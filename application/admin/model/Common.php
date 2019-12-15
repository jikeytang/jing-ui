<?php

namespace app\admin\model;

use think\Model;
use think\Db;

class Common extends Model {
    protected $_auto = array(
        array('status', 1),
        array('inputtime', 'time', 1, 'function'),
        array('updatetime', 'time', 2, 'function'),
        array('ctime', 'ctime', 1, 'callback'),
    );

    public function ctime(){
        return date('Y-m-d', time());
    }

    /**
     * 得到评论列表
     * @param $field 需要得到的字段
     * @param string $where 筛选条件
     * @param int $pageSize 每页的记录数
     */
    public function getCommentList($field, $where='', $pageSize=15){
        $count = $this->field('id')->where($where)->count();
        $list = $this->field($field)->where($where)->order('id', 'desc')->paginate($pageSize);

        foreach($list as $k => $v){
            $res = $this->where(array('id' => $v['pid']))->column('username');
            if(!empty($res)){
                $list[$k]['toUserName'] = $res;
            } else {
                $list[$k]['toUserName'] = null;
            }
        }

        return array('page' => $list->render(), 'list' => $list);
    }

    public function getPath(){
        $pid = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;
        $id = $_POST['id'];
        if($pid == 0){
            return 0;
        }
        $fat = $this->where(array('id'=>$pid))->find();
        $data = $fat['path'] . '-' . $fat['id'];
        return $data;
    }
}