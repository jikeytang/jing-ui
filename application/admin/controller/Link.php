<?php
namespace app\admin\controller;

use think\Controller;

class Link extends Common {
    public function index() {
        return $this->lists();
    }

    public function insert() {
        $this->getInsert();
        return $this->fetch();
    }

    public function add(){
        $data['title'] = $_POST['title'];
        $data['url'] = $_POST['url'];
        $data['linkman'] = $_POST['linkman'];
        $data['introduce'] = $_POST['introduce'];
        $data['inputtime'] = time();
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
        $data['url'] = $_POST['url'];
        $data['linkman'] = $_POST['linkman'];
        $data['introduce'] = $_POST['introduce'];
        $data['inputtime'] = time();
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
