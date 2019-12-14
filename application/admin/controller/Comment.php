<?php
namespace app\admin\controller;

class Comment extends Common {
    public function index() {
        return $this->getCommentData();
    }

    public function blogindex(){
        return $this->getCommentData();
    }
    
    public function reply(){
        $comment = model('Comment');
        $vo = $comment->relation(true)->getById($_GET['id']);
        $this->assign('vo', $vo);
        return $this->fetch();
    }

    public function answer(){
        $c = model('Comment');
        if($c->create()){
            $c->username = '静静';
            $result = $c->add();
        }
        $this->say($result, 'comment');
    }

    public function del(){
        $id = $_GET['id'];
        $m = db('Comment');

        // 删除评论所属评论
        $m->where(array('pid' => $id))->delete();

        // 删除评论
        $result = $m->where(array('id' => $id))->delete();
        $this->say($result, 'comment/index', '删除成功！', '删除失败！');
    }

    public function act(){
        $this->action();
    }

    protected function getCommentData(){
        $where['isreply'] = array('neq', 1);
        $field = "id,username,author,pid,nid,bid,inputtime,headimg,email,url,module,isreply,ip,content,path,concat(path,'-',id) as bpath";
        $model = model('Comment');

        if(!$this->isPost()){
            $arr = $model->getCommentList($field, $where);
        } else {
            if(!empty($_POST['keywords'])){
                $map[] = array('content', 'like', '%' . $_POST['keywords'] . '%');
                $keywords = $_POST['keywords'];
                $arr = $model->getCommentList($field, $map);
                $this->assign('keywords', $keywords);
            }
        }

        $this->assign('page', $arr['page']);
        $this->assign('commentList', $arr['list']);
        return $this->fetch();
    }
    
}
