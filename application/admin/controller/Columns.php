<?php

namespace app\admin\controller;

use think\Controller;

class Columns extends Common {
    public function index() {
        $newscat = model('Columns')->sortList('Design', 1);
        $this->assign('catArray', $newscat);
        return $this->fetch();
    }

    public function blogindex() {
        $blogcat = model('Columns')->sortList('Blog', 4);
        $this->assign('catArray', $blogcat);

        return $this->fetch();
    }

    public function insert() {
        $this->getInsert();
        return $this->fetch();
    }

    public function bloginsert() {
        return $this->fetch();
    }

    public function add() {
        $data['colPid'] = $_POST['colPid'];
        $data['model'] = $_POST['model'];
        $data['modelid'] = $_POST['modelid'];
        $data['colPath'] = $_POST['colPath'];
        $data['colTitle'] = $_POST['colTitle'];
        $data['description'] = $_POST['description'];
        $this->addData($data);
    }

    public function blogadd(){
        $data['colPid'] = $_POST['colPid'];
        $data['model'] = $_POST['model'];
        $data['modelid'] = $_POST['modelid'];
        $data['colPath'] = $_POST['colPath'];
        $data['colTitle'] = $_POST['colTitle'];
        $data['description'] = $_POST['description'];
        $this->addData($data);
    }

    public function edit() {
        $vo = model('Columns')->where(array('colId' => $_GET['id']))->find();
        $this->assign('vo', $vo);
        return $this->fetch();
    }

    public function modify() {
        $data['colId'] = $_POST['colId'];
        $data['colTitle'] = $_POST['colTitle'];
        $data['description'] = $_POST['description'];
        $this->getModify($data);
    }

    public function del() {
        $id = $_GET['id'];
        $id = explode(' ', $id);
        $this->remove($id);
    }

    public function act() {
        $this->action();
    }
        
    public function modelList(){
        return db('Columns')->Distinct(true)->field('model, modelid')->select();
    }

}