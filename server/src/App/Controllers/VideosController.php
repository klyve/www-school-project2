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
use \App\Models\TempVideosModel;

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
  public function postVideo(VideosModel $newVideo,
                            TempVideosModel $tempVideos,
                            Request $req) {

    $userid = $req->token()->userid;

    $newVideo->userid      = $userid;
    $newVideo->title       = $req->input('title');
    $newVideo->description = $req->input('description');
    $temp_videoid          = $req->input('temp_videoid');

    $tempVideo = $tempVideos->find([
        'id' => $temp_videoid,
        'userid' => $userid
    ]);
    if (!$tempVideo->id) {
        return Response::statusCode(HTTP_BAD_REQUEST, "Could not find tempvideo");
    }

    // Setup proper filepaths
    $tempdir       = WWW_ROOT.DS."public".DS."temp".DS.$userid;
    $mediaDir      =  WWW_ROOT.DS."public".DS."media";


    // Move Video from temp folder to destination folder
        {
            $videosDir     = $mediaDir.DS."videos".DS.$userid;
            File::makeDirIfNotExist($videosDir);
            $videoTempSource  = $tempdir  .DS. $tempVideo->fname;
            $videoDestination = $videosDir.DS. $tempVideo->fname;


            $err = File::moveFile($videoTempSource, $videoDestination);
            if ($err) {
                return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to move file");
            }
        }


    // Get Thumbnail from form first, then move file from temp to
            $thumbnailFormFile = $req->getFile('thumbnail');
            if (!$thumbnailFormFile) {
                return Response::statusCode(HTTP_BAD_REQUEST, "Could not find thumbnail in form");
            }
            
            $thumbnailsDir = $mediaDir.DS."thumbnails".DS.$userid;
            File::makeDirIfNotExist($thumbnailsDir);

            $thumbFilename = File::moveFormFile($thumbnailFormFile, $thumbnailsDir, "png");
            if (!$thumbFilename) {
                return Response::statusCode(HTTP_INTERNAL_ERROR, "Could not move thumbnail");
            }


    // Get Subtitle from form first, then move file from temp to
            $subtitleFormFile = $req->getFile('subtitle');
            if (!$subtitleFormFile) {
                return Response::statusCode(HTTP_BAD_REQUEST, "Could not find subtitle in form");
            }

            $subtitlesDir = $mediaDir.DS."subtitles".DS.$userid;
            File::makeDirIfNotExist($subtitlesDir);

            $subtitleFilename = File::moveFormFile($subtitleFormFile, $subtitlesDir, "srt");
            if (!$subtitleFilename) {
                return Response::statusCode(HTTP_INTERNAL_ERROR, "Could not move subtitle");
            }    

    
    // Finally save the new video in the database if all fil operations went through.
    $newVideo->filevideo = $tempVideo->fname;
    $newVideo->filesubtitle = $subtitleFilename;
    $newVideo->filethumbnail = $thumbFilename;
    $videoid = $newVideo->save();
    if(!$videoid) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Failed to create new video in database");
    }

    // Safely delete temp video
    $tempVideo->deleted_at = date ("Y-m-d H:i:s");
    $tempVideo->save();

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
