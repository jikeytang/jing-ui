<?php

namespace app\admin\controller;

class Restore extends Bak {

    public function index(){
        $bakDatas = $this->mysqlcn()->get_backup();
        $datas = array();
        foreach($bakDatas as $k => $v ){
            $datas[$k]['id'] = $k + 1;
            $datas[$k]['fileName'] = $v;
            $datas[$k]['cTime'] = $this->getDataCreateTime($v);
            $datas[$k]['part'] = $this->getPart($v);
        }
        $this->assign('datas', $datas);

        return $this->fetch();
    }
    
    public function export(){
        if($_POST['sub']){
            $data = $this->mysqlcn()->get_backupdata($_POST['id']);
            if($this->mysqlcn()->export($data)){
                $this->success('恭喜您备份成功!');
            }
        }
    }

    protected function getDataCreateTime($file){
        if(!preg_match('/_part/', $file)){
            return $this->getCreateTime('.', $file);
        } else {
            return $this->getCreateTime('_part', $file);
        }
    }

    protected function getCreateTime($sign, $file){
        $str = explode($sign, $file);
        $time = substr($str[0], -10);
        Date_default_timezone_set('PRC');
        return date('Y-m-d h:i', $time);
    }

    protected function getPart($file){
        if(!preg_match('/_part/', $file)){
            return '1';
        } else {
            $str = explode('.', $file);
            return substr($str[0], -1);
        }
    }

    public function recovery(){
        if($_GET['fn']){
            $sqlFile = trim($_GET['fn']);
            if($this->mysqlcn()->import($sqlFile)){
                $this->success('恭喜您<br>备份的数据已经成功导入！');
            }
        }
    }

    public function del(){
        if($this->mysqlcn()->del($_POST['id'])){
            $this->success('成功删除！');
        } else {
            $this->success('删除失败！');
        }
    }

}
