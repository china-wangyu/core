<?php
/**
 *  +----------------------------------------------------------------------
 *  | 日志类 log.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */

namespace core\lib;


class route
{
    # 路由url资源
    private static $_URL = [
        'url'           => '',
        'website_name'  => '',
        'module'        => '',
        'ctrl'          => '',
        'action'          => ''
    ];

    /**
     *
     * route constructor.
     */
    public function __construct()
    {
        if (!isset(self::$_URL['url']) or self::$_URL != $_SERVER['PHP_SELF']){
            self::$_URL['url'] = $_SERVER['REQUEST_URI'];
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

    /**
     * clear url
     */
    private static function _CLEAR_ROUTE()
    {
        if (strpos(self::$_URL['url'],'index.php') !== false){
            $CLEAR_CORE_DIR = explode('index.php',self::$_URL['url']);
            self::$_URL['website_name'] = $CLEAR_CORE_DIR[0];
            self::$_URL['url'] = empty(isset($url)) ? $CLEAR_CORE_DIR[1] : '';
        }elseif(self::$_URL != '/' and !empty(self::$_URL['url'])){
            $SCRIPT_NAME_EXPLODE = explode('/',trim($_SERVER['SCRIPT_NAME'],'/'));
            $CLEAR_CORE_DIR = explode('/',trim(self::$_URL['url'],'/'))
                ? explode('/',trim(self::$_URL['url'],'/')) : '';
            if (isset($SCRIPT_NAME_EXPLODE[1])){
                self::$_URL['website_name'] = $SCRIPT_NAME_EXPLODE[0];
                unset($CLEAR_CORE_DIR[0]);
                if (!empty($CLEAR_CORE_DIR)){
                    $CLEAR_DATA = array_chunk($CLEAR_CORE_DIR,count($CLEAR_CORE_DIR));
                    $CLEAR_CORE_DIR = $CLEAR_DATA[0];
                }else{
                    $CLEAR_CORE_DIR = '';
                }
            }

            if(isset($CLEAR_CORE_DIR[1])){
                self::$_URL['url'] = join('/',$CLEAR_CORE_DIR);
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
        if( isset(self::$_URL['url'])){
            if (strpos(self::$_URL['url'],'?') !== false){
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
        self::$_URL = [
            'url'           => DS.conf::get('default_module','conf').DS.conf::get('default_controller','conf').DS.conf::get('default_action','conf'),
            'website_name'  => '',
            'module'        => conf::get('default_module','conf'),
            'ctrl'          => conf::get('default_controller','conf'),
            'action'          => conf::get('default_action','conf')
        ];
    }

    /**
     * 解析路由 第一种 传值模式为 ?id=2&p=22
     * @return array
     */
    private static function _ANALYSIS_ROUTE_ONE()
    {
        $url = explode('?',trim(self::$_URL['url'],'/'),2);
        parse_str($url[1],$params);
        $url_link = $url_arr = explode('/',trim($url[0],'/'));
        return ['path' => $url_link, 'data' => empty($params) ? $params : ''];
    }

    /**
     * 解析路由 第二种 传值模式为 /id/2/p/22
     * @return array
     */
    private static function _ANALYSIS_ROUTE_TWO()
    {
        $url = explode('/',trim(str_replace('\\','/',self::$_URL['url']),'/'));
        $url_link = [$url[0],$url[1],$url[2]];
        if(isset($url[3])){
            unset($url[0],$url[1],$url[2]);
            $url_data = array_chunk($url,count($url));
            $params = $url_data[0];
        }
        $url_data = isset($params) ? $params : '';
        return ['path' => $url_link, 'data' => $url_data];
    }

    private static function _GET_REWRITE($path)
    {
        if(isset($path[0])){
            self::$_URL['module'] = $path[0];
        }
        if (isset($path[1])){
            self::$_URL['ctrl'] = $path[1];
        }else{
            self::$_URL['ctrl'] = conf::get('default_controller','conf');
        }
        if (isset($path[2])){
            self::$_URL['action'] = $path[2];
        }else{
            self::$_URL['action'] = conf::get('default_action','conf');
        }
    }


    public static function module()
    {
        return self::$_URL['module'];
    }



    public static function ctrl()
    {
        return self::$_URL['ctrl'];
    }


    public static function action()
    {
        return self::$_URL['action'];
    }

    public static function website_name()
    {
        return self::$_URL['website_name'];
    }

    private static function _SET_GET($data)
    {
        if (!empty($data) and is_array($data)){
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