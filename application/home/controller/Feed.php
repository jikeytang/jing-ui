<?php

namespace app\home\controller;

use core\utils\RSS;

class Feed extends Common {
    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $blog = model('blog')->order('id desc')->limit(20)->select();
        $rss = new RSS(config('site.title'), '', config('site.keywords'), '');

        foreach($blog as $list){
            $rss->AddItem($list['title'], url('/blog/' . $list['id']), $list['description'], $list['ctime']);
        }

        $rss->display();
    }

}
