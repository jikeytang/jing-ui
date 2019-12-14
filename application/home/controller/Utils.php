<?php

namespace app\home\controller;

use think\captcha\src\Captcha;

class Utils extends Common {

    public function verify($id = '') {
        $config = [
            // 验证码字体大小
            'fontSize'    =>    20,
            // 验证码位数
            'length'      =>    4,
            // 关闭验证码杂点
            'useNoise'    =>    true,
            // 验证成功后是否重置        
            'reset'    => true
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

}

