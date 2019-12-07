<?php
namespace app\admin\controller;

use think\Controller;
use think\db\Where;

class Msg extends Common {
    public function index() {

        $modelName = $this->getActionName();
        $model = model($modelName);

        $field = "id,username,pid,inputtime,email,url,isreply,ip,content,path,concat(path,'-',id) as bpath";
        $where = '';

        if(!$this->isPost()){
            $arr = $model->getCommentList($field, $where);
        } else {
            if(!empty($_POST['keywords'])){
                $map['content'] = array('like', '%' . $_POST['keywords'] . '%');
                $keywords = $_POST['keywords'];
                $arr = $model->getCommentList($field, $map);
                $this->assign('keywords', $keywords);
            }
        }

        $this->assign('page', $arr['page']);
        $this->assign('commentList', $arr['list']);

        return $this->fetch();
    }

    public function reply(){
        if(!empty($_GET['id'])){
            $modelName = $this->getActionName();
            $comment = model($modelName);
            $vo = $comment->getById($_GET['id']);
            $this->assign('vo', $vo);
            return $this->fetch();
        }
    }

    public function answer(){
        $modelName = $this->getActionName();
        $module = model($modelName);
        $result = $module->save();
        $this->say($result, $modelName . '/index');
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
