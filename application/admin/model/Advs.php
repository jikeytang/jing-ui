<?php

namespace app\admin\model;

use think\Model;

class Advs extends Common {

    protected $_validate = array(
        array('title', 'require', '标题不能为空'),
        array('content', 'require', '标题不能为空'),
    );

}