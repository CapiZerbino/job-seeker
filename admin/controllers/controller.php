<?php

abstract class Controller {
    protected $data = array();
    protected $view = NULL;
    protected $head = array('title' => NULL, 'description' => NULL);

    abstract function process($param);

    public function renderView() {
        if($this->view) {
            extract($this->data);
            require('../views/' .$this->view .".php");
        }
    }

    public function redirect($url) {
        header("Location:/$url");
        header("Connection: close");
        exit();
    }
}
?>