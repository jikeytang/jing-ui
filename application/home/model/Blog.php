<?php

namespace app\home\model;

use think\Model;

class Blog extends Common {

    public function columns() {
        return $this->belongsTo('Columns', 'catid');
    }

    public function comment() {
        return $this->hasMany('Comment', 'bid');
    }

    public function index() {
//        $this->hasMany('Comment', 'bid')->fields('username,email,inputtime,content');
//        return $this->belongsTo('Columns', 'catid')->fields('colId,colTitle,colPid,description');
        // return $this->morphMany('Comment', 'Columns');
        // return $this->belongsTo('Columns', 'catid')->fields('colId,colTitle,colPid,description');
        // return $this->hasMany('Comment', 'bid')->fields('username,email,inputtime,content');
    }

//    public function Comment() {
//        return $this->hasMany('Comment', 'bid')->fields('username,email,inputtime,content');
//    }
}