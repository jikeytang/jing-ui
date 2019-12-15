<?php

namespace app\home\model;
use think\Model;

class Design extends Common {

    /*
    protected $_link = array(
        'Comment' => array(
            'mapping_type' => HAS_MANY, // 一篇博文可能拥有多条评论
            'class_name' => 'Comment',
            'foreign_key' => 'bid',
            'mapping_fields' => 'username,email,inputtime,content',
        ),
    );
    */

    public function index() {
        return $this->hasMany('Comment', 'bid')->fields('username,email,inputtime,content');
    }

}