<?php

namespace app\home\controller;

class Contact extends Common {
    public function _initialize() {
        parent::_initialize();
    }

    public function index(){
        $comment = model('Msg')->getCommentList("id,username,inputtime,pid,url,content,path,concat(path,'-',id) as bpath");

        $this->assign('commentList', $comment['list']);
        $this->assign('page', $comment['list']->render());

        return $this->fetch();
    }

    public function add(){
        return $this->addData('Msg');
    }

}
