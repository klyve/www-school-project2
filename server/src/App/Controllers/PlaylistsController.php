<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Core\Language;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use App\Models\PlaylistsModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;
use \MVC\Helpers\File;
use \Datetime;

// HTTP STATUS CODES
const HTTP_OK            = 200;  // Success and returning content
const HTTP_CREATED       = 201;  // Successfull creation
const HTTP_ACCEPTED      = 202;  // Marked for  deletion, not deleted yet


class PlaylistsController extends Controller {
    
    // @assumption user has been athenticated
    // @assumption playlistdata has already been validated

    /**
     * @api {Post} /playlist Create playlist.
     * @apiName Create playlist.
     * @apiGroup Playlist
     * @apiPermission teacher
     *
     * @apiParam {String} title Title of playlist to create.
     * @apiParam {String} description Description of playlist to create.
     * 
     * @apiParamExample Create playlist
     *      {
     *          title: "Math playlist",
     *          description: "Quadratic expantion of imaginari numbers in the complex plain"
     *      }
     * 
     * @apiSuccess {Object} data Container for response.
     * @apiSuccess {Number} data.id Id of created playlist.
     * @apiSuccess {String} data.message Human readable respons description.
     * 
     * @apiSuccessExample Created playlist
     *      HTTP/1.1 201 Created
     *      {
     *          data: {
     *              id: 1,
     *              message: "Resource created"
     *          }
     *      }
     * 
     * @apiUse errorCode
     * 
     * @apiErrorExample {json} Error SQL
     *     HTTP/1.1 500 Not Found
     *     {
     *        code: 500,
     *        error: 4,
     *        message: "Server had an error when trying to create playlist"
     *     }
     */
    public function postPlaylist(PlaylistsModel $newPlaylist, Request $req) {

        $newPlaylist->userid = $req->token()->userid;
        $newPlaylist->title  = $req->input('title');
        $newPlaylist->description = $req->input('description');
        $lastinsertid = $newPlaylist->save();

        if (!$lastinsertid) {
            return new Error(ErrorCode::get('playlist.sql_insert_error'));
        }


        $res = ['id' => $lastinsertid, 'message' => Language::get('success.created')];
        return Response::statusCode(HTTP_CREATED, $res);
    }


    // @assumption playlistdata has already been validated
    //              playlistid's validated'

    /**
     * @api {Post} /playlist/:playlistid Change playlist.
     * @apiName Change playlist.
     * @apiGroup Playlist
     * @apiPermission owner
     *
     * @apiParam {Number} playlistid Id of playlist to change.
     * @apiParam {Number} id Id of playlist to change.
     * @apiParam {String} title New title of playlist.
     * @apiParam {String} description New description of playlist.
     * 
     * @apiParamExample Change playlist
     *      {
     *          title: "Math playlist",
     *          description: "Quadratic expantion of imaginari numbers in the complex plain"
     *      }
     * 
     * @apiSuccess {Object} data Container for response.
     * @apiSuccess {String} data.message Human readable respons description.
     * 
     * @apiSuccessExample Changed playlist
     *      HTTP/1.1 200 OK
     *      {
     *          data: {
     *              message: "Resource updated"
     *          }
     *      }
     * 
     * @apiUse errorCode
     * 
     * @apiErrorExample {json} id mismatch
     *     HTTP/1.1 400 Bad Request
     *     {
     *        code: 400,
     *        error: 3,
     *        message: "Id mismatch. The id in the url and the body does not match.",
     *     }
     * 
     * @apiErrorExample {json} not found
     *     HTTP/1.1 404 Not Found
     *     {
     *        code: 404,
     *        error: 3,
     *        message: "Could not find playlist with given playlistid and userid"
     *     }
     */
    public function putPlaylist(PlaylistsModel $playlist, Request $req) {

        if ( $req->input('id') !==  $req->param('playlistid')) {
            return new Error(ErrorCode::get('id_mismatch'));
        }

        $playlistid = $req->input('id');
        $userid     = $req->token()->userid;

        $foundPlaylist = $playlist->find([
            'id' => $playlistid,
            'userid' => $userid
        ]);

        if (!$foundPlaylist->id) {
            return new Error(ErrorCode::get('playlist.not_found'));
        }

        $foundPlaylist->title = $req->input('title');
        $foundPlaylist->description = $req->input('description');
        $foundPlaylist->save();


        $res = ['message' => Language::get('success.updated')];
        return Response::statusCode(HTTP_OK, $res);
    }


    /**
     * @api {Post} /playlist/:playlistid Delete playlist.
     * @apiName Delete playlist.
     * @apiGroup Playlist
     * @apiPermission owner
     *
     * @apiParam {Number} playlistid Id of playlist to change.
     * 
     * @apiSuccess {Object} data Container for response.
     * @apiSuccess {String} data.message Human readable respons description.
     * 
     * @apiSuccessExample Deleted playlist
     *      HTTP/1.1 202 Accepted
     *      {
     *          data: {
     *              message: "esource marked for deletion"
     *          }
     *      }
     * 
     * @apiUse errorCode
     * 
     * @apiErrorExample {json} not found
     *     HTTP/1.1 404 Not Found
     *     {
     *        code: 404,
     *        error: 3,
     *        message: "Could not find playlist with given playlistid and userid"
     *     }
     */
    public function deletePlaylist(PlaylistsModel $playlist, Request $req) {

        $playlistid = $req->param('playlistid');
        $userid = $req->token()->userid;

        $myplaylist = $playlist->find([
            'id' => $playlistid,
            'userid' => $userid
        ]);

        if (!$myplaylist->id) {
            return new Error(ErrorCode::get('playlist.not_found'));
        }

        $myplaylist->deleted_at = date("Y-m-d H:i:s");
        $myplaylist->save();
        
        $res = ['message' => Language::get('success.deleted')];
        return Response::statusCode(HTTP_ACCEPTED, $res);
    }
}
