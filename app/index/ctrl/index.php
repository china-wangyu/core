<?php
namespace app\index\ctrl;
use core\lib\ctrl;
class index extends ctrl
{
  public function index()
  {

      echo '<div style="margin-top:20%;line-height: 4rem;padding: 2rem auto;font-family:黑体;font-size: 1.5rem;
                border-top:3px solid #6d6d6d;border-bottom:3px solid #6d6d6d;border-radius:8px;
                white-space:pre-wrap;word-wrap:break-word;letter-spacing:1.5px;
                text-align:center;">欢迎使用 CORE<small>'.CORE_VERSION.'</small> 框架</div>';
  }
}