<?php

namespace app\admin\controller;

class Cache extends Common {
    public function index(){
        return $this->fetch();
    }

    public function del(){
        $type = trim($_GET['type']);
        if(empty($type)){ $this->error('请选择类型！'); }

        switch($type){
            case 1:
                $path = env('runtime_path');
                break;
            case 2:
                $path = env('runtime_path') . 'temp/';
                break;
            case 3:
                $path = DATA_PATH;
                break;
            case 4:
                $path = env('runtime_path') . 'cache/';
                break;
            case 5:
                $path = env('runtime_path') . 'cache/admin';
                break;
            case 6:
                $path = env('runtime_path') . 'cache/home';
                break;
        }

        if(is_dir($path)){
            $this->delDir($path);
            $this->success('更新成功！');
        } else {
            $this->error('已清空!');
        }

    }

    //删除目录
    private function delDir($dirName) {
        if (!file_exists($dirName)) {
            return false;
        }
        $dir = opendir($dirName);
        while (false !== ($fileName = readdir($dir))) {
            $file = $dirName . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file)) {
                    $this->delDir($file);
                } else {
                    unlink($file);
                }
            }
        }
        closedir($dir);
        return rmdir($dirName);
    }
}
