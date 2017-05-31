<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/html/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\App\Entity;

use Dot\Hydrator\ClassMethodsCamelCase;

/**
 * Class PackageEntityHydrator
 * @package Frontend\App\Entity
 */
class PackageEntityHydrator extends ClassMethodsCamelCase
{
    public function __construct()
    {
        parent::__construct();

        $this->addStrategy('versions', new PackageHydratorDecodeStrategy());
        $this->addStrategy('requires', new PackageHydratorDecodeStrategy());
    }
}
