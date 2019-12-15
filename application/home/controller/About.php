<?php

namespace app\home\controller;

class About extends Common {
    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        return $this->fetch();
    }

}
