# core

core 是一个快速，简单，可扩展的微型 PHP 框架，允许用户快速的构建 RESTful web 应用，同样易于学习和使用，简单但是很强大！


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

# 应用入口文件
        
        -> index.php
        
        define('APP',    'app');    # 应用名称
        define('DEBUG', True);      # 是否开启报错
        
# 应用配置文件
        
        -> conf.php     # 基本配置
        -> db.php       # 数据库配置
        -> common.php   # 自定义函数
        