<?php namespace MVC\Core;

class View {

    
    public static function load($filename, $params = []) {
        extract($params);
        if(file_exists('app/view/'.$filename.'.php')) {
            require 'app/view/'.$filename.'.php';
        }else {
            die("View not found");
        }
    }
}