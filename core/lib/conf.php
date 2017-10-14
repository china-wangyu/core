<?php
/**
 * Created by PhpStorm.
 * User: china-wangyu@aliyun.com
 * Date: 2017/10/10
 * Time: 15:44
 */

namespace core\lib;




use function Composer\Autoload\includeFile;

class conf
{
    public static $conf = [];


    /**
     * 获取单个配置项
     * @param $name
     * @param $file
     * @param bool $CORE # 判断是否是获取系统配置
     * @return mixed
     * @throws \Exception
     */
    static public function get($name,$file,$CORE = false)
    {
        /**
         * 1. 判断获取资源种类
         * 2. 判断文件是否存在
         * 3. 判断配置是否存在
         * 4. 缓存配置
         */
        if (isset(self::$conf[$file])){
            return self::$conf[$file][$name];
        }else{
            if ($CORE == false){
                $path = str_replace('\\','/',ROOT_PATH.DS.APP.DS.$file.EXT);
            }else{
                $path = str_replace('\\','/',ROOT_PATH.CORE_PATH.'/conf/'.$file.EXT);
            }
            if (is_file($path)){
                $conf = self::_include_file($path);
                if (isset($conf[$name])){
                    self::$conf[$file] = $conf;
                    return $conf[$name];
                }else{
                    throw new \Exception('没有该配置项，无法获取属性值~！'.$name );
                }
            }else{
                throw new \Exception('没有该配置文件，无法获取对应内容~！'.$path);
            }
        }
    }


    /**
     * 获取所以配置
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
        if (isset(self::$conf[$file])){
            return self::$conf[$file];
        }else{
            if ($CORE == false){
                $path = str_replace('\\','/',ROOT_PATH.DS.APP.DS.$file.EXT);
            }else{
                $path = str_replace('\\','/',ROOT_PATH.CORE_PATH.'/conf/'.$file.EXT);
            }
            if (is_file($path)){
                $conf = self::_include_file($path);
                self::$conf[$file] = $conf;
                return $conf;
            }else{
                throw new \Exception('没有该配置文件，无法获取对应内容~！'.$path);
            }
        }
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