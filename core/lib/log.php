<?php
/**
 * Created by PhpStorm.
 * User: zhns_
 * Date: 2017/10/10
 * Time: 17:23
 */

namespace core\lib;


class log
{
    public static $class;

    static public function init()
    {
        $driveType = 'file';
        $logClass = str_replace('/','\\',CORE_DRIVE_PATH).LOG_PATH.$driveType;
        self::$class = new $logClass;
    }

    static public function log($msg,$file='')
    {
        self::$class->log($msg,$file);
    }

}