<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/html/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\App\Controller;

use Dot\AnnotatedServices\Annotation\Service;
use Dot\AnnotatedServices\Annotation\Inject;
use Dot\Controller\AbstractActionController;
use Dot\Controller\Plugin\FlashMessenger\FlashMessengerPlugin;
use Dot\Controller\Plugin\Forms\FormsPlugin;
use Dot\Controller\Plugin\TemplatePlugin;
use Dot\Controller\Plugin\UrlHelperPlugin;
use Frontend\App\Service\PackageService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Form\Form;
use Zend\Session\Container;

/**
 * Class PackageController
 * @package Frontend\App\Controller
 *
 * @method UrlHelperPlugin|UriInterface url(string $route = null, array $params = [])
 * @method FlashMessengerPlugin messenger()
 * @method FormsPlugin|Form forms(string $name = null)
 * @method TemplatePlugin|string template(string $template = null, array $params = [])
 * @method Container session(string $namespace)
 *
 * @Service
 */
class PackageController extends AbstractActionController
{
    /** @var  PackageService */
    protected $packageService;

    /**
     * PackageController constructor.
     * @param PackageService $packageService
     *
     * @Inject({PackageService::class})
     */
    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * @return ResponseInterface
     */
    public function indexAction(): ResponseInterface
    {
        $packages = $this->packageService->getPackages();
        if ($this->packageService->getLastUpdated() == 'never') {
            $lastUpdatedFormated = 'never';
        } else {
            $lastUpdate = new \DateTime($this->packageService->getLastUpdated());
            $lastUpdated = date_diff($lastUpdate, new \DateTime());
            $format = $lastUpdated->h === 0 ? '%i minutes' : '%h hours';
            $lastUpdatedFormated = $lastUpdated->format($format);
        }
        return new HtmlResponse(
            $this->template('app::packages', ['data' => $packages, 'lastUpdated' => $lastUpdatedFormated])
        );
    }
}
