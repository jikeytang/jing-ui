<?php

namespace app\home\controller;

use think\db\Where;

class Date extends Common{
    public function index($t) {
        $map[] = ['ctime', 'like', $t . '%'];
        $dateList = $this->getListInfo('Blog', $map);
        $this->assign('list', $dateList['list']);
        $this->assign('page', $dateList['page']);
        return $this->fetch();
    }

}