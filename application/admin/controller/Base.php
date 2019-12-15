<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\User;


class Base extends Controller {

    public function index() {
        return $this->fetch();
    }

    public function login() {
        return $this->fetch();
    }

    public function isPost() {
        return request()->isPost();
    }

    public function checkLogin() {
        if ($this->isPost()) {
            $data = input('post.');
        } else {
            return;
        }

        $verify = $data['verify'];
        $username = $data['username'];

        if (empty($_POST['username'])) {
            $this->error('请填写用户名！');
        } elseif (empty($_POST['pwd'])) {
            $this->error('请填写密码！');
        }

        if (!captcha_check($verify)) {
            $this->error('验证码错误!');
        }

        $info = User::where('username', $username)->find();

        if (is_null($info)) {
            $this->error('账号不存在!');
        } else {
            if ($info['pwd'] != md5($_POST['pwd'])) {
                $this->error('密码错误!');
            }

            session('auth_key', $info['id']);
            session('username', $info['username']);
            session('logintime', $info['logintime']);

            $this->success('登录成功', url('/'));
        }

    }

    // 检查用户是否登录
    protected function checkUser() {
        if (!empty(session('auth_key'))) {
            $this->error('没有登录', url('/Base/login'));
        }
    }

    public function updatePwd() {
        $this->checkUser();
        $verify = $_POST['verify'];

        if (!captcha_check($verify)) {
            $this->error('验证码错误!');
        }

        if ($_POST['newpwd'] != $_POST['renewpwd']) {
            $this->error('两次输入的密码不一致！');
        }

        $map = array();
        $map['pwd'] = md5($_POST['oldpwd']);
        if (isset($_POST['username'])) {
            $map['username'] = $_POST['username'];
        } elseif (empty(session('auth_key'))) {
//            $map['id'] = session('auth_key');
        }

        // 检查用户
        $user = User::where($map)->find();
        if (is_null($user)) {
            $this->error('旧密码不符或者用户名错误！');
        } else {
            $user->pwd = md5($_POST['newpwd']);
            $user->logintime = time();
            $user->loginip = $this->request->ip();
            $user->save();
            $this->success('密码修改成功！');
        }

    }

    // 退出登录操作
    public function logout() {
        cookie('SESSIONID', null);
        session(null);
        $this->redirect(url('/Base/login'));
    }

    public function user() {
        $map['id'] = session('auth_key');
        $vo = User::field('id,username,logintime,updatetime,loginip')->where($map)->find();
        $this->assign('vo', $vo);
        return $this->fetch();
    }

    public function password() {
        return $this->fetch();
    }

}