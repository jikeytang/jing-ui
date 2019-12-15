<?php

namespace app\home\taglib;

use think\template\TagLib;

class Demo extends TagLib {
    /**
     * 定义标签列表
     */
    protected $tags = [
        'jquery'=>['attr'=>'','close'=>0],
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'close' => ['attr' => 'time,format', 'close' => 1], //闭合标签，默认为不闭合
        'open'  => ['attr' => 'name,type', 'close' => 0],

    ];

    //引入jquery
    public function tagJquery(){
        return '<script src="__PUBLIC__/static/js/jquery-2.0.0.min.js"></script>';
    }

    /**
     * 这是一个闭合标签的简单演示
     */
    public function tagClose($tag, $content) {
        $format = empty($tag['format']) ? 'Y-m-d H:i:s' : $tag['format'];
        $time   = empty($tag['time']) ? time() : $tag['time'];
        $parse  = '<?php ';
        $parse  .= 'echo date("' . $format . '",' . $time . ');';
        $parse  .= ' ?>';
        return $parse;
    }

    /**
     * 这是一个非闭合标签的简单演示
     */
    public function tagOpen($tag, $content) {
        $name = $tag['name'];
        return "<h1>22123 $name</h1>";
    }

}