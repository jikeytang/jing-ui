<?php

namespace app\admin\controller;

class Advs extends Common {
    public function index() {
        return $this->lists();
    }

    public function insert() {
        $this->getInsert();
        return $this->fetch();
    }

    public function add(){
        $data['title'] = $_POST['title'];
        $data['title'] = $_POST['title'];
        $data['link'] = $_POST['link'];
        $this->addData($data);
    }

    public function edit(){
        $id = $_REQUEST['id'];
        $this->getEdit($id);
        return $this->fetch();
    }

    public function modify(){
        $data['id'] = $_POST['id'];
        $data['title'] = $_POST['title'];
        $data['link'] = $_POST['link'];
        $this->getModify($data);
    }
    
    public function del(){
        $id = $_GET['id'];
        $id = explode(' ', $id);
        $this->remove($id);
    }

    public function act(){
        $this->action();
    }
}