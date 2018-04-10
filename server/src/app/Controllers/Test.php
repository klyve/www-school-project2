<?php namespace App\Controllers;

use MVC\Core\Controller;

class Test extends Controller {
    public function __construct() {
        die("WORKS");
    }

    public function hello() {
        die("Hello world from TEst");
    }
}