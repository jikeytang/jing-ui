<?php

namespace app\home\controller;

use think\facade\App;
use think\facade\View;
use think\Controller;

class Common extends Controller {
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;


    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app) {
//        $this->app     = $app;
//        $this->request = $this->app->request;
//        $this->request = app('request');

        // 控制器初始化
        $this->initialize();
    }


    public function initialize() {
        header('Content-type:text/html; charset=utf-8');
    }

    protected function assign($name, $value = '') {
        View::assign($name, $value);
    }

    protected function fetch($template = '', $data = [], $config = []) {
        return View::fetch($template, $data, $config);
    }

    public function error404() {
        $this->assign('url', '__APP__/Public/error404');
        $this->error('抱歉您请求的页面不存在或已经删除!');
    }

    /**
     * 得到博客列表
     * @param $modelName
     * @param string $where
     * @param int $pageSize
     * @param string $ord
     * @return array
     */
    public function getListInfo($modelName, $where = '', $pageSize = 15, $ord = 'ord asc,id desc') {
        $D     = model($modelName);
        $field = 'id,catid,title,content,smallimg,description,inputtime,author,clicks,status';
        $list  = $D->field($field)->where($where)->order($ord)->paginate($pageSize);

        return array('list' => $list, 'page' => $list->render());
    }

    public function readDetail($modelName, $id) {
        $model = model($modelName);
        $vo    = $model->where(array('id' => $id))->find();

        // 点击次数更新
        $data['clicks'] = $vo['clicks'] + 1;
        $model->where(array('id' => $id))->update($data);

        return array('vo' => $vo);
    }

    public function addData($modelName) {
        $verify = trim($_POST['verify']);

        if (empty($verify)) {
            $this->error('验证码不能为空!');
        }
        if (!captcha_check($verify)) {
            $this->error('验证码错误!');
        }

        $model              = model($modelName);
        $_POST['content']   = htmlspecialchars($_POST['content']); // 过滤非法html代码
        $_POST['inputtime'] = time();
        $vo                 = $model->create($_POST);

        if (!$vo) {
            $this->error('数据创建失败！');
        } else {
            $this->success($_POST);
        }
    }
}