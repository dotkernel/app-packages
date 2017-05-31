<?php
/**
 * @see https://github.com/dotkernel/dk-packages/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dk-packages/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Frontend\App\Mapper;

use Dot\Mapper\Mapper\AbstractDbMapper;
use Zend\Db\Sql\Select;

/**
 * Class CronStatDbMapper
 * @package Frontend\App\Mapper
 */
class CronStatDbMapper extends AbstractDbMapper
{
    /**
     * @param Select $select
     * @param array $options
     * @return Select
     */
    public function findByName(Select $select, array $options)
    {
        return $select->where(['cronName' => $options['cronName']]);
    }
}
