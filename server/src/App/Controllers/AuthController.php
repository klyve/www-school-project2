<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use \App\Models\UsersModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;

class AuthController extends Controller {

    /**
     * @api {Put} /user/password Change the user's password.
     * @apiName Put user password
     * @apiGroup User
     *
     * @apiParam {String} password Current password of user.
     * @apiParam {String} newpassword New password of user. 
     * 
     * @apiParamExample Put password:
     *      {
     *          password: "oldPassword43*",
     *          newpassword: "newPassword43*"
     *      }
     * 
     *
     * @apiSuccessExample Put password.
     *      HTTP/1.1 204 No content
     *      {
     * 
     *      }
     * 
     * @apiErrorExample {json} password is wrong:
     *     HTTP/1.1 401 Unauthorized
     *     {
     *        code: 401,
     *        error: 2,
     *        message: 'Invalid password',
     *     }
     */
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

    /**
     * @api {Get} /testtoken Update token.
     * @apiName update token
     * @apiGroup User
     * 
     *
     * @apiSuccessExample Put password.
     *      HTTP/1.1 200 OK
     *      {
     *          token : eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiIxIiwiaXNzIjoiS3J1c0tvbnRyb2xsLmNvbSIsImV4cCI6IjIwMTgtMDUtMDQgMTE6NTM6NDUiLCJzdWIiOiIiLCJhdWQiOiIifQ.EbB3SXuKqJJ2Bn6hpAIlZYJJPK010816dtro1z7Nxho
     *      }
     */
    public function updateToken(Request $req, Response $res) {
        $token = Hash::JWT(["key" => 'userid', 'value' => $req->token()->userid]);
        return Response::statusCode(200, ['token' => $token]);
    }

    /**
     * @api {Post} /user/login Login as user.
     * @apiName Login as user
     * @apiGroup User
     * 
     * @apiParam {String} email Email of user to login.
     * @apiParam {String} password User password. 
     * 
     * @apiParamExample {json} Post tag:
     *      {
     *          name: "useremail@kruskontroll.no",
     *          password: "someDifficultPassword43*"
     *      }
     * 
     * @apiSuccessExample Post login.
     *      HTTP/1.1 200 OK
     *      {
     *          data: {
     *              name: "username",
     *              email: "useremail@kruskontroll.no",
     *              usergroup: 1,
     *              token: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiIxIiwiaXNzIjoiS3J1c0tvbnRyb2xsLmNvbSIsImV4cCI6IjIwMTgtMDUtMDQgMTM6NTI6MTEiLCJzdWIiOiIiLCJhdWQiOiIifQ.kRRskrm5F2hg8PjNzxBjLiC9iE_jJ_J9ZY10Nx6wh68"
     *          }
     *      }
     * 
     *  @apiErrorExample {json} Error user does not own video with given id:
     *     HTTP/1.1 200 Ok
     *     {
     *        error: "Username or password is invalid",
     *     }
     * 
     */
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

    public function getUser(UsersModel $user, Request $req) {
        $myuser = $user->find([
            'id' => $req->input('id'),
        ]);
        return Response::statusCode(200, $myUser);
    }

    /**
     * @api {Post} /user/register Register a new user.
     * @apiName Register user
     * @apiGroup User
     * 
     * @apiParam {String} email Email of user to register.
     * @apiParam {String} password User password.
     * 
     * @apiParamExample {json} Post tag:
     *      {
     *          email: "useremail@kruskontroll.no"
     *          password: "someDifficultPassword43*"
     *      }
     * 
     * @apiSuccessExample {json} Post login.
     *      HTTP/1.1 200 OK
     *      {
     *          data: {
     *              name: "username",
     *              email: "useremail@kruskontroll.no",
     *              usergroup: 1,
     *              token: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiIxIiwiaXNzIjoiS3J1c0tvbnRyb2xsLmNvbSIsImV4cCI6IjIwMTgtMDUtMDQgMTM6NTI6MTEiLCJzdWIiOiIiLCJhdWQiOiIifQ.kRRskrm5F2hg8PjNzxBjLiC9iE_jJ_J9ZY10Nx6wh68"
     *          }
     *      }
     */
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

    /**
     * @api {Post} /user/refresh Refresh token identifying user.
     * @apiName Refresh token
     * @apiGroup User
     * 
     * @apiParamExample {json} Post tag:
     *      {
     *          
     *      }
     * 
     * @apiSuccessExample Post login.
     *      HTTP/1.1 200 OK
     *      {
     *          "data": {
     *              "key": "1",
     *              "value": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiIxIiwiaXNzIjoiS3J1c0tvbnRyb2xsLmNvbSIsImV4cCI6IjIwMTgtMDUtMDQgMTM6NTI6MTEiLCJzdWIiOiIiLCJhdWQiOiIifQ.kRRskrm5F2hg8PjNzxBjLiC9iE_jJ_J9ZY10Nx6wh68"
     *          }
     *      }
     * 
     *  @apiErrorExample {json} Error user does not own video with given id:
     *     HTTP/1.1 200 Ok
     *     {
     *        "data": ""
     *     }
     * 
     */
    public function refreshToken(UsersModel $user, Request $req) {
        $myUser = $user->find([
            'id' => $req->token()->userid
        ]);

        // @TODO invalidate existing token
        $myUser->token = Hash::JWT(["key" => 'userid', 'value' => $req->token()->userid]);
        return Response::statusCode(200, $myUser); 
    }

}
