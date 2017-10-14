<?php
/**
 * Created by PhpStorm.
 * User: china-wangyu@aliyun.com
 * Date: 2017/9/26
 * Time: 15:36
 */

namespace core\lib;


use core\lib\drive\dir\mkdir;

class loader
{
    private static $classMap = array();


    /**
     * 框架运行入口
     * @throws \Exception
     */
    public static function run()
    {
        self::_is_app();
        $route = new \core\lib\route();
        $module = $route->_GET_MODULE();  #   模块名称
        $ctrlClass = $route->_GET_CTRL(); #   控制器名称
        $action = $route->_GET_ACTION();  #   方法名
        \core\lib\log::init();
        \core\lib\log::log('module : '.$module.' ctrl : '.$ctrlClass.' action : '.$action);

        # 控制器文件路径
        $ctrlFile = self::_VALIDATE_CTRL_FILE(APP.DS.$module.APP_CTRL_PATH,$ctrlClass);
        # 类名
        $ctrlClass = str_replace('/','\\',DS.APP.DS.$module.APP_CTRL_PATH)
            .$ctrlClass;
        if (is_file($ctrlFile)){
            self::_include_file($ctrlFile);
            $ctrl = new $ctrlClass();
            $ctrl->$action();
        }else{
            throw new \Exception("请求的控制器不存在！ controller => ".$ctrlFile);
        }
    }

    private static function _VALIDATE_CTRL_FILE($path,$fileName,$ext=EXT)
    {
        if (IS_WIN){
            $path = ROOT_PATH.DS.$path;
        }
        $path = str_replace('\\','/',$path);
        if (is_file($path.ucfirst($fileName).$ext)){
            return $path.ucfirst($fileName).$ext;
        }elseif(is_file($path.lcfirst($fileName).$ext)){
            return $path.lcfirst($fileName).$ext;
        }
        return $path.$fileName.$ext;
    }

    /**
     * 自动加载类库 load()
     * @param $class 类名
     * @return bool
     */
    public static function load($class)
    {
        # 举例  new \loader\lib\route();
        if (isset(self::$classMap[$class])){
            return true;
        }
        $class = str_replace('\\', '/', $class);

        $file =  $class .EXT;
        if (is_file($file)) {
            self::_include_file($file);
            self::$classMap[$class] = $class;
        } else {
            return false;
        }
    }

    /**
     * 注册自动加载机制
     * @param string $autoload
     */
    public static function register($autoload = '')
    {
        $loader = str_replace('/','\\',CORE_LIB_PATH).'loader::load';
        // 注册系统自动加载
        spl_autoload_register($autoload ?: $loader, true, true);
    }

    private static function _is_app()
    {
        if (!is_dir(ROOT_PATH.DS.APP)){
            mkdir::_app();
        }
    }






    /**
     * 加载文件
     * @param $file
     * @return mixed
     */
    static private function _include_file($file)
    {
        return include str_replace('\\','/',trim($file,'/'));
    }

}