# core

core 是一个快速，简单，可扩展的微型 PHP 框架，允许用户快速的构建 RESTful web 应用，同样易于学习和使用，简单但是很强大！

        CORE_VERSION : 1.0          # 框架版本

# 框架目录：

初始的目录结构如下：

            www WEB部署目录（或者子目录）
            |——APP                      应用目录
            |      |—— module_name      模块目录      
            |      |   |—— ctrl         控制器目录
            |      |   |—— model        模型目录
            |      |   |—— view         视图目录
            |      |   |—— common.php   模块函数文件
            |
            |      |—— common.php       公共函数文件
            |      |—— conf.php         公共配置文件
            |      |—— db.php           数据库配置文件
            |
            |——core                     框架系统目录
            |      |—— conf             框架配置目录
            |      |—— lib              框架类库目录
            |           - drive           框架驱动目录
            |
            |——runtime                  应用的运行时目录
            |       |—— cache           应用的运行时缓存目录（可写，可定制）
            |       |—— log             应用的运行时日志目录（可写，可定制）
            |
            |——vendor                   第三方类库目录（Composer依赖库）
            |——.htaccess                用于apache的重写
            |——composer.json            composer 定义文件
            |——composer.lock            composer 文件
            |——index.php                入口文件

# 应用入口文件 index.php

        
        define('APP',    'app');    # 应用名称
        define('DEBUG', True);      # 是否开启报错
        
        require_once './core/start.php';  # 加载框架系统运行文件
        
### 如需新建web应用，只需要新建一个文件入口文件，配置上面常量和引入系统运行文件就OK，
### 新建的web应用系统会自动创建相关目录和文件。
        
        
# 应用配置文件 conf.php
        
           // 应用调试模式
          'app_debug'              => true,
      
          // 控制器类后缀
          'controller_suffix'      => false,
      
          // 模型库后缀
          'model_suffix'           => false,
      
          // 操作方法后缀
          'action_suffix'          => '',
      
          // 应用类库后缀
          'class_suffix'           => false,
      
      
          // +----------------------------------------------------------------------
          // | 模块设置
          // +----------------------------------------------------------------------
      
          // 默认模块名
          'default_module'         => 'index',
          // 禁止访问模块
          'deny_module_list'       => ['common'],
          // 默认控制器名
          'default_controller'     => 'Index',
          // 默认操作名
          'default_action'         => 'index',
          // 默认验证器
          'default_validate'       => '',
          // 默认的空控制器名
          'empty_controller'       => 'Error',
      
          // 自动搜索控制器
          'controller_auto_search' => false,
      
          'template'               => [
              // 模板引擎类型 支持 php twig 支持扩展
              'type'         => 'twig',
              // 模板路径
              'view_path'    => '',
              // 模板后缀
              'view_suffix'  => 'html',
              // 模板文件名分隔符
              'view_depr'    => DS,
              // 模板引擎普通标签开始标记
              'tpl_begin'    => '{',
              // 模板引擎普通标签结束标记
              'tpl_end'      => '}',
              // 标签库标签开始标记
              'taglib_begin' => '{',
              // 标签库标签结束标记
              'taglib_end'   => '}',
          ],
      
          'cache'                  => [
              // 驱动方式
              'type'   => 'File',
              // 缓存保存目录
              'path'   => CACHE_PATH,
              // 缓存前缀
              'prefix' => '',
              // 缓存有效期 0表示永久缓存
              'expire' => 0,
          ],
      
          'log'                    => [
              // 日志记录方式，内置 file socket 支持扩展
              'type'  => 'File',
              // 日志保存目录
              'path'  => LOG_PATH,
              // 日志记录级别
              'level' => [],
          ],
        
### 配置应用相关内容，不做详细叙述