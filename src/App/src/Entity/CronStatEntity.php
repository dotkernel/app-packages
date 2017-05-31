<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dk-packages/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\App\Entity;

use Dot\Mapper\Entity\Entity;

/**
 * Class CronStatEntity
 * @package Frontend\App\Entity
 */
class CronStatEntity extends Entity
{
    /** @var  int */
    protected $id;

    /** @var  string */
    protected $cronName;

    /** @var  string */
    protected $lastRun;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCronName()
    {
        return $this->cronName;
    }

    /**
     * @param string $cronName
     */
    public function setCronName($cronName)
    {
        $this->cronName = $cronName;
    }

    /**
     * @return string
     */
    public function getLastRun()
    {
        return $this->lastRun;
    }

    /**
     * @param string $lastRun
     */
    public function setLastRun($lastRun)
    {
        $this->lastRun = $lastRun;
    }
}
