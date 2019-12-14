<?php
/**
 * Created by PhpStorm.
 * User: jikey
 * Date: 2018-4-1
 * Time: 20:55
 */

namespace app\home\model;

use think\Model;

class Link extends Common {
    public function getLinks(){
        return $this->field('title,url,introduce')->order('ord')->select();
    }
}