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

        // Find max position of active videos in playlist
        $allvideos = $playlistVideos->all([
            'playlistid' =>  $playlistid]);
        
        $max = 0;
        foreach($allvideos as $vid) {
            if(is_null($vid->deleted_at)) {
                $max = max(array($max, $vid->position));
            }
        }
        $max += 1;

        $playlistVideos->playlistid = $playlistid;
        $playlistVideos->videoid    = $videoid;
        
        $playlistVideos->position = $max;
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
        $playlistid = $req->param('playlistid');
        $id         = $req->param('id');


        // @NOTE - checking if user owns the playlist
        $userplaylist = $playlists->find([
            'id'     => $playlistid,
            'userid' => $userid
        ]);

        if(!$userplaylist->id) {
            return new Error(ErrorCode::get('not_found'));
        }

        $foundPlaylistVideo = $playlistVideos->find([
            'id'    => $id
        ]);
        
        if(!$foundPlaylistVideo->id) {
            return new Error(ErrorCode::get('not_found'));
        }

        $foundPlaylistVideo->deleted_at = date("Y-m-d H:i:s");
        $foundPlaylistVideo->save();

        $res = ['message' => Language::get('success.deleted')];
        return Response::statusCode(HTTP_ACCEPTED, $res);
    }


    public function reorderPlaylist(Request $req,
                                    PlaylistsModel $playlists,
                                    PlaylistVideosModel $playlistVideos) 
    {
        $userid     = $req->token()->userid;
        $playlistid = $req->param('playlistid');
        $reordering = $req->input('reordering');

        // @NOTE - checking if user owns the playlist
        $userplaylist = $playlists->find([
            'id'     => $playlistid,
            'userid' => $userid
        ]);

        if(!$userplaylist->id) {
            return new Error(ErrorCode::get('not_found'));
        }

        // @TODO make this for-loop into a transaction, which can be rolled back if something ffails - JSolsvik 29.04.18
        // @assumption that all positions have been validated
        foreach($reordering as $r) {
            $playlistVideo = $playlistVideos->find(['id' => $r['id']]);
            if (!$playlistVideo->id) {
                return new Error(ErrorCode::get('not_found'));
            }

            $playlistVideo->position = $r['position'];
            $playlistVideo->save();
        }

        return Response::statusCode(200, "Playlist was reordered");
    }

}
