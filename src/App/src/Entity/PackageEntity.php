<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/html/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\App\Entity;

use Dot\Mapper\Entity\Entity;

/**
 * Class PackageEntity
 * @package Frontend\App\Entity
 */
class PackageEntity extends Entity
{
    protected $hydrator = PackageEntityHydrator::class;

    /** @var  int */
    protected $id;

    /** @var string  */
    protected $name;

    /** @var string */
    protected $description;

    /** @var  string */
    protected $repository;

    /** @var  string */
    protected $license;

    /** @var  string */
    protected $versions;

    /** @var  string */
    protected $requires;

    /** @var  string */
    protected $updated;

    /** @var array  */
    protected $packageIdLinks = [];

    /** @var PackageEntity[] */
    protected $requiredByPackages = [];

    /**
     * PackageEntity constructor.
     */
    public function __construct()
    {
        $this->addIgnoredProperties(['requiredByPackages']);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param mixed $repository
        $hydrator = new ClassMethodsCamelCase();
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @param mixed $license
     */
    public function setLicense($license)
    {
        $this->license = $license;
    }

    /**
     * @return mixed
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * @param mixed $versions
     */
    public function setVersions($versions)
    {
        $this->versions = $versions;
    }

    /**
     * @return string
     */
    public function getRequires()
    {
        return $this->requires;
    }

    /**
     * @param string $requires
     */
    public function setRequires($requires)
    {
        $this->requires = $requires;
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return $this->updated;
    }

    /**
     * @param string $updated
     */
    public function setUpdated(string $updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return array
     */
    public function getPackageIdLinks(): array
    {
        return $this->packageIdLinks;
    }

    /**
     * @param array $packageIdLinks
     */
    public function setPackageIdLinks(array $packageIdLinks)
    {
        $this->packageIdLinks = $packageIdLinks;
    }

    /**
     * @return PackageEntity[]
     */
    public function getRequiredByPackages(): array
    {
        return $this->requiredByPackages;
    }

    /**
     * @param PackageEntity[] $requiredByPackages
     */
    public function setRequiredByPackages(array $requiredByPackages)
    {
        $this->requiredByPackages = $requiredByPackages;
    }
}
