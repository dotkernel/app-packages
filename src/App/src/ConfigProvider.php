<?php
/**
 * @see https://github.com/dotkernel/frontend/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/frontend/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\App;

use Dot\AnnotatedServices\Factory\AnnotatedServiceFactory;
use Dot\Mapper\Factory\DbMapperFactory;
use Frontend\App\Entity\CronStatEntity;
use Frontend\App\Entity\PackageEntity;
use Frontend\App\Entity\PackageEntityHydrator;
use Frontend\App\Mapper\CronStatDbMapper;
use Frontend\App\Mapper\PackageDbMapper;
use Frontend\App\Service\PackageService;
use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * Class ConfigProvider
 * @package Frontend\App
 */
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),

            'templates' => $this->getTemplates(),

            'dot_mapper' => $this->getMappers(),

            'dot_hydrator' => $this->getHydrators(),
        ];
    }

    public function getHydrators()
    {
        return [
            'hydrator_manager' => [
                'factories' => [
                    PackageEntityHydrator::class => InvokableFactory::class,
                ]
            ]
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                PackageService::class => AnnotatedServiceFactory::class,
            ],
            'aliases' => [
                'PackageService' => PackageService::class,
            ]
        ];
    }

    public function getMappers(): array
    {
        return [
            'mapper_manager' => [
                'factories' => [
                    PackageDbMapper::class => DbMapperFactory::class,
                    CronStatDbMapper::class => DbMapperFactory::class,
                ],
                'aliases' => [
                    PackageEntity::class => PackageDbMapper::class,
                    CronStatEntity::class => CronStatDbMapper::class,
                ]
            ],
        ];
    }

    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app' => [__DIR__ . '/../templates/app'],
                'error' => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }
}
