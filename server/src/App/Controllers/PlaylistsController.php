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
            return new Error(ErrorCode::get('playlist.sql_insert_error'));
        }


        $res = ['id' => $lastinsertid, 'message' => Language::get('success.created')];
        return Response::statusCode(HTTP_CREATED, $res);
    }


    // @assumption playlistdata has already been validated
    //              playlistid's validated
    public function putPlaylist(PlaylistsModel $playlist, Request $req) {

        if ( $req->input('id') !==  $req->param('playlistid')) {
            return new Error(ErrorCode::get('playlist.id_mismatch'));
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

        // @ERROR Cannot save the updated playlist
        $myplaylist->deleted_at = date("Y-m-d H:i:s");
        $myplaylist->save(); // <--- SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens

        $res = ['message' => Language::get('success.deleted')];
        return Response::statusCode(HTTP_ACCEPTED, $res);
    }
}
