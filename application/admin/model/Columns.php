<?php

namespace app\admin\model;

use think\Model;
use think\Db;

class Columns extends Model {
    protected $pk = 'colId';


    public function sortList($modelName, $modelId) {

        $model = db($modelName);
        $list = $this->field("concat(colPath,'-', colId) as bPath, colPid, colId, colTitle, description, ord, model")->where(array('modelid' => $modelId))->select();

        if(!empty($list)){
            foreach($list as $k => $v){
                $list[$k]['count'] = count(explode('-', $v['bPath']));

                if (in_array($modelName, ['Design', 'Columns'])) {
                    $list[$k]['total'] = $model->where(array('colId' => $v['colId']))->count();
                }

                if (in_array($modelName, ['Blog'])) {
                    $list[$k]['total'] = $model->where(array('catid' => $v['colId']))->count();
                }

                $str = '';

                if($v['colPid'] != 0){
                    for($i = 0; $i < $list[$k]['count']; $i++){
                        $str .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                    $str .= '|-';
                }

                $list[$k]['space'] = $str;
            }
        }

        return $list;
    }
}