<?php
/**
 *  +----------------------------------------------------------------------
 *  | 目录类 dir.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */

namespace core\lib\drive\dir;


class dir
{
    private static $dir;


    private static function _get_class_dir()
    {
        self::$dir = str_replace('/','\\',self::$dir);
    }


    private static function _validate_dir()
    {
        if (is_dir(self::$dir)){
            self::$dir = str_replace('\\','/',self::$dir);
        }
    }

    private static function _validate_dir_environment()
    {
        if (IS_WIN){
            self::$dir =  ROOT_PATH.DS.self::$dir;
        }
    }

    public static function _dir($dir,$type=false)
    {
        self::$dir = $dir;
        self::_validate_dir_environment();
        self::_validate_dir();
        $type == true ? self:: _get_class_dir() : '';
        return self::$dir;
    }
}