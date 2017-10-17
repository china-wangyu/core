<?php
/**
 *  +----------------------------------------------------------------------
 *  | 基础加载文件 mkdir.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */
define('CORE_VERSION', '1.0');  # 框架版本号 1.0
define('CORE_START_TIME', microtime(true)); # 框架运行微妙数
define('CORE_START_MEM', memory_get_usage());   # 返回分配给 PHP 的内存量

define('EXT', '.php');  # 脚本文件后缀名
define('DEFAULT_NAME', 'index');  # 默认微模块 或 文件
define('DS', DIRECTORY_SEPARATOR); # 目录 '/ '

defined('ROOT_PATH') or define('ROOT_PATH', dirname($_SERVER['SCRIPT_FILENAME']));     # 根目录
defined('VENDOR_PATH') or define('VENDOR_PATH', 'vendor' . DS); # 第三方库
defined('CORE_PATH') or define('CORE_PATH', 'core'.DS);  # 框架配置目录
define('CORE_LIB_PATH', CORE_PATH . 'lib' . DS);                # 依赖目录
define('CORE_DRIVE_PATH', CORE_LIB_PATH . 'drive' . DS);        # 系统驱动目录

defined('RUNTIME_PATH') or define('RUNTIME_PATH', 'runtime' . DS); # 运行缓存目录
defined('LOG_PATH') or define('LOG_PATH',  'log' . DS); # 系统日志目录
defined('CACHE_PATH') or define('CACHE_PATH', 'cache' . DS); # 缓存文件目录
defined('TEMP_PATH') or define('TEMP_PATH',  'temp' . DS); # 临时文件目录

// 环境常量
define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);


include VENDOR_PATH.'/autoload'.EXT;
if (DEBUG === True) {
    if(is_dir(VENDOR_PATH.'/filp/')){
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
    ini_set('display_errors', 'on');
} else {
    ini_set('display_errors', 'off');
}
include './'.CORE_LIB_PATH.'/loader'.EXT;





