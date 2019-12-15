<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');

Route::get('captcha/[:id]', "\\think\\captcha\\CaptchaController@index");

return [
    '__pattern__'                        => [
        'name'  => '\w+',
        'id'    => '\d+',
        'catId' => '\d+',
    ],
    '[hello]'                            => [
        ':id'   => ['home/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['home/hello', ['method' => 'post']],
    ],
    'blog/:id'                           => 'blog/read',         // 博客详细
    'blog/read/:id'                      => 'blog/read',         // 博客详细
    'cat/:catid'                         => 'cat/index',         // 博客分类
    'read/:id'                           => 'read/read',         // 博客详细
    'date/:t'                            => 'date/index',        // 文章归档
    'design/:id'                         => 'design/detail',     // 作品详细
    'design/designlist/:id/:title'       => 'design/designlist', // 作品列表
    'design/designlist/:id/:title/:desc' => 'design/designlist', // 作品列表
    'contact/add'                        => 'contact/add',       // 联系我们
];
