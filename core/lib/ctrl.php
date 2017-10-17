<?php
/**
 *  +----------------------------------------------------------------------
 *  | 控制器 ctrl.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */

namespace core\lib;

use core\lib\drive\dir\file;

class ctrl
{
    public $assign = array();

    public function __construct()
    {
        common::_RUN(route::module());
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
            $file = route::ctrl() . '/' . route::action();
        }

        # 获取 template（模板）配置
        $template = conf::get('template', 'conf');
        $dir_arr = file::_include_view_file($file,$template);

        if (is_dir(VENDOR_PATH . $template['type'])) {
            $loader = new \Twig_Loader_Filesystem($dir_arr['dir']);
            $twig = new \Twig_Environment($loader, array(
                'cache' => self::_VIEW_CACHE_DIR(),
                'debug' => DEBUG
            ));
            echo $twig->render($dir_arr['filename'], $this->assign ? $this->assign : '');
        } else {
            extract($this->assign);
            self::_include_file($dir_arr['dir'] . $dir_arr['filename']);
        }
    }


    private function _VIEW_CACHE_DIR()
    {
        $cache = conf::get('cache', 'conf');
        if (empty($cache['path'])) {
            IS_WIN ? $view_cache_dir = ROOT_PATH . DS . RUNTIME_PATH . CACHE_PATH : RUNTIME_PATH . CACHE_PATH;
        } else {
            $view_cache_dir = RUNTIME_PATH.$cache['path'];
        }
        is_dir($view_cache_dir) ? '' : mkdir($view_cache_dir, '0777', true);
        return str_replace('\\', '/', $view_cache_dir); # HTML缓存目录
    }

    /**
     * 页面跳转
     * @param string $path
     * @param array $data
     */
    public function load($path = '', $data = [])
    {
        if (empty($path)) {
            $path = route::module() . DS . route::ctrl() . DS . route::action();
        }
        if (false !== strpos($path, '?')) { // 解析参数
            $params = explode('?', $path, 2);
            $path = $params[0];
            parse_str($params[1], $params);
        }
        $params = isset($params) ? array_merge($params, $data) : $data;
        $load = self::_LOAD_ROUTE($path);
        $vars = array_merge($params, $load[1]);
        $params_str = self::_PARAMS_TO_STR($vars);
        $url = str_replace('\\', '/', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . DS . route::website_name() . DS . $load[0] . $params_str);
        if (!headers_sent()) {
            header("Location: " . $url);
            exit;
        }
    }

    private function _LOAD_ROUTE($path)
    {
        $load_arr = explode('/', trim($path, '/'));
        if (isset($load_arr[2])) {
            $module = $load_arr[0];
            $ctrl = $load_arr[1];
            $action = $load_arr[2];
            unset($load_arr[0], $load_arr[1], $load_arr[2]);
        } elseif (isset($load_arr[1])) {
            $module = $load_arr[0];
            $ctrl = $load_arr[1];
            $action = conf::get('default_action', 'conf');
            unset($load_arr[0], $load_arr[1]);
        } else {
            $module = conf::get('default_module', 'conf');
            $ctrl = conf::get('default_controller', 'conf');
            $action = $load_arr[0];
            unset($load_arr[0]);
        }
        empty($load_arr) ? array_multisort($load_arr) : [];
        return [$module . DS . $ctrl . DS . $action, $load_arr];
    }

    private function _PARAMS_TO_STR($params)
    {
        static $str;
        foreach ($params as $key => $val) {
            if (is_array($params[$key])) {
                $str .= self::_PARAMS_TO_STR($val);
            }
            $str .= '/' . $key . '/' . $val;
        }
        return $str;
    }

    static private function _include_file($file)
    {
        return include str_replace('/', '\\', $file);
    }
}