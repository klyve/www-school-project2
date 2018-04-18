<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use App\Models\TestModel;
use \MVC\Helpers\Hash;

class SimpleJSONController extends Controller {


  public function hello() {
    $token = Hash::JWT(["key" => 'userid', 'value' => 50]);
    $string = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOjUwLCJpc3MiOiJLcnVzS29udHJvbGwuY29tIiwiZXhwIjoiMjAxOC0wNC0xOSAxMzo0MzozNyIsInN1YiI6IiIsImF1ZCI6IiJ9.pTXACfyriYqofEh6UaI6eUFxlm--wghPBwhVn4X03SY";
    if(Hash::verifyJWT($string)) {
      $data = Hash::getJWT($string);
    }
    return $token;

    //return "Hello world";
  }

  public function sayHelloJSON(Request $req, TestModel $testModel) {

  }



}
