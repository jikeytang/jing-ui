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

function testConsole () {
    dump('test');
}

// 应用公共文件
function check_login($id) {
    $user = db('user')->where('id', $id)->find();
    $key = 'auth_key';
//    dump($id);
    dump(session($key));

    if (session($key) === $id) {
        return true;
    } else {
        return false;
    }
}
