<?php

namespace app\home\controller;

use app\home\model\Advs;

class Index extends Common {
    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
//        dump($Think);
        $this->assign('advs', Advs::advs());
        return $this->fetch();
    }
}