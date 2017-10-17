<?php
/**
 *  +----------------------------------------------------------------------
 *  | 加载类 loader.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */

namespace core\lib;

use core\lib\drive\dir\file;
use core\lib\drive\dir\mkdir;

class loader
{
    private static $classMap = array();

    public function __construct()
    {
        self::register();
    }

    /**
     * 框架运行入口
     * @throws \Exception
     */
    public static function run()
    {
        self::_validate_app_exist();
        $route = new route();
        log::init();
        log::log('route = [ module => '.$route::module().', ctrl => '.$route::ctrl().', action=> '.$route::action().' ]');
        file::_include_ctrl_file();
    }


    /**
     * 自动加载类库
     * @param $className
     * @return bool
     */
    public static function load($className)
    {
        # 例：new \loader\lib\route();
        if (isset(self::$classMap[$className])) {
            return true;
        }
        $file = str_replace('\\', '/', $className . EXT);
        if (is_file($file)) {
            self::_include_file($file);
            self::$classMap[$className] = str_replace('/', '\\', $className);
        } else {
            return false;
        }
    }

    // 注册自动加载机制
    public static function register($autoload = '')
    {
        $loader = str_replace('/', '\\', CORE_LIB_PATH) . 'loader::load';
        spl_autoload_register($autoload ?: $loader, true, true);
    }

    private static function _validate_app_exist()
    {
        if (IS_WIN) {
            $app = ROOT_PATH . DS . APP;
        } else {
            $app = APP;
        }
        if (!is_dir($app)) {
            mkdir::_auto_generation();
        }
    }

    static private function _include_file($file)
    {
        if (IS_WIN) {
            $file = ROOT_PATH . DS . $file;
        }
        return include str_replace('\\', '/', $file);
    }

}