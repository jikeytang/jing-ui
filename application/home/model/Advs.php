<?php

namespace app\home\model;

class Advs extends Common {

    public static function advs() {
        return db('advs')->field('title,link,smallimg,ord')->order('ord')->select();
    }
}