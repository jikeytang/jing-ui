<?php

namespace app\admin\model;

use think\Model;

class Comment extends Common {

    public function design() {
        // belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('design', 'nid');
    }

    public function blog() {
        return $this->belongsTo('blog', 'bid');
    }

}