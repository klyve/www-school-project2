<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use App\Models\TestModel;

class SimpleJSONController extends Controller {


  public function hello() {
    echo "Hello world";
  }

  public function sayHelloJSON(Request $req, TestModel $testModel) {

  }



}
