<?php
/**
 * Created by PhpStorm.
 * User: china-wangyu@aliyun.com
 * Date: 2017/10/12
 * Time: 20:52
 */

namespace core\lib;

class ctrl
{
    private static $_WEB_DIR;
    private static $_MODULE;
    private static $_CTRL;
    private static $_ACTION;
    public $assign = array();

    public function __construct()
    {
        common::_RUN(\core\lib\route::_GET_MODULE());
        self::$_WEB_DIR = \core\lib\route::_GET_WEB_DIR();
        self::$_MODULE = \core\lib\route::_GET_MODULE();
        self::$_CTRL = \core\lib\route::_GET_CTRL();
        self::$_ACTION = \core\lib\route::_GET_ACTION();
    }

    /**
     * 视图传值
     * @param $name
     * @param $value
     */
    public function assign($name, $value)
    {
        $this->assign[$name] = $value;
    }


    /**
     * 加载页面
     * @param string $file
     * @throws \Exception
     */
    public function display($file = '')
    {
        /**
         * 1. 判断$file或是否存在 不存在赋值为： 当前/控制器/方法
         * 2. 判断html文件是否存在 不存在就： 返回错误信息
         */

        if (empty($file)) {
            $file = self::$_CTRL . '/' . self::$_ACTION;
        }
        # 获取 template（模板）配置
        $template = conf::get('template', 'conf');
        # 显示文件 （格式 index/index）
        $display_file = str_replace('\\', '/', $file . '.' . $template['view_suffix']);

        if (is_file(self::_VIEW_DIR() . $display_file)) {

            if (is_dir(VENDOR_PATH . $template['view_depr'] . $template['type'])) {
                $loader = new \Twig_Loader_Filesystem(self::_VIEW_DIR());
                $twig = new \Twig_Environment($loader, array(
                    'cache' => self::_VIEW_CACHE_DIR(),
                    'debug' => DEBUG
                ));
                echo $twig->render($display_file, $this->assign ? $this->assign : '');
            } else {
                extract($this->assign);
                self::_include_file(self::_VIEW_DIR() . $display_file);
            }

        } else {
            throw new \Exception('你要访问的文件不存在~！ Html_Path: ' . self::_CACHE_VIEW() . $display_file);
        }
    }

    /**
     * 页面跳转
     * @param string $path
     * @param array $data
     */
    public function load($path='',$data=[])
    {
        if (empty($path)){
            $path = self::$_MODULE.DS.self::$_CTRL.DS.self::$_ACTION;
        }
        if(false !== strpos($path,'?')) { // 解析参数
            $params = explode('?',$path,2);
            $path = $params[0];
            parse_str($params[1],$params);
        }
        $params = isset($params) ? array_merge($params,$data) : $data;
        $load = self::_LOAD_ROUTE($path);
        $vars = array_merge($params,$load[1]);
        $params_str = self::_PARAMS_TO_STR($vars);
        $url = str_replace('\\','/',$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].DS.self::$_WEB_DIR.DS.$load[0].$params_str);
        if (!headers_sent()) {
            header("Location: ".$url);
            exit;
        }
    }

    private function _LOAD_ROUTE($path)
    {
        $load_arr = explode('/',trim($path,'/'));
        if (isset($load_arr[2])){
            $module = $load_arr[0];
            $ctrl = $load_arr[1];
            $action = $load_arr[2];
            unset($load_arr[0],$load_arr[1],$load_arr[2]);
        }elseif(isset($load_arr[1])){
            $module = $load_arr[0];
            $ctrl = $load_arr[1];
            $action = conf::get('default_action','conf');
            unset($load_arr[0],$load_arr[1]);
        }else{
            $module = conf::get('default_module','conf')
                ? conf::get('default_module','conf')
                : conf::get('default_module','conf',true);
            $ctrl = conf::get('default_controller','conf')
                ? conf::get('default_controller','conf')
                : conf::get('default_controller','conf',true);
            $action = $load_arr[0];
            unset($load_arr[0]);
        }
        empty($load_arr) ? array_multisort($load_arr) : [];
        return [$module.DS.$ctrl.DS.$action,$load_arr];
    }

    private function _PARAMS_TO_STR($params)
    {
        static $str;
        foreach ($params as $key => $val){
            if (is_array($params[$key])){
                $str .=self::_PARAMS_TO_STR($val);
            }
            $str .= '/'.$key.'/'.$val;
        }
        return $str;
    }

    private function _VIEW_DIR()
    {
        $template = conf::get('template', 'conf')
            ? conf::get('template', 'conf')
            : conf::get('template', 'conf',true);
        if (empty($template['view_path'])){
            IS_WIN ? $view_dir = ROOT_PATH . DS . APP . DS . self::$_MODULE . APP_VIEW_PATH : APP . DS . self::$_MODULE . APP_VIEW_PATH;
        }else{
            $view_dir = $template['view_path'];
        }
        return str_replace('\\', '/', $view_dir); # 视图文件目录
    }

    private function _VIEW_CACHE_DIR()
    {
        $cache = conf::get('cache', 'conf')
            ? conf::get('cache', 'conf')
            : conf::get('cache', 'conf',true);
        if (empty($cache['path'])){
           IS_WIN ? $view_cache_dir = ROOT_PATH.DS .RUNTIME_PATH . CACHE_PATH : RUNTIME_PATH . CACHE_PATH ;
        }else{
            $view_cache_dir = $cache['path'];
        }
        is_dir($view_cache_dir) ? '' : mkdir($view_cache_dir, '0777', true);
        return str_replace('\\', '/', $view_cache_dir); # HTML缓存目录
    }

    static private function _include_file($file)
    {
        return include str_replace('/','\\',$file);
    }
}