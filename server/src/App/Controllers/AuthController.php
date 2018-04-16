<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use App\Models\UsersModel;
use \MVC\Helpers\Hash;

class AuthController extends Controller {

  public function putPassword(UsersModel $user, Request $req) {

    $myUser = $user->find([
      'id' => $req->input('id'),
    ]);

    if(!$myUser->id || !Hash::verify($req->input('oldpassword'), $myUser->password)) {
      return [
        "error" => "Username or password is invalid",
        $myUser
      ];
    }

    $newpassword       = $req->input('newpassword');
    $myUser->password = Hash::password($newpassword);
    $myUser->save();

    return $myUser;
  }

  public function postLogin(UsersModel $user, Request $req) {
      $myUser = $user->find([
        'email' => $req->input('email'),
      ]);

      if(!$myUser->id || !Hash::verify($req->input('password'), $myUser->password)) {
        return [
          "error" => "Username or password is invalid",
        ];
      }

      // TODO: Generate jwt token and send to user
      return $myUser;

  }

  public function getUser(UsersModel $user, Request $req) {
    return $user->find([
      'id' => $req->input('id'),
    ]);
  }

  public function postRegister(UsersModel $user, Request $req) {
    // TODO: ADD validation
    $user->create(array_merge($req->only([
      'name', 'email',
    ]), ['password' => Hash::password($req->input('password'))]));

    return $user;
  }



}
