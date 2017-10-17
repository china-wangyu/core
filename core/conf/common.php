<?php
/**
 *  +----------------------------------------------------------------------
 *  | 应用函数 common.php
 *  | Auth: china-wangyu@aliyun.com
 *  +----------------------------------------------------------------------
 */


function _dump($arg){
    if (is_null($arg)){
        var_dump($arg);
    }
    if (is_bool($arg)){
        var_dump($arg);
    }
    echo '<pre style="line-height: 2rem;padding: 1rem auto;font-family:黑体;font-size: 1rem;border-top:3px solid #6d6d6d;border-bottom:3px solid #6d6d6d;border-radius:8px;white-space:pre-wrap;word-wrap:break-word;letter-spacing:1.5px;">';
    print_r($arg);
    echo '<pre/>';
}