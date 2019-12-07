<?php

namespace app\home\taglib;

use think\template\TagLib;

class Widget extends TagLib {
    /**
     * 定义标签列表
     */
    protected $tags = [
        'topnav'  => ['attr' => 'name', 'close' => 0],
        'open'  => ['attr' => 'name,type', 'close' => 0],
    ];

    public function tagTopnav($tag, $content) {
        $html = '<li><a class="home" href="__ROOT__/">Home</a></li>';
        $html .= '<li><a class="design" href="__ROOT__/design">Design</a></li>';
        $html .= '<li><a class="about" href="__ROOT__/about">About</a></li>';
        $html .= '<li><a class="contact" href="__ROOT__/contact">Contact</a></li>';
        $html .= '<li><a class="blog" href="__ROOT__/blog">Blog</a></li>';
        return $html;
    }
}