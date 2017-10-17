<?php
/**
 *  +----------------------------------------------------------------------
 *  | 创建目录 mkdir.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */
namespace core\lib\drive\dir;

use core\lib\conf;
use core\lib\drive\error\error;

class mkdir
{

    /**
     * 创建目录
     * @param $dir
     * @throws \Exception
     */
    public static function _create($dir)
    {
        $dir =  dir::_dir($dir);
        if (!is_dir($dir)){
            try{
                mkdir($dir,'0755',true);
            }catch (\Exception $e){
                new error('目录权限不够，创建目录失败~！',403);
            }
        }else{
            new error('目录已存在，创建目录失败~！',500);
        }
    }

    /**
     * 自动生成
     * 1. 创建APP目录
     * 2. 创建APP default_module(默认模块)
     * 3. 创建CTRL（控制器）目录
     * 4. 创建VIEW（视图）目录
     * 5. 创建MODLE（模型）目录
     * 6. 创建CORE框架默认控制器（index）and 创建ACTION默认方法（index）
     */
    public static function _auto_generation()
    {
        self::_create_app();
        self::_create_conf();
        self::_create_db();
        self::_create_common();
        self::_create_module();
        self::_create_ctrl();
        self::_create_model();
        self::_create_view();
        self::_create_default_ctrl();
    }


    private static function _create_app()
    {
        self::_create(APP);
    }

    private static function _create_conf()
    {
        $confPath = CORE_PATH.'conf/conf'.EXT;
        $appPath = APP.DS.'conf'.EXT;
        $data = file_get_contents($confPath,true);
        file_put_contents($appPath,$data);
    }

    private static function _create_common()
    {
        $confPath = CORE_PATH.'conf/common'.EXT;
        $appPath = APP.DS.'common'.EXT;
        $data = file_get_contents($confPath,true);
        file_put_contents($appPath,$data);
    }

    private static function _create_db()
    {
        $confPath = CORE_PATH.'conf/db'.EXT;
        $appPath = APP.DS.'db'.EXT;
        $data = file_get_contents($confPath,true);
        file_put_contents($appPath,$data);
    }

    private static function _create_module()
    {
        $default_name = conf::get('default_module','conf',true);
        self::_create(APP.DS.$default_name);
    }

    private static function _create_ctrl()
    {
        $default_name = conf::get('default_module','conf',true);
        self::_create(APP.DS.$default_name.DS.'ctrl');
    }

    private static function _create_view()
    {
        $default_name = conf::get('default_module','conf',true);
        self::_create(APP.DS.$default_name.DS.'view');
    }

    private static function _create_model()
    {
        $default_name = conf::get('default_module','conf',true);
        self::_create(APP.DS.$default_name.DS.'model');
    }

    public static function _create_default_ctrl()
    {
        $default_name = conf::get('default_module','conf',true);
        $ctrlPath = APP.DS.$default_name.DS.'ctrl'.DS.$default_name.EXT;
        $data = '<?php'
                    .PHP_EOL.'namespace '.trim(str_replace('/','\\',DS.APP.DS.$default_name.DS.'ctrl'),'\\').';'
                    .PHP_EOL.'use '.ltrim(str_replace('/','\\',CORE_LIB_PATH),'\\').'ctrl;'
                    .PHP_EOL.'class '.$default_name.' extends ctrl'
                    .PHP_EOL.'{'
                    .PHP_EOL.'  public function index()'
                    .PHP_EOL.'  {'
                    .PHP_EOL.'      echo \'<div style="margin-top:20%;line-height: 4rem;padding: 2rem auto;font-family:黑体;
                                        font-size: 1.5rem;border-top:3px solid #6d6d6d;border-bottom:3px solid #6d6d6d;
                                        border-radius:8px;white-space:pre-wrap;word-wrap:break-word;
                                        letter-spacing:1.5px;text-align:center;">欢迎使用 CORE<small>'.CORE_VERSION.'</small> 框架</div>\'; '
                    .PHP_EOL.'  }'
                    .PHP_EOL.'}';
        file_put_contents($ctrlPath,$data);
    }


}