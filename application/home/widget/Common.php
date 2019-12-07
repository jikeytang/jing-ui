<?php

namespace app\home\widget;

use think\Controller;

class Common extends Controller {
    public function nav() {
        $data['nav'] = model('Columns')->menu();
        return $this->fetch('widget/Nav', $data);
    }

    public function link() {
        $this->assign('link', model('Link')->getLinks());
        return $this->fetch('widget/Link');
    }

    public function date(){
        $data['list'] = model('Blog')->getDateList('Blog');

        return $this->fetch('widget/Date', $data);
    }

    public function blogSort(){
        $data['bloglist'] = model('Columns')->getCatList('Blog', 4);
        return $this->fetch('widget/BlogSort', $data);
    }
}