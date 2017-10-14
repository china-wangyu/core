<?php
/**
 * Created by PhpStorm.
 * User: zhns_
 * Date: 2017/9/26
 * Time: 15:44
 */

namespace core\lib;


class route
{
    private static $_URL;       # 网页URL连接
    private static $_MODULE;
    private static $_CTRL;
    private static $_ACTION;

    /**
     *
     * route constructor.
     */
    public function __construct()
    {
        if (!isset(self::$_URL) or self::$_URL != $_SERVER['PHP_SELF']){
            self::$_URL = $_SERVER['REQUEST_URI'];
            self::_RUN();
        }

    }


    /**
     * 例 ：www.zhns.com/index.php/index/index/id/3/str/02
     * 1. 隐藏index.php
     * 2. 获取URL 参数部分
     * 3. 返回对应的模块、控制器、方法
     */
    private static function _RUN()
    {
        self::_CLEAR_ROUTE();
        self::_ROUTE();
    }

    private static function _CLEAR_ROUTE()
    {

        if (strpos(self::$_URL,'index.php') !== false){
            $url_object = explode('index.php',self::$_URL);
            self::$_URL = empty(isset($url)) ? $url_object[1] : '';
        }elseif(self::$_URL != '/' and !empty(self::$_URL)){
            $CLEAR_CORE_DIR = explode('/',trim(self::$_URL,'/'))
                ? explode('/',trim(self::$_URL,'/')) : '';
            if(isset($CLEAR_CORE_DIR[1])){
                $CLEAR = explode($_SERVER['PATH_INFO'],self::$_URL);
//                $CLEAR_TWO = explode('/',trim($CLEAR_ONE[0],'/'));
                self::$_URL = isset($CLEAR[1])
                    ?  $CLEAR[1].$_SERVER['PATH_INFO']
                    : $CLEAR[0].$_SERVER['PATH_INFO'];
            }else{
                self::_INIT_ROUTE();
            }
        }else{
            self::_INIT_ROUTE();
        }

    }

    /**
     * 解析路由
     */
    private static function _ROUTE()
    {

        if( isset(self::$_URL)){
            if (strpos(self::$_URL,'?') !== false){
                $url = self::_ANALYSIS_ROUTE_ONE();
            }else{
                $url = self::_ANALYSIS_ROUTE_TWO();
            }
            self::_GET_REWRITE($url['path']);
            self::_SET_GET($url['data']);
        }
    }

    /**
     * _init_route 初始化
     */
    private static function _INIT_ROUTE()
    {
        self::$_MODULE = conf::get('default_module','conf');
        self::$_CTRL = conf::get('default_controller','conf');
        self::$_ACTION = conf::get('default_action','conf');
        self::$_URL = DS.self::$_MODULE.DS.self::$_CTRL.DS.self::$_ACTION;
    }

    /**
     * 解析路由 第一种 传值模式为 ?id=2&p=22
     * @return array
     */
    private static function _ANALYSIS_ROUTE_ONE()
    {
        $url = explode('?',trim(self::$_URL,'/'));
        $url_data = explode('&',$url[1]);
        $url_data = array_merge(explode('=',$url_data[0]),explode('=',$url_data[1]));
        $url_link = $url_arr = explode('/',trim($url[0],'/'));
        return ['path' => $url_link, 'data' => isset($url_data) ? $url_data : ''];
    }

    /**
     * 解析路由 第二种 传值模式为 /id/2/p/22
     * @return array
     */
    private static function _ANALYSIS_ROUTE_TWO()
    {
        $url = explode('/',trim(str_replace('\\','/',self::$_URL),'/'));
        $url_link = [$url[0],$url[1],$url[2]];
        if(isset($url[3])){
            unset($url[0],$url[1],$url[2]);
            array_multisort($url);
            $url_data = $url ? $url : '';
        }
        return ['path' => $url_link, 'data' => isset($url_data) ? $url_data : ''];
    }

    private static function _GET_REWRITE($path)
    {

        if(isset($path[0])){
            self::$_MODULE = $path[0];
            unset($path[0]);
        }
        if (isset($path[1])){
            self::$_CTRL = $path[1];
            unset($path[1]);
        }else{
            self::$_CTRL = conf::get('default_controller','conf');
        }
        if (isset($path[2])){
            self::$_ACTION = $path[2];
            unset($path[2]);
        }else{
            self::$_ACTION = conf::get('default_action','conf');
        }
    }


    public function _GET_MODULE()
    {
        return self::$_MODULE;
    }


    public function _GET_CTRL()
    {
        return self::$_CTRL;
    }


    public function _GET_ACTION()
    {
        return self::$_ACTION;
    }

    private static function _SET_GET($data)
    {
        if (!empty($data) and is_array($data)){
            ###  获取URL参数
            $count = count($data) + 1;
            $i = 0;
            while ($i < $count){
                if (isset($data[$i + 1])){
                    $_GET[$data[$i]] = $data[$i + 1];
                }
                $i = $i + 2;
            }
        }
    }

}