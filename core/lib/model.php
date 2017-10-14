<?php
/**
 * Created by PhpStorm.
 * User: zhns_
 * Date: 2017/10/10
 * Time: 15:08
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