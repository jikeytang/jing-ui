<?php
/**
 * Created by PhpStorm.
 * User: jikey
 * Date: 2018-4-1
 * Time: 20:55
 */

namespace app\home\model;

use think\Model;

class Columns extends Common {
    protected $pk = 'colId';

    public function col() {
        return $this->hasMany('Blog', 'modelid');
    }
    /**
     * 得到分类列表
     * @param $modelName 模块名称
     * @param $modelId 模块id
     * @return mixed
     */
    public function getCatList($modelName, $modelId) {
        $list = $this->field('colId,colPid,modelid,ord,colTitle,description,model')->where(array('modelid' => $modelId, 'colPid' => $modelId))->order('ord desc')->select();
        foreach ($list as $k => $v) {
            $list[$k]['total'] = db($modelName)->field('id,catid')->where(array('catid' => $v['colId']))->count();
        }

        return $list;
    }

    public function menu() {
        return $this->field('colId, colTitle, asmenu')->where(array('asmenu' => 1))->order('ord desc')->select();
    }
}