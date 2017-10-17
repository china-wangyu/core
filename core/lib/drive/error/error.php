<?php
/**
 * Created by PhpStorm.
 * User: china-wangyu@aliyun.com
 * Date: 2017/10/10
 */

namespace core\lib\drive\error;
class error
{
    private static $_msg = 'Fatal errors inside the core framework';
    private static $_code = '500';
    private static $_error_type = ['text','json'];
    private static $_json_code = [
        200 => 'OK',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unathorized',
        403 => 'ForBidden',
        404 => 'No Found',
        405 => 'Method Not Allowed',
        500 => 'Server Internal Error'
    ];
    public function __construct($msg,$code='500', $type='text')
    {
        self::_validate_error_type($type);
        self::$type($msg ? $msg : self::$_msg,$code ? $code : self::$_code);
    }

    private static function _validate_error_type($type)
    {
        if (!in_array($type,self::$_error_type)){
            self::text('没有此错误类型~！',403);
        }
    }

    protected static function text($msg, $code)
    {
        throw new \Exception(self::$_json_code[$code].' '.$msg,$code);
    }

    protected static function json($msg, $code)
    {
        if ($code > 0 and $code != 200 and $code != 204) {
            header("HTTP/1.1 ".$code." ".self::$_json_code[$code]);
        }
        header('Content-Type:application/json;charset=utf-8');
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        exit();
    }
}