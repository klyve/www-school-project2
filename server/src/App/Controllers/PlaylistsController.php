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
    public function postPlaylist(PlaylistsModel $newPlaylist, Request $req) {

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
    //              playlistid's validated
    public function putPlaylist(PlaylistsModel $playlist, Request $req) {

        if ( $req->input('id') !==  $req->param('playlistid')) {
            return Response::statusCode(HTTP_BAD_REQUEST, "Playlistid mismatch");
        }


        $playlistid = $req->input('id');
        $userid     = $req->token()->userid;

        $foundPlaylist = $playlist->find([
            'id' => $playlistid,
            'userid' => $userid
        ]);

        if (!$foundPlaylist->id) {
            return Response::statusCode(HTTP_NOT_FOUND, "Could not find playlist on userid");
        }

        $playlistTag->update([
            'id' => $playlistid,
            'userid' => $userid
        ],[ 
           'title' => $req->input('title'),
           'description' => $req->input('description')
        ]);

        $res = ['msg' => 'Updated playlist'];
        return Response::statusCode(HTTP_OK, $res);
    }

    public function deletePlaylist(PlaylistsModel $playlist, Request $req) {

        $playlistid = $req->param('playlistid');
        $userid = $req->token()->userid;

        $myplaylist = $playlist->find([
            'id' => $playlistid,
            'userid' => $userid
        ]);

        if (!$myplaylist->id) {
            return Response::statusCode(HTTP_NOT_FOUND, "Could not find playlist on userid");
        }

        // @ERROR Cannot save the updated playlist
        $myplaylist->deleted_at = date("Y-m-d H:i:s");
        $myplaylist->save(); // <--- SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens

        $res = ['msg' => "Playlist marked for deletion"];
        return Response::statusCode(HTTP_ACCEPTED, $res);
    }
}
