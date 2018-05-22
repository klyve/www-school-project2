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

    /**
     * @api {Get} /user Get current active user.
     * @apiName Get current active user.
     * @apiGroup User
     * @apiPermission user
     *
     * @apiSuccess {object} data Container for respons.
     * @apiSuccess {String} data.name Name of user.
     * @apiSuccess {string} data.email Email of user.
     * @apiSuccess {Number} data.usergroup Access level of the user.
     * @apiSuccess {Number} data.requestedgroup Requested access level of the user.
     * @apiParam {String} data.token Identifier of the user.
     * 
     * @apiSuccessExample got user
     *      HTTP/1.1 200 OK
     *      {
     *          data: {
     *              name: "Kruskontroll",
     *              email: "Kruskontroll@localhost.com",
     *              usergroup: "1",
     *              requestedgroup: "2"
     *          }
     *      }
     */
    public function getUser(UsersModel $user, Request $req) {
        return $user->find([
            'id' => $req->token()->userid,
        ]);
    }

    /**
     * @api {Put} /user/:id/group Give new group to user.
     * @apiName Give new group to user.
     * @apiGroup User
     * @apiPermission admin
     *
     * @apiParam {Number} id Identifier for the user.
     * @apiParam {Number} group New group for the user.
     * 
     * @apiParamExample get user
     *      {
     *          group: 2
     *      }
     * 
     * @apiSuccess {object} data Container for respons.
     * @apiSuccess {String} data.name Name of user.
     * @apiSuccess {string} data.email Email of user.
     * @apiSuccess {Number} data.usergroup Access level of the user.
     * @apiSuccess {Number} data.requestedgroup Requested access level of the user.
     * @apiParam {String} data.token Identifier of the user.
     * 
     * @apiSuccessExample updated group
     *      HTTP/1.1 200 OK
     *      {
     *          data: {
     *              name: "Kruskontroll",
     *              email: "Kruskontroll@localhost.com",
     *              usergroup: "1",
     *              requestedgroup: "2"
     *          }
     *      }
     * 
     * @apiUse data
     * 
     * @apiErrorExample {json} Error Not Found
     *     HTTP/1.1 404 Not Found
     *     {
     *        data: "Could not find 1"
     *     }
     */
    public function updateGroup(UsersModel $user, Request $req) {
        $userid = $req->param('id');

        $user->find([
            'id' => $userid
        ]);
        if(!$user->id) {
            return Response::statusCode(HTTP_NOT_FOUND, "Could not find $userid");
        }
        $user->usergroup = $req->input('group');
        $user->save();

        return Response::statusCode(HTTP_OK, $user);
    }

    /**
     * @api {Put} /user/ Edit current active user.
     * @apiName Edit current active user.
     * @apiGroup User
     * @apiPermission user
     *
     * @apiParam {String} name New name for the user.
     * @apiParam {String} email New email for the user.
     * 
     * @apiParamExample get user
     *      {
     *          group: 2
     *      }
     * 
     * @apiUse data
     * 
     * @apiSuccessExample updated user
     *      HTTP/1.1 200 OK
     *      {
     *          data: "Updated User"
     *      }
     * 
     * @apiUse data
     * 
     * @apiErrorExample {json} Error Not Found
     *     HTTP/1.1 404 Not Found
     *     {
     *        data: "Could not find 1"
     *     }
     */
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

    /**
     * @api {Delete} /user/ Delete current active user.
     * @apiName Delete current active user.
     * @apiGroup User
     * @apiPermission user
     * 
     * @apiUse data
     * 
     * @apiSuccessExample deleted user
     *      HTTP/1.1 202 Accepted
     *      {
     *          data: "User 1 marked for deletion"
     *      }
     * 
     * @apiUse data
     * 
     * @apiErrorExample {json} Error Not Found
     *     HTTP/1.1 404 Not Found
     *     {
     *        data: "Could not find 1"
     *     }
     */
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
