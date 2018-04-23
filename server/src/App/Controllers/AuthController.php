<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use \App\Models\UsersModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;

class AuthController extends Controller {

  public function putPassword(UsersModel $user, Request $req) {
    $token = $req->token();
    $myUser = $user->find([
      'id' => $token->userid,
    ]);

    if(!Hash::verify($req->input('password'), $myUser->password)) {
      return new Error(ErrorCode::get('user.invalid_password'));
    }
    $myUser->password = Hash::password($req->input('newpassword'));
    $myUser->save();

    return Response::statusCode(204);
  }

  public function postLogin(UsersModel $user, Request $req, Response $res) {
      $myUser = $user->find([
        'email' => $req->input('email'),
      ]);

      if(!$myUser->id || !Hash::verify($req->input('password'), $myUser->password)) {
        return [
          "error" => "Username or password is invalid",
        ];
      }
      $myUser->token = Hash::JWT(["key" => 'userid', 'value' => $myUser->id]);
      
      return Response::statusCode(200, $myUser);
  }

  public function postRegister(UsersModel $user, Request $req) {
    $user->find([
      'email' => $req->input('email')
    ]);

    if($user->id) {
      return new Error(ErrorCode::get('user.email_exists'));
    }

    $id = $user->create(array_merge($req->only([
      'name', 'email',
    ]), ['password' => Hash::password($req->input('password'))]));
    
    $token = Hash::JWT(["key" => 'userid', 'value' => $id]);
    $user->token = $token;


    return Response::statusCode(200, $user);
  }

}
