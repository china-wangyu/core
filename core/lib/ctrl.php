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
    private static $_MODULE;
    private static $_CTRL;
    private static $_ACTION;
    public $assign = array();

    public function __construct()
    {
        $route = new \core\lib\route();
        self::$_MODULE = $route->_GET_MODULE();
        self::$_CTRL = $route->_GET_CTRL();
        self::$_ACTION = $route->_GET_ACTION();
        common::_RUN(self::$_MODULE);
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

    private function _VIEW_DIR()
    {
        $template = conf::get('template', 'conf')
            ? conf::get('template', 'conf')
            : conf::get('template', 'conf',true);
        empty($template['view_path']) ? $view_dir = ROOT_PATH . DS . APP . DS . self::$_MODULE . APP_VIEW_PATH
            : $view_dir = $template['view_path'];
        return str_replace('\\', '/', $view_dir); # 视图文件目录
    }

    private function _VIEW_CACHE_DIR()
    {
        $cache = conf::get('cache', 'conf')
            ? conf::get('cache', 'conf')
            : conf::get('cache', 'conf',true);
        empty($cache['path']) ? $view_cache_dir = RUNTIME_PATH . CACHE_PATH
            : $view_cache_dir = $cache['path'];
        is_dir($view_cache_dir) ? '' : mkdir($view_cache_dir, '0777', true);
        return str_replace('\\', '/', $view_cache_dir); # HTML缓存目录
    }

    static private function _include_file($file)
    {
        return include str_replace('/','\\',$file);
    }
}