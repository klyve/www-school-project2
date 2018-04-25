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

const DS = DIRECTORY_SEPARATOR;

class VideosController extends Controller {
  
  // @route POST /user/{userid}/video
  public function postVideo(VideosModel $newVideo, Request $req) {


    // @assumption userid matches token
    $token = $req->token();
    $userid = $token->userid;

    $newVideo->userid      = $userid;
    $newVideo->title       = $req->input('title');
    $newVideo->description = $req->input('description');
    $newVideo->filesubtitle  = Hash::md5($userid . $newVideo->title  . microtime() ) . ".srt";
    $newVideo->filethumbnail = $req->input('fileThumbnail');
    $newVideo->filevideo     = $req->input('fileVideo');


    //
    // @TODO Move this into a function in  FILE.php
    //

    // Setup proper filepaths
    $tempdir       = WWW_ROOT.DS."public".DS."temp".DS.$userid;

    $mediaDir      =  WWW_ROOT.DS."public".DS."media";
    $subtitlesDir  = $mediaDir.DS."subtitles".DS.$userid;
    $thumbnailsDir = $mediaDir.DS."thumbnails".DS.$userid;
    $videosDir     = $mediaDir.DS."videos".DS.$userid;

    $thumbTemp = $tempdir.DS.$newVideo->filethumbnail;
    $videoTemp = $tempdir.DS.$newVideo->filevideo;

    $thumbDest    = $thumbnailsDir.DS.$newVideo->filethumbnail;
    $videoDest    = $videosDir.DS.$newVideo->filevideo;
    $subtitleDest = $subtitlesDir.DS.$newVideo->filesubtitle;

    File::makeDirIfNotExist($subtitlesDir);
    File::makeDirIfNotExist($thumbnailsDir);
    File::makeDirIfNotExist($videosDir);


    // Do file operations
    $err = File::moveFile($thumbTemp, $thumbDest);
    if ($err) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to move file");
    }

    $err = File::moveFile($videoTemp, $videoDest);
    if ($err) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to move file");
    }

    $err = File::newFile($subtitleDest);
    if ($err) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to new file");
    }

    if (!rmdir($tempdir)) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to remove users temp directory");
    }
    
    //
    // @TODO Move THE ABOVE into a function in  FILE.php
    //

    
    // Finally save the new video in the database if all fil operations went through.
    $videoid = $newVideo->save();
    if(!$videoid) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to create new video in database");
    }

    $res = ['videoid' => $videoid, 'message' => "Created a new video"];
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

    $userid = $req->token()->userid;
    $deleteVideo = $video->find([
            'id' => $req->param('videoid')
    ]);

    if(!$deleteVideo->id) {
        return Response::statusCode(HTTP_NOT_FOUND);
    }


    //
    // @TODO Move this into a function in  FILE.php
    //

    $mediaDir      =  WWW_ROOT.DS."public".DS."media";
    $subtitlesDir  = $mediaDir.DS."subtitles".DS.$userid;
    $thumbnailsDir = $mediaDir.DS."thumbnails".DS.$userid;
    $videosDir     = $mediaDir.DS."videos".DS.$userid;

    
    $err = File::moveFile($thumbnailsDir.DS.$deleteVideo->filethumbnail, $thumbnailsDir.DS."DELETED-".$deleteVideo->filethumbnail);
    if ($err) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to delete thumbnail file");
    }

    $err = File::moveFile($videosDir.DS.$deleteVideo->filevideo, $videosDir.DS."DELETED-".$deleteVideo->filevideo);
    if ($err) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to delete video file");
    }

    $err = File::moveFile($subtitlesDir.DS.$deleteVideo->filesubtitle, $subtitlesDir.DS."DELETED-".$deleteVideo->filesubtitle);
    if ($err) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to delete video file");
    }

    //
    // @TODO Move THE ABOVE into a function in  FILE.php
    //



    $deleteVideo->deleted_at = date ("Y-m-d H:i:s");
    $deleteVideo->save();


    return Response::statusCode(HTTP_ACCEPTED, "Video accepted for deletion");
  }
}
