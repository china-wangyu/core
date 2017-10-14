<?php
/**
 * Created by PhpStorm.
 * User: china-wangyu@aliyun.com
 * Date: 2017/9/26
 * Time: 15:36
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