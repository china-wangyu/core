<?php
/**
 *  +----------------------------------------------------------------------
 *  | 公共函数类 common.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */

namespace core\lib;

use core\lib\drive\dir\dir;

class common
{
    private static $_COMMON_MAP = [];



    public static function _RUN($module)
    {
        self::_LOAD_APP_COMMON();
        self::_LOAD_MODULE_COMMON($module);
    }

    private static function _LOAD_APP_COMMON()
    {
        $INDEX_NAME = '_'.APP;
        $file = dir::_dir(APP).DS.'common'.EXT;
        if (is_file($file)){
            self::_REQUIRE_ONE_FILE($file);
            if (!isset(self::$_COMMON_MAP[$INDEX_NAME])){
                self::$_COMMON_MAP[$INDEX_NAME] = $file;
            }
        }
    }

    private static function _LOAD_MODULE_COMMON($module)
    {
        $INDEX_NAME = '_'.APP.'_'.$module;
        $file = dir::_dir(APP).DS.$module.DS.'common'.EXT;
        if (is_file($file)){
            self::_REQUIRE_ONE_FILE($file);
            if (!isset(self::$_COMMON_MAP[$INDEX_NAME])){
                self::$_COMMON_MAP[$INDEX_NAME] = $file;
            }
        }
    }

    private static function _REQUIRE_ONE_FILE($file)
    {
        require_once $file;
    }

}