<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function check_login($name) {
    $user = db('user')->where('username', $name)->find();
    $key = 'username';

    if (session($key) && session($key) === $user['username']) {
        return true;
    } else {
        header('location: ' . url('/Base/login'));
        exit;
    }
}
