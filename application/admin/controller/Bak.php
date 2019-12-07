<?php

namespace app\admin\controller;

use core\utils\Dbbackup;

class Bak extends Common {

    public function mysqlcn(){
        return new Dbbackup(config()['database']);
    }

    public function index(){
        $tabInfo = $this->mysqlcn()->gettbinfo();
        $tabs = array();
        foreach($tabInfo as $k => $v ){
            $tabs[$k]['id'] = $k + 1;
            $tabs[$k]['name'] = $v['Name'];
            $tabs[$k]['engine'] = $v['Engine'];
            $tabs[$k]['data_length'] = sizecount($v['Data_length']);
            $tabs[$k]['create_time'] = $v['Create_time'];
            $tabs[$k]['collation'] = $v['Collation'];
        }
        $this->assign('tabs', $tabs);
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

}
