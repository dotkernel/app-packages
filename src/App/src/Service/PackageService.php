<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/html/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\App\Service;

use Dot\AnnotatedServices\Annotation\Inject;
use Dot\Mapper\Mapper\MapperInterface;
use Dot\Mapper\Mapper\MapperManager;
use Frontend\App\Entity\CronStatEntity;
use Frontend\App\Entity\PackageEntity;
use Frontend\App\Mapper\PackageMapperInterface;
use Packagist\Api\Client;
use Packagist\Api\Result\Package;
use Packagist\Api\Result\Result;

class PackageService
{
    const CRON_UPDATE_PACKAGES = 'update_packages';
    const CRON_INIT_PACKAGES = 'initialize_packages';


    /** @var  Client */
    protected $client;

    /** @var  MapperManager */
    protected $mapperManager;

    /**
     * PackageService constructor.
     * @param Client $client
     * @param MapperManager $mapperManager
     *
     * @Inject({Client::class, MapperManager::class})
     */
    public function __construct(Client $client, MapperManager $mapperManager)
    {
        $this->client = $client;
        $this->mapperManager = $mapperManager;
    }

    /**
     * @return array|mixed
     */
    public function getPackages()
    {
        /** @var PackageMapperInterface $packageMapper */
        $packageMapper = $this->mapperManager->get(PackageEntity::class);
        $packages = $packageMapper->find('all', ['order'=>['name ASC']]);

        $this->initRequiredByPackages($packages);

        return $packages;
    }

    /**
     * @return string
     */
    public function getLastUpdated()
    {
        /** @var MapperInterface $cronStatMapper */
        $cronStatMapper = $this->mapperManager->get(CronStatEntity::class);
        /** @var CronStatEntity[] $cronStat */
        $cronStat = $cronStatMapper->find('byName', ['cronName' => self::CRON_UPDATE_PACKAGES]);
        if (!empty($cronStat)) {
            $cronStat = $cronStat[0];
        }

        return $cronStat->getLastRun();
    }

    /**
     * @param array $packages
     */
    private function initRequiredByPackages(array $packages)
    {
        $packagesData = [];
        foreach ($packages as $package) {
            $packagesData[$package->getId()] = $package;
        }

        foreach ($packages as $package) {
            $requiredByPackages = [];
            foreach ($package->getPackageIdLinks() as $requiredByPackageId) {
                $requiredByPackages[$packagesData[$requiredByPackageId]->getName()] =
                    $packagesData[$requiredByPackageId]->getRepository();
            }
            ksort($requiredByPackages);
            $package->setRequiredByPackages($requiredByPackages);
        }
    }

    /**
     * Update package associations
     */
    public function updateRequiredByPackages()
    {
        /** @var PackageMapperInterface $packageMapper */
        $packageMapper = $this->mapperManager->get(PackageEntity::class);
        $packages = $this->getPackages();
        $packagesIds = [];
        foreach ($packages as $package) {
            $packagesIds[$package->getName()] = $package;
        }
        foreach ($packages as $package) {
            $requires = $package->getRequires();
            $packageMapper->deletePackageLinks($package);

            foreach ($requires as $require => $url) {
                /** @var PackageEntity $p */
                $requiredPackage = $packagesIds[$require];
                $packageMapper->insertRequiredByLink($package, $requiredPackage);
            }
        }
    }

    /**
     * @param PackageEntity[] $packages
     */
    public function savePackages($packages)
    {
        /** @var PackageMapperInterface $mapper */
        $mapper = $this->mapperManager->get(PackageEntity::class);
        foreach ($packages as $package) {
            $mapper->save($package);
        }
    }

    public function updateDotKernelPackages()
    {
        /** @var MapperInterface $cronStatMapper */
        $cronStatMapper = $this->mapperManager->get(CronStatEntity::class);
        $cronStat = $cronStatMapper->find('byName', ['cronName' => self::CRON_UPDATE_PACKAGES]);
        if (empty($cronStat)) {
            $cronStat = new CronStatEntity();
            $cronStat->setCronName(self::CRON_UPDATE_PACKAGES);
        } else {
            $cronStat = $cronStat[0];
        }

        $apiPackages = $this->getPackagesFromApi('dotkernel');
        $existingPackages = $this->getPackages();

        /** @var PackageMapperInterface $packageMapper */
        $packageMapper = $this->mapperManager->get(PackageEntity::class);

        $packages = [];
        /** @var PackageEntity $packageEntity */
        foreach ($existingPackages as $packageEntity) {
            $packages[$packageEntity->getName()] = $packageEntity;
        }

        $packagesToSave = [];
        foreach ($apiPackages as $package) {
            $data = $this->getPackageData($package);

            if (array_key_exists($data['name'], $packages)) {
                /** @var PackageEntity $entity */
                $entity = $packageMapper->getHydrator()->hydrate($data, $packages[$data['name']]);
            } else {
                /** @var PackageEntity $entity */
                $entity = $packageMapper->getHydrator()->hydrate($data, new PackageEntity());
            }

            $packagesToSave[] = $entity;
        }

        $this->savePackages($packagesToSave);

        $cronStat->setLastRun(date("Y:m:d H:i:s", time()));
        $cronStatMapper->save($cronStat);
    }


    /**
     * @param string $vendor
     * @return Result[]
     */
    public function getPackagesFromApi(string $vendor)
    {
        $packages = $this->client->search($vendor);
        return $packages;
    }

    /**
     * @param Result $package
     * @return array
     */
    private function getPackageData(Result $package)
    {
        $packageDetails = $this->client->get($package->getName());
        /** @var Package\Version[] $versions */
        $versions = $packageDetails->getVersions();
        $allRequires = $versions['dev-master']->getRequire();

        $data['name'] = $packageDetails->getName();
        $data['description'] = $packageDetails->getDescription();
        $data['repository'] = $packageDetails->getRepository();
        $data['license'] = $versions['dev-master']->getLicense()[0];
        $data['versions'] = json_encode($this->getPackageVersions($versions));
        $data['requires'] = json_encode($this->getDotRequires($allRequires));
        return $data;
    }

    /**
     * @param array $versions
     * @return array
     */
    private function getPackageVersions(array $versions)
    {
        $versionsArray = [];
        foreach ($versions as $version => $versionData) {
            $versionName = $versions[$version]->getVersion();
            $versionUrl = $versions['dev-master']->getDist()->getUrl();
            $versionsArray[$versionName] = $versionUrl;
        }
        return $versionsArray;
    }

    /**
     * @param array $allRequires
     * @return array
     */
    private function getDotRequires(array $allRequires)
    {
        $dotRequires = [];
        foreach ($allRequires as $require => $version) {
            if ($result = substr($require, 0, 10) == "dotkernel/") {
                $dotRequires[$require] = $this->client->get($require)->getRepository();
            }
        }
        return $dotRequires;
    }
}
