<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/html/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\App\Entity;

use Zend\Hydrator\Strategy\StrategyInterface;
use Zend\Json\Json;

/**
 * Class PackageHydratorDecodeStrategy
 * @package Frontend\App\Entity
 */
class PackageHydratorDecodeStrategy implements StrategyInterface
{
    /**
     * @param mixed $value
     * @return string
     */
    public function extract($value)
    {
        return Json::encode($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function hydrate($value)
    {
        return Json::decode($value, Json::TYPE_ARRAY);
    }
}
