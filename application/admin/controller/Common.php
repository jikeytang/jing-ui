<?php

namespace app\admin\controller;

use app\admin\util\RBAC;
use think\Controller;
use think\Config;
use think\Request;
use think\Model;

class Common extends Controller {

    protected $request;
    protected $isPost;

    function __construct() {
        parent::__construct();

        // 用户权限检查
        check_login(session('username'));
    }

    public function _initialize() {
        $this->request = Request::instance();
        $moduleName = $this->request->controller();
    }

    public function isPost() {
        return $this->request->isPost();
    }

    public function getActionName () {
        return $this->request->controller();
    }

    public function lists() {
        $modelName = $this->getActionName();
        $modelId = $this->getModelId($modelName);

        if(!$this->isPost()){
            $listArr = $this->getPageLists($modelName, '');
        } else {
            if(!empty($_POST['keywords'])){
                $keywords = $_POST['keywords'];
                $map[] = array('title', 'like', '%' . $keywords . '%');
                $listArr = $this->getPageLists($modelName, $map);
                $this->assign('keywords', $keywords);
            } else {
                $listArr = $this->getPageLists($modelName, '');
            }
        }

        if(in_array($modelName, array('Design', 'About', 'Contact', 'Blog'))){
            $this->assign('sortList', model('Columns')->sortList($modelName, $modelId));
        }

        $lists = $listArr['lists'];
        $this->assign('page', $lists ? $lists->render() : null);
        $this->assign('lists', $lists);

        return $this->fetch();
    }

    public function getInsert() {
        $modelName = $this->getActionName();
        $modelId = $this->getModelId($modelName);
        $data = model('Columns')->sortList($modelName, $modelId);
        $this->assign('sortList', $data);
    }

    public function getModelId($modelName){
        $array = array(1 => 'Design', 2 => 'About', 3 => 'Contact', 4 => 'Blog');
        $modelId = (int)implode(',', array_keys($array, $modelName));
        return $modelId;
    }

    public function addData($data){
        if(!$this->isPost()){ $this->error('非法请求'); }
        $module = $this->getActionName();
        $D = model($module);

        if(count($_FILES) > 0 && $_FILES['smallimg']['size'] > 0){
            $info = $this->_upload();
            $saveName = $info['savename'];
            $imgUrl = $saveName;
            $data['smallimg'] = $imgUrl;
        }

        $vo = $D->create($data);
        if($vo){
            $this->success('添加成功', 'admin/' . $module . '/index');
        } else {
            $this->error('添加失败');
        }
    }
    
    public function remove($id){
        $module = $this->getActionName();
        $mod = db($module);
        $result[] = array();

        foreach($id as $v){
            if(in_array($module, array('Design', 'About', 'Contact', 'Blog'))){
                $thumb = $mod->where(array('id' => $v))->column('smallimg');
                $this->delThumb($thumb[0]);
            }
            
            if(in_array($module, array('Columns'))){
                $result[] = $mod->where(array('colId' => $v))->delete();
            } else {
                $result[] = $mod->where(array('id' => $v))->delete();
            }
            
        }

        if($result){
            $this->success('删除成功', 'admin/' . $module . '/index');
        } else {
            $this->error('删除失败');
        }
    }

    // 删除缩略图片
    public function delThumb($thumb){
        if($thumb !== false){
            $file = 'Public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . strtolower($this->getActionName()) . $thumb;
            if(is_file($file)){
                unlink($file);
            }
        }
    }

    protected function _upload () {
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move('./uploads/' . strtolower($this->getActionName()) );

        if($info){
            $result['code'] = 1;
            $path = str_replace('\\','/',$info->getSaveName());
            $result['savename'] = $info->getSaveName();
            $result['url'] = '/uploads/'. $path;
            $result['ext'] = $info->getExtension();
        }else{
            // 上传失败获取错误信息
            $result['code'] = 0;
            $result['url'] = '';
        }

        return $result;
    }

    protected function _upload3(){
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();
        $upload->maxSize = 3292200;
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->savePath = './Public/uploads/';
        $upload->saveRule = 'time';
        if(!$upload->upload()){
            $this->error($upload->getErrorMsg());
        } else {
            $info = $upload->getUploadFileInfo();
//                import('ORG.Util.Image');
//                $img = new Image();
            // 给m_缩略图添加水印，Image:water('原文件名', '水印图片地址')
//                $img->water($info[0]['savepath'].$info[0]['savename'], './Public/uploads/logo.png');
//                $_POST['image'] = $upload[0]['savename'];
        }

        return $info;
    }

    protected function _upload2(){
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        $upload->savePath = './Public/uploads/';
        $upload->thumb = true;
        $upload->imgeClassPath = '@.ORG.Image';
        $upload->thumbPrefix = 'm_,s_';
        $upload->thumbMaxWidth = '400,100';
        $upload->thumbMaxHeight = '400,100';
        $upload->saveRule = uniqid;
        $upload->thumbRemoveOrigin = true;
        if(!$upload->upload()){
            $this->error($upload->getErrorMsg());
        } else {
            $uploadList = $upload->getUploadFileInfo();
            import('ORG.Util.Image');
            $img = new Image();
            // 给m_缩略图添加水印，Image:water('原文件名', '水印图片地址')
            $img->water($uploadList[0]['savepath'].$uploadList[0]['savename'], '../Public/images/logo.png');
            $_POST['image'] = $uploadList[0]['savename'];
        }
        $model = db('index');
        $data['smallimg'] = $_POST['image'];
        $data['ctime'] = time();
        $list = $model->add($data);
        if($list !== false){
            $this->success('上传图片成功！');
        } else {
            $this->error('上传图片失败');
        }
    }

    public function getEdit($id){
        $module = $this->getActionName();
        $M = db($module);
        $modelId = $this->getModelId($module);
        $this->assign('sortList', model('Columns')->sortList($module, $modelId));

        $vo = $M->where('id=' . $id)->find();
        $this->assign('vo', $vo);
    }

    public function getModify($data){
        if(!$this->isPost()){ $this->error('非法请求!'); }
        $module = $this->getActionName();

        $D = model($module);
        $pk = $D->getPK();

        if(count($_FILES) > 0 && $_FILES['smallimg']['size'] > 0){
            $info = $this->_upload();
            $saveName = $info['savename'];
            $imgUrl = $saveName;
            $data['smallimg'] = $imgUrl;
        }

//        $data['inputtime'] = time();
//        $data['updatetime'] = date("Y-m-d",time());

        $list = $D->where($pk, $data[$pk])->update($data);
        if(false === $list){
            $this->error($this->getError());
        }

        $this->say($list, 'admin/' . $module . '/index');
    }

    /**
     * 输出调试信息
     * @param $res 结果信息
     * @param $backUrl 返回的url
     * @param string $ok 信息
     * @param string $no 信息
     */
    public function say($res, $backUrl, $ok='操作成功', $no='操作失败'){
        if(false !== $res){
            $this->success($ok, $backUrl);
        } else {
            $this->error($no);
        }
    }

    /**
     * 分页信息
     * @param $modeName
     * @param string $where
     * @param string $order
     */
    public function getPageLists($modeName, $where='', $order='ord asc, id desc', $pageSize=12){
        $M = db($modeName);
        $count = $M->where($where)->count();
        $lists = $M->where($where)->order($order)->paginate($pageSize);

        return array('lists' => $lists);
    }

    // 批量删除处理
    public function action(){
        $actionName = trim($_GET['action']);
        $ids = $_POST['ids'];
        $id = $_POST['id'];
        $ord = $_POST['ord'];

        switch($actionName){
            case 'del' :
                $this->remove($id);
                break;
            case 'ord' :
                $this->updateOrd($ids, $ord);
                break;
            default :
                $this->error('未知操作!');
                break;
        }
    }

    // 更新排序
    public function updateOrd($ids, $ord){
        $module = $this->getActionName();
        $mod = db($module);

        foreach($ids as $k => $v){
            $data['ord'] = $ord[$k];
            $id = $ids[$k];
            if($module == 'Columns'){
                $map['colId'] = $id;
            } else {
                $map['id'] = $id;
            }
            $result = $mod->where($map)->save($data);
        }

        $this->say($result);
    }



}