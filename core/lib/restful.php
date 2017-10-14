<?php
/**
 * Created by PhpStorm.
 * User: sphpe
 * Date: 2017/10/11
 * Time: 22:31
 */

namespace core\lib;


abstract class restful
{
    /**
     * 请求方法
     * @var requestMethod
     */
    private $_requestMethod;

    /**
     * 请求资源名称
     * @var resourceName
     */
    private $_resourceName;

    /**
     * 允许请求资源列表
     * @var array
     */
    private $_allowResources = [];

    private $_data = [];

    /**
     * 允许请求资源方法
     * @var array
     */
     private $_allowRequestMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];

    /**
     * 常用HTTP状态码
     * @var array
     */
    private $_statusCode = [
        200 => 'OK',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unathorized',
        403 => 'ForBidden',
        404 => 'No Found',
        405 => 'Method Not Allowed',
        500 => 'Server Internal Error'
    ];

    /**
     * 初始化请求方法
     */
    private function _setupResourcesMethods()
    {
        $this->_requestMethod = $_SERVER['REQUEST_METHOD'];
        if (!in_array($this->_requestMethod, $this->_allowRequestMethods)) {
            throw new \Exception("请求方法不被允许！", 405);
        }
    }

    /**
     * 初始化请求资源
     */
    private function _setupResources()
    {
        $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        if (strpos($path,'?') === false and !empty($path)){
            $params = explode('/',$path);
            $this->_resourceName = $params[2];

            if (!empty($data) and is_array($data)){
                ###  获取URL参数
                $count = count($data) + 1;
                $i = 0;
                while ($i < $count){
                    if (isset($data[$i + 1])){
                        $_GET[$data[$i]] = $data[$i + 1];
                    }
                    $i = $i + 2;
                }
            }
        }else{
            throw new \Exception("请求资源不被允许！", 400);
        }

        self::_validate_resources();


    }
    private function _validate_resources()
    {
        if(!empty($this->_allowResources)){
            if (!in_array($this->_resourceName,$this->_allowResources)){
                throw new \Exception("请求资源不被允许！", 400);
            }
        }
    }

    /**
     * 获取请求参数 （除GET）
     * @return mixed
     * @throws \Exception
     */
    private function getBodyParams()
    {
        $raw = file_get_contents('php://input');
        if (empty($raw)){
            throw new \Exception("请求参数错误~！",400);
        }
        return json_decode($raw,true);
    }

    /**
     * 输出JSON
     * @param $array
     */
    protected function _json($array, $code = 0)
    {
        if ($code > 0 and $code != 200 and $code != 204) {
            header("HTTP/1.1 ".$code." ".$this->_statusCode[$code]);
        }
        header('Content-Type:application/json;charset=utf-8');
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function __construct()
    {
        try {
            $this->_setupResourcesMethods();
            $this->_setupResources();
        } catch (\Exception $e) {
            $this->_json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    protected function _set_allow_resources($resurces)
    {
        $this->_allowResources = $resurces;
    }

    protected function _set_request_methods($methods)
    {
        $this->_allowRequestMethods = $methods;
    }

    protected function _set_status_code($code)
    {
        $this->_statusCode = $code;
    }

    protected function _get_data()
    {
        return self::getBodyParams() ? self::getBodyParams() : $this->_data;
    }

}