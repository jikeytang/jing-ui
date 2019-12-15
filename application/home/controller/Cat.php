<?php

namespace app\home\controller;

use think\Controller;

class Cat extends Common {
    public function index($catid) {
        $dateList = $this->getListInfo('Blog', array('catid' => $catid));
        $this->assign('list', $dateList['list']);
        $this->assign('page', $dateList['page']);
        return $this->fetch();
    }
}