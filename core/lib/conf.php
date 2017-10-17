<?php
/**
 *  +----------------------------------------------------------------------
 *  | 获取配置类 conf.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */

namespace core\lib;

use core\lib\drive\error\error;

class conf
{
    private static $_prefix = 'core_';
    private static $_ambient = IS_WIN ? ROOT_PATH.DS : '';
    public static $_conf = [];
    public static $_const = [];

    public function __construct($key,$val='')
    {
        return self::conf($key,$val);
    }

    /**
     * 获取单个配置项
     * @param $name
     * @param $file
     * @return mixed
     * @throws \Exception
     */
    static public function get($name,$file)
    {
        /**
         * 1. 判断获取资源种类
         * 2. 判断文件是否存在
         * 3. 判断配置是否存在
         * 4. 缓存配置
         */
        if (isset(self::$_conf[self::$_prefix.$file])){
            return self::$_conf[self::$_prefix.$file][$name];
        }else{
            $app_conf_file = self::$_ambient.APP.DS.$file.EXT;
            return self::get_conf($app_conf_file,$name);
        }
    }

    /**
     * @param $file
     * @param $name
     * @return mixed
     */
    private static function get_conf($file,$name)
    {
        $core_conf = self::$_ambient.DS.CORE_PATH.'/conf/'.$file.EXT;
        is_file($file) ? '' : $file = trim($core_conf,'/');
        $conf = self::_include_file($file);
        if (isset($conf[$name])){
            self::$_conf[self::$_prefix.$file] = $conf;
            return $conf[$name];
        }else{
            new error('没有该配置项，无法获取属性值~！'.$name ,404);
        }
        new error('没有该配置文件，无法获取对应内容~！'.$file,404);
    }

    /**
     * 获取所有配置
     * @param $file
     * @param bool $CORE # 判断是否是获取系统配置
     * @return mixed
     * @throws \Exception
     */
    static public function all($file, $CORE = false)
    {
        /**
         * 1. 判断文件是否存在
         * 2. 判断配置是否存在
         * 3. 缓存配置
         */
        if (isset(self::$_conf[self::$_prefix.$file])){
            return self::$_conf[self::$_prefix.$file];
        }else{
            if ($CORE == false){
                $path = str_replace('\\','/',ROOT_PATH.DS.APP.DS.$file.EXT);
            }else{
                $path = str_replace('\\','/',ROOT_PATH.DS.CORE_PATH.'/conf/'.$file.EXT);
            }
            if (is_file($path)){
                $conf = self::_include_file($path);
                self::$_conf[self::$_prefix.$file] = $conf;
                return $conf;
            }else{
                new error('没有该配置文件，无法获取对应内容~！'.$path,404);
            }
        }
    }

    /**
     * 系统配置常量
     * @param $key
     * @param string $val
     * @return mixed
     */
    private static function conf($key,$val='')
    {
        if (empty($key)){
            new error('第一个参数不能为空~！',400);
        }
        if (empty($val)){
            return self::$_const[self::$_prefix.$key];
        }
        self::$_const[self::$_prefix.$key] = $val;
    }




    /**
     * 加载文件
     * @param $file
     * @return mixed
     */
    static private function _include_file($file)
    {
        $file = str_replace('\\','/',$file);
        return include $file;
    }
}