<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use App\Models\VideosModel;
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


class VideosController extends Controller {
  
  // @route POST /user/{userid}/video
  public function postVideo(Request $req) {


    // @assumption userid matches token
    $token = $req->token();
    $userid = $token->userid;

    $newVideo              = new VideosModel();
    $newVideo->userid      = $userid;
    $newVideo->title       = $req->input('title');
    $newVideo->description = $req->input('description');
    $newVideo->filesubtitle  = Hash::md5($userid . $newVideo->title  . microtime() ) . ".srt";
    $newVideo->filethumbnail = $req->input('fileThumbnail');
    $newVideo->filevideo     = $req->input('fileVideo');
    $videoid = $newVideo->save();
 

    $tempdir      = WWW_ROOT."/public/temp/$userid";
    $destdirSub   = WWW_ROOT."/public/media/subtitles/$userid";
    $destdirThumb = WWW_ROOT."/public/media/thumbnails/$userid";
    $destdirVideo = WWW_ROOT."/public/media/videos/$userid";

    File::makeDirIfNotExist($destdirSub);
    File::makeDirIfNotExist($destdirThumb);
    File::makeDirIfNotExist($destdirVideo);

    $tempfileThumb = "$tempdir/$newVideo->filethumbnail";
    $tempfileVideo = "$tempdir/$newVideo->filevideo";

    $err = File::moveFile($tempfileThumb, "$destdirThumb/$newVideo->filethumbnail");
    if ($err) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to move file");
    }

    $err = File::moveFile($tempfileVideo, "$destdirVideo/$newVideo->filevideo");
    if ($err) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to move file");
    }

    $err = File::newFile("$destdirSub/$newVideo->filesubtitle");
    if ($err) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to new file");
    }

    rmdir($tempdir);

    $res = array('videoid' => $videoid, 'message' => "Created a new video");
    return Response::statusCode(HTTP_CREATED, $res);
  }

  // @route PUT /user/{userid}/video/{videoid}
  public function putVideo(VideosModel $video, Request $req) {


    // @assumption userid matches token
    $updatedVideo  = $video->find([
        'id' => $req->param('videoid'), 
        'userid' => $req->token()->userid
    ]);

    if(!$updatedVideo->id) {
        return Response::statusCode(HTTP_NOT_FOUND, "Could not find video on userid");
    }

    $updatedVideo->title       = $req->input('title');
    $updatedVideo->description = $req->input('description');
    $updatedVideo->save();

    // @TODO - figure out why videoid === NULL even though the updated happend 
    //            successfully.??  - jSolsvik 19.04.2018
    /*
    if ($updatedVideo->id !== $videoid) {
        print_r([$videoid, $updatedVideo->id]);
        return Response::statusCode(HTTP_INTERNAL_ERROR, []);
    }
    */
    return Response::statusCode(HTTP_OK, "Updated video successfully");
  }

  // @route DELETE /user/{userid}/video/{videoid}
  public function deleteVideo(VideosModel $video, Request $req) {

    $deleteVideo = $video->find([
            'id' => $req->param('videoid')
    ]);
    if(!$deleteVideo->id) {
        return Response::statusCode(HTTP_NOT_FOUND);
    }
    
    $deleteVideo->deleted_at = date ("Y-m-d H:i:s");
    $deleteVideo->save();


    return Response::statusCode(HTTP_ACCEPTED, "Video accepted for deletion");
  }
}
