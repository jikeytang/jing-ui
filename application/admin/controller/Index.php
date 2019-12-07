<?php

namespace app\admin\controller;

class Index extends Common {
    public function index() {
        $info = array(
            '运行环境' => $_SERVER['SERVER_SOFTWARE'],
            'PHP运行方式' => php_sapi_name(),
            '静静设计-版本' => 'v-0.1',
            '上传附件限制' => ini_get('upload_max_filesize'),
            '北京时间' => gmdate('Y年n月j日 H:i:s', time() + 8 * 3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '操作系统' => PHP_OS,
            'Host' => gethostbyname($_SERVER['SERVER_NAME']),
        );
        $this->assign('info', $info);

        return $this->fetch();
    }
}