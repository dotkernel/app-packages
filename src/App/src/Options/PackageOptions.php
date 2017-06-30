<?php
/**
 * Created by app-packages.
 * User: andi@apidemia.com
 */

declare(strict_types=1);

namespace Frontend\App\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class PackageOptions
 * @package Frontend\App\Options
 */
class PackageOptions extends AbstractOptions
{
    protected $blacklistPackages = [];

    /**
     * @return array
     */
    public function getBlacklistPackages(): array
    {
        return $this->blacklistPackages;
    }

    /**
     * @param array $blacklistPackages
     */
    public function setBlacklistPackages(array $blacklistPackages)
    {
        $this->blacklistPackages = $blacklistPackages;
    }
}
