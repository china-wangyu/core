<?php
/**
 *  +----------------------------------------------------------------------
 *  | 文件类 file.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */
namespace core\lib\drive\dir;
use core\lib\conf;
use core\lib\drive\error\error;
use core\lib\route;

class file
{
    private static $_ctrl = 'ctrl';
    private static $_view = 'view';

    public static function _create($file,$data)
    {
        file_put_contents($file,$data);
    }



    public static function _validate_file($file)
    {
        if (is_file($file) or file_exists($file)){
            return true;
        }
        return false;
    }

    public static function _include_ctrl_file()
    {
        $dir = APP.DS.route::module().DS.self::$_ctrl.DS;
        $ctrlName = route::ctrl();
        $data = self::_validate_ctrl($dir,$ctrlName);
        $ctrlClass = $data['class'].$ctrlName;
        self::_include($data['file']);
        $action = route::action();
        $ctrl =  new $ctrlClass();
        $ctrl->$action();
    }

    public static function _include_view_file($filename,$template=[])
    {
        if (empty($template)){
            # 获取 template（模板）配置
            $template = conf::get('template', 'conf');
        }

        $dir = APP.DS.route::module().DS.self::$_view.DS;
        $dir = dir::_dir($dir);
        if (is_file($dir.$filename.'.' . $template['view_suffix'])){
            return ['dir'=>$dir,'filename'=>$filename.'.' . $template['view_suffix']];
        }elseif(!empty($template['view_path'])){
            $dir = dir::_dir($template['view_path']);
            return ['dir'=>$dir,'filename'=>$filename.'.' . $template['view_suffix']];
        }else{
            new error('视图文件不存在~！'.$dir.$filename.'.' . $template['view_suffix'],404);
        }
    }


    public static function _include_class($file)
    {
        $file = str_replace('/','\\',$file);
        return include $file;
    }

    public static function _include($file)
    {
        $file = str_replace('\\','/',$file);
        return include $file;
    }

    private static function _validate_ctrl($dir,$filename,$ext=EXT)
    {
        $_dir = dir::_dir($dir);
        if (self::_validate_file($dir.ucfirst($filename).$ext)){
            $filename = ucfirst($filename);
            return ['file'=>$_dir.$filename.$ext,'class'=>str_replace('/','\\',$dir)];
        }elseif(self::_validate_file($dir.lcfirst($filename).$ext)){
            $filename = lcfirst($filename);
            return ['file'=>$_dir.$filename.$ext,'class'=>str_replace('/','\\',$dir)];
        }else{
            new error('控制器不存在~！',404);
        }
    }
}