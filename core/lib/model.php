<?php
/**
 *  +----------------------------------------------------------------------
 *  | 模型类 model.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */

namespace core\lib;


class model extends \Medoo\Medoo
{
    public function __construct()
    {

        $dbObject = conf::all('db');
        parent::__construct($dbObject);
    }
}