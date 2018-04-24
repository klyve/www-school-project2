<?php namespace App\Controllers;

use \MVC\Core\Controller;
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
const HTTP_NO_CONTENT     = 204;  // Successfull update
const HTTP_BAD_REQUEST    = 400;
const HTTP_NOT_FOUND      = 404; 
const HTTP_NOT_IMPLMENTED = 501;
const HTTP_INTERNAL_ERROR = 500;


class PlaylistsController extends Controller {
    
    // @assumption user has been athenticated
    // @assumption playlistdata has already been validated
    public function postPlaylist(Request $req) {

        $newPlaylist = new PlaylistsModel();
        $newPlaylist->userid = $req->token()->userid;
        $newPlaylist->title  = $req->input('title');
        $newPlaylist->description = $req->input('description');
        $lastinsertid = $newPlaylist->save();

        if (!$lastinsertid) {
            return Response::statusCode(HTTP_INTERNAL_ERROR, "Server could not created your playlist");
        }


        $res = ['id' => $lastinsertid, 'msg' => 'Created playlist'];
        return Response::statusCode(HTTP_CREATED, $res);
    }


    // @assumption playlistdata has already been validated
    public function putPlaylist(PlaylistsModel $playlist, Request $req) {


        if ( $req->input('id') !==  $req->param('playlistid')) {
            return Response::statusCode(HTTP_BAD_REQUEST, "Playlistid mismatch");
        }

        $myplaylist = $playlist->find([
            'userid' => $req->token()->userid,
            'id' => $req->input('id'),
        ]);

        if (!$myplaylist->id) {
            return Response::statusCode(HTTP_NOT_FOUND, "Could not find playlist on userid");
        }

        $myplaylist->title = $req->input('title');
        $myplaylist->description = $req->input('description');

        $res = ['msg' => 'Updated playlist'];
        return Response::statusCode(HTTP_OK, $res);
    }

    public function deletePlaylist(PlaylistsModel $playlist, Request $req) {
        return Response::statusCode(HTTP_ACCEPTED, "Playlist marked for deletion");
    }
}
