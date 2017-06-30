<?php
/**
 * Created by app-packages.
 * User: andi@apidemia.com
 */

declare(strict_types=1);

namespace Frontend\App\Factory;

use Frontend\App\Options\PackageOptions;
use Psr\Container\ContainerInterface;

/**
 * Class PackageOptionsFactory
 * @package Frontend\App\Factory
 */
class PackageOptionsFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new PackageOptions($container->get('config')['package_options']);
    }
}
