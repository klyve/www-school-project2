<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use App\Models\UsersModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;



// HTTP STATUS CODES
const HTTP_OK             = 200;  
const HTTP_ACCEPTED       = 202;  
const HTTP_NOT_IMPLMENTED = 501;


class UsersController extends Controller {


    public function getUser(UsersModel $user, Request $req) {
        return $user->find([
            'id' => $req->token()->userid,
        ]);
  }


    public function putUser(UsersModel $user, Request $req) {

        $userid =  $req->token()->userid;

        $updatedUser = $user->find([
            'id' => $userid
        ]);

        if (!$updatedUser->id) {
            return Response::statusCode(HTTP_NOT_FOUND, "Could not find $userid");
        }

        $updatedUser->name = $req->input('name');
        $updatedUser->email = $req->input('email');
        $updatedUser->save();
        
        return Response::statusCode(HTTP_OK, "Updated User");
  }


  public function deleteUser(UsersModel $user, Request $req) {

    $userid =  $req->token()->userid;
    $deletedUser = $user->find([
            'id' => $userid
    ]);
    if(!$deletedUser->id) {
        return Response::statusCode(HTTP_NOT_FOUND, "Could not find $userid");
    }
    
    $deletedUser->deleted_at = date ("Y-m-d H:i:s");
    $deletedUser->save();

    return Response::statusCode(HTTP_ACCEPTED, "User $userid marked for deletion");
  }

}
