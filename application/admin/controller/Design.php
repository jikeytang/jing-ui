<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Design extends Common {
    public function index() {
        return $this->lists();
    }

    public function insert() {
        $this->getInsert();
        return $this->fetch();
    }

    public function add() {
        $data['title'] = stripslashes($_POST['title']);
        $data['author'] = $_POST['author'];
        $data['content'] = stripslashes($_POST['content']);
        $data['colId'] = $_POST['colId'];
        $data['inputtime'] = time();
        $data['ctime'] = date("Y-m-d",time());
        $this->addData($data);
    }

    public function edit() {
        $id = $_REQUEST['id'];
        $this->getEdit($id);
        return $this->fetch();
    }

    public function modify() {
        $data['id'] = $_POST['id'];
        $data['title'] = stripslashes($_POST['title']);
        $data['author'] = $_POST['author'];
        $data['content'] = stripslashes($_POST['content']);
        $data['colId'] = $_POST['colId'];
        $data['inputtime'] = time();
        $data['ctime'] = date("Y-m-d",time());
        $data['smallimg'] = $_POST['smallimg'];
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
}