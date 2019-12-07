<?php

namespace app\home\controller;

class Blog extends Common {
    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $blogList = $this->getListInfo('Blog');
        $this->assign('list', $blogList['list']);
        $this->assign('page', $blogList['page']);

        return $this->fetch();
    }

    protected function listArr(){
        $arr = array();
        $arr['list'] = model('Columns')->getCatList('Blog', 4);

        $this->assign('catList', $arr['list']);
    }

    public function read($id){
        $this->listArr();
        $arr = $this->readDetail('Blog', $id); // readaction()
        $comment = model('Comment')->getCommentList("id,username,headimg,inputtime,pid,nid,url,content,path,concat(path,'-',id) as bpath", array('bid' => array('eq', $arr['vo']['id'])));

        $blog = model('Blog');
        $art['prev'] = $blog->getPrevNextArt($arr['vo']['inputtime'], 'prev'); // 上一篇
        $art['next'] = $blog->getPrevNextArt($arr['vo']['inputtime'], 'next'); // 下一篇

        $this->assign('art', $art);
        $this->assign('vo', $arr['vo']);
        $this->assign('commentList', $comment['list']);
        return $this->fetch();
    }

    public function add(){
        $this->addData('Comment');
    }

    public function search(){
        if(empty($_POST['keywords'])){
            $this->error('关键字不能为空！');
        }

        $map[] = array('title', 'like', '%' . $_POST['keywords'] . '%');
        $blogList = $this->getListInfo('Blog', $map);

        $this->assign('list', $blogList['list']);
        $this->assign('page', $blogList['page']);
        return $this->fetch('index');
    }

}
