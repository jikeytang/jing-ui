<?php

namespace core\utils;

use think\Db;
use think\Config;

class Dbbackup{

    private $dbhost;//数据库主机

    private $dbuser;//数据库用户名

    private $dbpwd;//数据库密码

    private $dbname;//数据库名

    private $coding;//数据库编码,GBK,UTF8,gb2312

    private $conn;//数据库连接标识

    private $data_dir = 'data/';//文件夹路径（存放备份数据）

    private $part = 2048;//分卷长度（单位KB）

    public $bakfn;//备份文件名

    /**
     * 数据库配置
     * @var integer
     */
    private $dbconfig = array();


    public function __construct($config, $coding = 'UTF8',$pconnect = 0){
        $this->init();
        $this->dbname = $config['database'];
        $this->coding = $coding;
        $this->setDbConn($config);
        $this->part = $this->part * 1024; //设置分卷长度,单位为KB
        $this->cre_dir();  				  //创建文件夹
    }


    private function init(){
        set_time_limit(0);					//程序执行不限时
        error_reporting(E_ERROR | E_PARSE); //报错级别
    }

    /**
     * 设置数据库连接必备参数
     * @param array  $dbconfig   数据库连接配置信息
     * @return object 
     */
    public function setDbConn($config = []) {
        $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $this->dbname);
        $this->conn = $conn;

        return $this;
    }


    private function cre_dir(){
        //文件夹不存在则创建
        if(!is_dir($this->data_dir)){
            mkdir($this->data_dir, 0777);
        }
    }

    public function gettbinfo(){
        if ($res = mysqli_query($this->conn, "SHOW TABLE STATUS FROM `" . $this->dbname . "`")){
            return $res; //返回表集合
        }
    }

    public function get_backupdata($arrtb){
        $backupdata = "#\n# Jing-ui bakfile\n#Time: ".date('Y-m-d H:i',time())."\n# Type: \n# jing-ui: http://www.jing-ui.com\n# --------------------------------------------------------\n\n\n"; //存储备份数据
        $conn = $this->conn;

        foreach($arrtb as $tb){
            //获取表结构
            $query = mysqli_query($conn, "SHOW CREATE TABLE $tb");
            $row = mysqli_fetch_row($query);
            $backupdata .= "DROP TABLE IF EXISTS $tb;\n" . $row[1] . ";\n\n";
            //获取表数据
            $query = mysqli_query($conn, "select * from $tb");
            $numfields = mysqli_num_fields($query); //统计字段数

            while($row = mysqli_fetch_row($query)){
                $comma = "";
                $backupdata .= "INSERT INTO $tb VALUES (";

                for($i=0; $i<$numfields; $i++){
                    $backupdata .= $comma . "'" . str_replace(array("\r", "\n"), array('\\r', '\\n'), addslashes($row[$i])) . "'";
                    $comma = ",";
                }

                $backupdata .= ");\n";

                if(strlen($backupdata) > $this->part){
                    $arrbackupdata[] = $backupdata;
                    $backupdata = '';
                }
            }
            $backupdata .= "\n";
        }

        if(is_array($arrbackupdata)){
            array_push($arrbackupdata, $backupdata);
            return $arrbackupdata;
        }

        return $backupdata;
    }


    private function wri_file($data){

        if(is_array($data)){
            $i = 1;
            foreach($data as $val){

                $filename = $this->data_dir . "tk_" . time() . "_part{$i}.sql"; //文件名
                if(!$fp = @fopen($filename, "w+")){ echo "在打开文件时遇到错误,备份失败!"; return false;}
                if(!@fwrite($fp, $val)){
                    echo "在写入信息时遇到错误,备份失败!"; fclose($fp); //需关闭文件才能删除
                    unlink($filename); //删除文件
                    return false;}
                $this->bakfn[] = "tk_" . time() . "_part{$i}.sql"; //备份成功则返回文件名数组
                $i++;
            }
        }else{ //单独备份
            $filename = $this->data_dir . "tk_" . time() . ".sql";
            if(!$fp = @fopen($filename, "w+")){ echo "在打开文件时遇到错误,备份失败!"; return false;}
            if(!@fwrite($fp, $data)){
                echo "在写入信息时遇到错误,备份失败!"; fclose($fp);
                unlink($filename);
                return false;}
            $this->bakfn = "tk_" . time() . ".sql";
        }
        fclose($fp);
        return true;
    }


    public function export($data){
        return $this->wri_file($data); //写入数据
    }

    public function get_backup(){
        $backup = scandir($this->data_dir);
        for($i=0; $i<count($backup); $i++){
            if($backup[$i] != "." && $backup[$i] != ".."){
                if(stristr($backup[$i], 'tk_')) $arrbackup[] = $backup[$i];

            }
        }
        return $arrbackup;
    }

    public function import($filename){

        $Boolean = preg_match("/_part/",$filename);
        if($Boolean){
            $fn = explode("_part", $filename);
            $backup = scandir($this->data_dir);
            for($i=0; $i<count($backup); $i++){
                $part = preg_match("/{$fn[0]}/", $backup[$i]);
                if($part){
                    $filenames[] = $backup[$i];
                }
            }
        }

        if(is_array($filenames)){
            foreach($filenames as $fn){
                $data .= file_get_contents($this->data_dir . $fn);  //获取数据
            }
        }else{
            $data = file_get_contents($this->data_dir . $filename);
        }

        $data = str_replace("\r", "\n", $data);
        $regular = "/;\n/";
        $data = preg_split($regular,trim($data));

        foreach($data as $val){
            mysqli_query($this->conn, $val) or die('导入数据失败！' . mysqli_error($this->conn));
        }

        return true;
    }


    public function del($delfn){

        if(is_array($delfn)){
            foreach($delfn as $fn){
                if(!unlink($this->data_dir.$fn)){ return false;}
            }
            return true;
        }

        return unlink($this->data_dir.$delfn);
    }
}

?>





