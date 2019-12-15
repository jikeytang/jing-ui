<?php

namespace app\admin\controller;

use think\Controller;

class Blog extends Common {
    public function index() {
        return $this->lists();
    }

    public function insert() {
        $this->getInsert();
        return $this->fetch();
    }

    public function add() {
        $data['catid'] = $_POST['catid'];
        $data['title'] = $_POST['title'];
        $data['author'] = $_POST['author'];
        $data['content'] = stripslashes($_POST['content']);
        $data['description'] = $_POST['description'];
        $data['inputtime'] = time();
        $data['ctime'] = date('Y-m-d', time());
        $this->addData($data);
    }

    public function edit() {
        $id = $_REQUEST['id'];
        $this->getEdit($id);
        return $this->fetch();
    }

    public function modify() {
        $data['id'] = $_POST['id'];
        $data['catid'] = $_POST['catid'];
        $data['title'] = $_POST['title'];
        $data['author'] = $_POST['author'];
        $data['content'] = stripslashes($_POST['content']); // 删除由 addslashes() 函数添加的反斜杠
        $data['description'] = $_POST['description'];
        $data['inputtime'] = time();
        $data['ctime'] = date('Y-m-d', time());
        $this->getModify($data);
    }

    public function del() {
        $id = $_GET['id'];
        $id = explode(' ', $id);
        $this->remove($id);
    }

    public function act() {
        return $this->action();
    }
}