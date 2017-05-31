<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dk-packages/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\Console\Command;

use Dot\AnnotatedServices\Annotation\Inject;
use Dot\AnnotatedServices\Annotation\Service;
use Frontend\App\Service\PackageService;
use Zend\Console\Adapter\AdapterInterface;
use ZF\Console\Route;

/**
 * Class UpdatePackagesCommand
 * @package Frontend\Console\Command
 *
 * @Service
 */
class UpdatePackagesCommand extends AbstractCommand
{
    /** @var  PackageService */
    protected $packageService;

    /**
     * HelloCommand constructor.
     * @param PackageService $packageService
     *
     * @Inject({PackageService::class})
     */
    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * @param Route $route
     * @param AdapterInterface $console
     * @return int
     */
    public function __invoke(Route $route, AdapterInterface $console)
    {
        $this->packageService->updateDotKernelPackages();
        $this->packageService->updateRequiredByPackages();
        $console->writeLine('Packages updated with success!');
        return 0;
    }
}
