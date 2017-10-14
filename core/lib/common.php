<?php
/**
 * Created by PhpStorm.
 * User: china-wangyu@aliyun.com
 * Date: 2017/10/10
 */
namespace core\lib;

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
        if (is_file(ROOT_PATH.DS.APP.DS.'common'.EXT)){
            self::_REQUIRE_ONE_FILE(ROOT_PATH.DS.APP.DS.'common'.EXT);
            if (!isset(self::$_COMMON_MAP[$INDEX_NAME])){
                self::$_COMMON_MAP[$INDEX_NAME] = ROOT_PATH.DS.APP.DS.'common'.EXT;
            }
        }
    }

    private static function _LOAD_MODULE_COMMON($module)
    {
        $INDEX_NAME = '_'.APP.'_'.$module;
        if (is_file(ROOT_PATH.DS.APP.DS.$module.DS.'common'.EXT)){
            self::_REQUIRE_ONE_FILE(ROOT_PATH.DS.APP.DS.$module.DS.'common'.EXT);
            if (!isset(self::$_COMMON_MAP[$INDEX_NAME])){
                self::$_COMMON_MAP[$INDEX_NAME] = ROOT_PATH.DS.APP.DS.$module.DS.'common'.EXT;
            }
        }
    }

    private static function _REQUIRE_ONE_FILE($file)
    {
        require_once $file;
    }

}