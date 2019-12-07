<?php
/**
 * Created by PhpStorm.
 * User: jikey
 * Date: 2018-4-1
 * Time: 20:55
 */

namespace app\home\model;

use think\Model;
use think\Config;

class Common extends Model {
    public function getCommentList($field, $where='', $pageSize=15){
        $count = $this->field('id')->where($where)->count();
        $list = $this->field($field)->where($where)->order('bpath,id desc')->select();
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

    // 文章归档
    public function getDateList($module){
        $module = config('database.prefix') . strtolower($module);
        $list = $this->query("select count(from_unixtime(inputtime, '%Y-%m')) as Count, from_unixtime(inputtime, '%Y-%m') as Time, inputtime as t from " . $module . " group by Time order by Time desc");
        return $list;
    }

    /**
     * 得到相关文章
     * @param $model
     * @param string $type
     * @return string
     */
    public function getPrevNextArt($time, $type='prev'){
        if($type == 'prev'){
            $where['inputtime'] = array('gt', $time);
            $ord = 'asc';
        }
        if($type == 'next'){
            $where['inputtime'] = array('lt', $time);
            $ord = 'desc';
        }

        $list = $this->where($where)->field('title,id')->order('ord asc,inputtime ' . $ord)->find();
        if($list){
            $title = $list['title'];
            $url = "<a title='$title' href='" . url('', array('id' => $list['id'])) . "' target='_blank'>" . mb_substr($title, 0, 22) . "</a>";
            return $url;
        } else {
            return '';
        }

    }

    /**
     * 得到分类列表
     * @param $modelName 模块名称
     * @param $modelId 模块id
     * @return mixed
     */
    public function getCat($modelName, $modelId) {
        return $this->field('description')->where(array('modelid' => $modelId, 'colPid' => $modelId))->order('ord desc')->find();
    }
}