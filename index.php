<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

if (!defined('__PUBLIC__')) {
    $_public = rtrim(dirname(rtrim($_SERVER['SCRIPT_NAME'], '/')), '/');
//    define('__PUBLIC__', (('/' == $_public || '\\' == $_public) ? '/public/' : $_public));
    define('__PUBLIC__', $_public . '/public');
}

define('__ROOT__', $_public);
define('__GROUP__', $_public . '/admin');

// 定义应用目录
//define('APP_PATH', __DIR__ . '/application/');

// 定义应用缓存目录
//define('RUNTIME_PATH', __DIR__ . '/runtime/');

// 开启调试模式
//define('APP_DEBUG', true);

// 加载基础文件
require __DIR__ . '/thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->bind('home')->run()->send();
