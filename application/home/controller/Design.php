<?php

namespace app\home\controller;

class Design extends Common {
    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $list = model('Columns')->field('colId, colTitle, description')->order('ord asc,colId asc')->where(array('colPid' => 1))->select();
        foreach ($list as $k => $v) {
            $res = model('Design')->limit(16)->order('ord asc,id desc')->where(array('colId' => $v['colId']))->select();

            if (!empty($res)) {
                foreach ($res as $key => $val) {
                    $commentNum = model('Comment')->where(array('bid' => $val['id']))->count();
                    $res[$key]['commentNum'] = $commentNum;
                }

                $list[$k]['works'] = $res;
            }

        }

        $this->assign('list', $list);
        return $this->fetch();
    }

    public function add() {
        $this->addData('Comment');
    }

    public function designlist($id, $title, $desc) {
        $D = model('Design');
        $where['colId'] = $id;
        $count = $D->field('id')->where($where)->count();
//        $pageSize = 15;
//        $page = new Page($count, $pageSize);

        $list = $D->field('id, title, inputtime, clicks, likes, smallimg')->where($where)->select();
        foreach ($list as $k => $v) {
            $commentNum = model('Comment')->where(array('bid' => $v['id']))->count();
            $list[$k]['commentNum'] = $commentNum;
        }

        /*
        $page->setConfig('header', '条');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('first', '<<');
        $page->setConfig('last', '>>');
        */

        $this->assign('id', $id);
        $this->assign('title', $title);
        $this->assign('desc', $desc);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function detail($id) {
        $arr = $this->readDetail('Design', $id);
        $comment = model('Comment')->getCommentList("id,username,headimg,inputtime,pid,nid,url,content,path,concat(path,'-',id) as bpath", array('bid' => array('eq', $arr['vo']['id'])));
        $columns = model('Columns')->field('colId,colTitle,description')->where(array('colId' => $arr['vo']['colId']))->find();

        // 点击次数更新
        $data['clicks'] = $arr['vo']['clicks'] + 1;
        model('Design')->where(array('id' => $arr['vo']['id']))->update($data);

        $this->assign('vo', $arr['vo']);
        $this->assign('commentList', $comment['list']);
//        $this->assign('page', $comment['page']);
        $this->assign('columns', $columns);
        return $this->fetch();
    }

    public function support() {
        $id = $_GET['id'];

        $model = model('Design');
        $vo = $model->where(array('id' => $id))->find();
        $data['likes'] = $vo['likes'] + 1;

        //            $data['uid'] = $data['uid'] . '|' . $sid;

        $res = $model->where(array('id' => $id))->save($data);

        if (!$vo) {
            $this->error('数据创建失败！');
        }
        if ($res > 0) {
            $this->ajaxReturn($res, '谢谢支持！', 1);
        } else {
            $this->error('操作失败!');
        }

    }

}
