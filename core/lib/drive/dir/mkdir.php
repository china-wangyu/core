<?php
/**
 * Created by PhpStorm.
 * User: china-wangyu@aliyun.com
 * Date: 2017/10/12
 * Time: 22:01
 */
namespace core\lib\drive\dir;
use core\lib\conf;

class mkdir
{

    /**
     * 创建目录
     * @param $dir
     * @throws \Exception
     */
    public static function create_dir($dir)
    {

        if (!is_dir($dir)){
            mkdir($dir,'0777',true);
        }else{
            throw new \Exception("FILE_EXISTS 目录已存在~！DIR: ".$dir);
        }
    }

    /**
     * 1. 创建APP目录
     * 2. 创建APP default_module(默认模块)
     * 3. 创建CTRL（控制器）目录
     * 4. 创建VIEW（视图）目录
     * 5. 创建MODLE（模型）目录
     * 6. 创建CORE框架默认控制器（index）and 创建ACTION默认方法（index）
     */
    public static function _app()
    {
        self::_create_app();
        self::_create_conf();
        self::_create_db();
        self::_create_module();
        self::_create_ctrl();
        self::_create_model();
        self::_create_view();
        self::_create_default_ctrl();
    }


    private static function _create_app()
    {
        self::create_dir(ROOT_PATH.DS.APP);
    }

    private static function _create_conf()
    {
        $confPath = ROOT_PATH.DS.CORE_PATH.'conf/conf'.EXT;
        $appPath = ROOT_PATH.DS.APP.DS.'conf'.EXT;
        $data = file_get_contents($confPath,true);
        file_put_contents($appPath,$data);
    }

    private static function _create_db()
    {
        $confPath = ROOT_PATH.DS.CORE_PATH.'conf/db'.EXT;
        $appPath = ROOT_PATH.DS.APP.DS.'db'.EXT;
        $data = file_get_contents($confPath,true);
        file_put_contents($appPath,$data);
    }

    private static function _create_module()
    {
        self::create_dir(ROOT_PATH.DS.APP.DS.DEFAULT_NAME);
    }

    private static function _create_ctrl()
    {
        self::create_dir(ROOT_PATH.DS.APP.DS.DEFAULT_NAME.APP_CTRL_PATH.APP_CTRL_PATH);
    }

    private static function _create_view()
    {
        self::create_dir(ROOT_PATH.DS.APP.DS.DEFAULT_NAME.APP_VIEW_PATH);
    }

    private static function _create_model()
    {
        self::create_dir(ROOT_PATH.DS.APP.DS.DEFAULT_NAME.APP_MODEL_PATH);
    }

    public static function _create_default_ctrl()
    {
        $ctrlPath = ROOT_PATH.DS.APP.DS.DEFAULT_NAME.APP_CTRL_PATH.DEFAULT_NAME.EXT;
        $data = '<?php'
                    .PHP_EOL.'namespace '.trim(str_replace('/','\\',DS.APP.DS.DEFAULT_NAME.APP_CTRL_PATH),'\\').';'
                    .PHP_EOL.'use '.ltrim(str_replace('/','\\',CORE_LIB_PATH),'\\').'ctrl;'
                    .PHP_EOL.'class '.DEFAULT_NAME.' extends ctrl'
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