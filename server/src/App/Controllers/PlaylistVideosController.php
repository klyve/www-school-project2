<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Core\Language;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;
use \MVC\Helpers\File;
use \Datetime;

use App\Models\VideosModel;
use App\Models\PlaylistsModel;
use App\Models\PlaylistVideosModel;

// HTTP STATUS CODES
const HTTP_OK            = 200;  // Success and returning content
const HTTP_CREATED       = 201;  // Successfull creation
const HTTP_ACCEPTED      = 202;  // Marked for  deletion, not deleted yet




class PlaylistVideosController extends Controller {

    public function postPlaylistVideo(Request $req, 
                                      PlaylistsModel $playlists, 
                                      VideosModel $videos, 
                                      PlaylistVideosModel $playlistVideos)
    {
        $userid     = $req->token()->userid;
        $playlistid = $req->input('playlistid');
        $videoid    = $req->input('videoid');


        // @TODO - HAndle this logic with middleware
        if( ($playlistid != $req->param('playlistid')) ) {
            var_dump($playlistid, $req->param('playlistid'));
            return new Error(ErrorCode::get('id_mismatch'));
        }

        $playlists->find([
            'id'     => $playlistid,
            'userid' => $userid
        ]);

        $videos->find([
            'id'     => $videoid,
            'userid' => $userid
        ]);

        if ((!$playlists->id) 
        ||  (!$videos->id)) {
            return new Error(ErrorCode::get('not_found'));
        }


        $playlistVideos->playlistid = $playlistid;
        $playlistVideos->videoid    = $videoid;
        $playlistVideos->position   = $req->input('position');
        $lastInsertId = $playlistVideos->save();

        if (!$lastInsertId) {
            return new Error(ErrorCode::get('sql_insert_error'));
        }

        $res = ['id' => $lastInsertId, 'message' => Language::get('success.created')];
        return Response::statusCode(HTTP_CREATED, $res);

    }

    public function deletePlaylistVideo(Request $req,
                                        PlaylistsModel $playlists,
                                        PlaylistVideosModel $playlistVideos)
    {
        $userid     = $req->token()->userid;
        $playlistid = $req->input('playlistid');

        // @TODO - HAndle this logic with middleware
        if( ($playlistid != $req->param('playlistid') )) {
            return new Error(ErrorCode::get('id_mismatch'));
        }

        // @NOTE - checking if user owns the playlist
        $userplaylist = $playlists->find([
            'id'     => $playlistid,
            'userid' => $userid
        ]);

        if(!$userplaylist->id) {
            return new Error(ErrorCode::get('not_found'));
        }

        $foundPlaylistVideo = $playlistVideos->find([
            'id'    => $req->input('id')
        ]);

        
        if(!$foundPlaylistVideo->id) {
            return new Error(ErrorCode::get('not_found'));
        }

        $foundPlaylistVideo->deleted_at = date("Y-m-d H:i:s");
        $foundPlaylistVideo->save();

        $res = ['message' => Language::get('success.deleted')];
        return Response::statusCode(HTTP_ACCEPTED, $res);
    }
}
