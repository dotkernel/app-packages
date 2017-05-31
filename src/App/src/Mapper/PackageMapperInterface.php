<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dk-packages/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\App\Mapper;

use Dot\Mapper\Mapper\MapperInterface;
use Frontend\App\Entity\PackageEntity;

/**
 * Interface PackageMapperInterface
 * @package Frontend\App\Mapper
 */
interface PackageMapperInterface extends MapperInterface
{
    /**
     * @param PackageEntity $package
     * @param PackageEntity $requiredByPackage
     */
    public function insertRequiredByLink(PackageEntity $package, PackageEntity $requiredByPackage);

    /**
     * @param PackageEntity $package
     */
    public function deletePackageLinks(PackageEntity $package);
}
