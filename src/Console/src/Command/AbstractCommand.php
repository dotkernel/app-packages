<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dk-packages/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\Console\Command;

use Zend\Console\Adapter\AdapterInterface;
use ZF\Console\Route;

/**
 * Class AbstractCommand
 * @package Frontend\Console\Command
 */
abstract class AbstractCommand
{
    abstract public function __invoke(Route $route, AdapterInterface $console);
}
