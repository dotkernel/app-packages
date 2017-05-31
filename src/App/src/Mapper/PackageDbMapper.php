<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/html/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\App\Mapper;

use Dot\Mapper\Event\MapperEvent;
use Dot\Mapper\Mapper\AbstractDbMapper;
use Dot\Mapper\Mapper\MapperManager;
use Frontend\App\Entity\PackageEntity;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

/**
 * Class PackageDbMapper
 * @package Frontend\App\Mapper
 */
class PackageDbMapper extends AbstractDbMapper implements PackageMapperInterface
{
    /** @var string  */
    protected $packageLinkTable = 'package_required';

    /** @var \Zend\Db\Adapter\Driver\StatementInterface  */
    protected $insertPackageLinkStatement;

    /**
     * PackageDbMapper constructor.
     * @param MapperManager $mapperManager
     * @param array $options
     */
    public function __construct(MapperManager $mapperManager, array $options = [])
    {
        parent::__construct($mapperManager, $options);
        $this->insertPackageLinkStatement = $this->getAdapter()->createStatement(
            'INSERT INTO `' . $this->packageLinkTable . '` VALUES (?, ?)'
        );
    }

    /**
     * @param PackageEntity $package
     * @param PackageEntity $requiredByPackage
     * @return int
     */
    public function insertRequiredByLink(PackageEntity $package, PackageEntity $requiredByPackage)
    {
        $result = $this->insertPackageLinkStatement->execute([$requiredByPackage->getId(), $package->getId()]);
        if ($result->getAffectedRows() < 1) {
            throw new \RuntimeException('Failed to insert package association');
        }

        return $result->getAffectedRows();
    }

    /**
     * @param PackageEntity $package
     * @return int
     */
    public function deletePackageLinks(PackageEntity $package)
    {
        $delete = $this->getSql()->delete($this->packageLinkTable)
            ->where(['requiredById' => $package->getId()]);
        $r = $this->getSql()->prepareStatementForSqlObject($delete)->execute();
        return $r->getAffectedRows();
    }

    /**
     * @param MapperEvent $e
     */
    public function onAfterLoad(MapperEvent $e)
    {
        /** @var PackageEntity $entity */
        $package = $e->getParam('entity');

        if (empty($package->getPackageIdLinks())) {
            $select = $this->getSlaveSql()->select()->from($this->packageLinkTable)
                ->where(['packageId' => $package->getId()]);

            $stmt = $this->getSlaveSql()->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {
                $resultSet = new ResultSet(ResultSet::TYPE_ARRAY);
                $resultSet->initialize($result);

                $requiredByIds = [];
                foreach ($resultSet as $row) {
                    $requiredByIds[] = $row['requiredById'];
                }

                if (!empty($requiredByIds)) {
                    $package->setPackageIdLinks($requiredByIds);
                } else {
                    $package->setPackageIdLinks([]);
                }
            } else {
                $package->setPackageIdLinks([]);
            }
        }
    }
}
