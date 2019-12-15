<?php

namespace app\home\model;

class Msg extends Common {

    protected $_auto = array(
        array('status', '0'),
        array('inputtime', 'time', '1', 'function'),
    );

    public function getCommentList($field, $where='', $pageSize=15){
        $count = $this->field('id')->where($where)->count();
        $list = $this->field($field)->where($where)->order('bpath,id desc')->paginate(10);

        foreach($list as $k => $v){
            $res = $this->where(array('pid' => $v['id']))->select();
            if(!empty($res)){
                $list[$k]['toUser'] = $res;
            } else {
                $list[$k]['toUser'] = null;
            }
        }

        return array('list' => $list);
    }
}