<?php

namespace app\home\controller;

use think\Exception;

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
        return $this->addData('Comment');
    }

    public function designlist($id, $title, $desc = '') {
        $D = model('Design');
        $where['colId'] = $id;
        $count = $D->field('id')->where($where)->count();

        $list = $D->field('id, title, inputtime, clicks, likes, smallimg')->where($where)->select();
        foreach ($list as $k => $v) {
            $commentNum = model('Comment')->where(array('bid' => $v['id']))->count();
            $list[$k]['commentNum'] = $commentNum;
        }

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
        $this->assign('columns', $columns);
        return $this->fetch();
    }

    public function support() {
        $id = request()->param('id');

        $res = model('design')->where('id', $id)->setInc('likes');

        if ($res > 0) {
            $res = [ 'code' => 1, 'msg' => '谢谢支持！' ];
        } else {
            $res = [ 'code' => 0, 'msg' => '操作失败！' ];
        }

        return json($res);

    }

}
