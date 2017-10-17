<?php
/**
 *  +----------------------------------------------------------------------
 *  | 日志类 log.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */

namespace core\lib;


class log
{
    public static $class;

    static public function init()
    {
        $logConf = conf::get('log','conf');
        $logClass = str_replace('/','\\',CORE_DRIVE_PATH.LOG_PATH.$logConf['type']);
        self::$class = new $logClass();
    }

    static public function log($msg,$file='')
    {
        self::$class->log($msg,$file);
    }

}