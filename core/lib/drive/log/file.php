<?php
/**
 * Created by PhpStorm.
 * User: china-wangyu@aliyun.com
 * Date: 2017/10/10
 */
namespace core\lib\drive\log;

class file
{
    public $path;

    /**
     * file constructor. 初始化操作
     */
    public function __construct()
    {
        $this->path = IS_WIN ? ROOT_PATH.DS.RUNTIME_PATH.LOG_PATH : RUNTIME_PATH.LOG_PATH;
    }

    /**
     * 生成系统日志文件
     * @param $msg
     * @param string $filePath
     * @return bool|int
     * @throws \Exception
     */
    public function log($msg,$filePath='')
    {
        /**
         * 1. 确定日志存储目录
         * 新建目录
         * 2. 写入日志
         */

        date_default_timezone_set('PRC');
        if (empty($filePath)){
            $filePath = $this->path.date('YmdH').'/log'.EXT;
        }elseif (strrpos($filePath,'.') == false){
            throw new \Exception('生成日志文件路径不正确~！'.$filePath);
        }

        $path_dir_count = strripos($filePath,'/');
        $path = str_replace('\\','/',substr($filePath,0,$path_dir_count));
        if(!is_dir($path)){
            mkdir($path,'0755',true);
        }

        return file_put_contents($filePath,
            date('Y-m-d H:i:s').'   '.json_encode($msg).PHP_EOL,FILE_APPEND);
    }

}