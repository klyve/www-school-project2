<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use App\Models\VideosModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;
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


/*

// @TODO Put this somewhere it makes sense
function getFileParameterOrNull($fileParam) {
  if ( !isset($_FILES[$fileParam]) ) {
      return null;
  }   
  $tmp_filepath = $_FILES[$fileParam]['tmp_name'];

  if (!is_uploaded_file($tmp_filepath)) {
      return null;
  }

  return $_FILES[$fileParam];
}


// @TODO Put this somewhere it makes sense
//  @return null is equal to 500 BAD REQUEST
function uploadFile($tmpfilePath, 
                    $userid, 
                    $videoid, 
                    $collectionName, 
                    $extension) {

    $ROOT = $_SERVER['DOCUMENT_ROOT'];
    if (!file_exists("$ROOT/media")){
        var_dump($ROOT);
        return Response::statusCode(HTTP_INTERNAL_ERROR, 
                                        "Media folder does not exist");
    }

    $userdir = "$ROOT/media/$collectionName/$userid";

    if (!file_exists($userdir)) {
        @mkdir($userdir);
    }

    $didMove = @move_uploaded_file($tmp_filepath, "$userdir/$videoid.$extension");
    if (!$didMove) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, 
                                "Could not move file into $collectionName");
    }

    return null;
}

*/


class VideosController extends Controller {
  
  // @route POST /user/{userid}/video
  public function postVideo(Request $req) {


    // @assumption userid matches token
    $userid = $req->param('userid');

    $newVideo = new VideosModel();
    $newVideo->userid = $userid;
    $newVideo->title  = $req->input('title');
    $newVideo->description = $req->input('description');

    $res = array('videoid' => $newVideo->save($newVideo));

    return Response::statusCode(HTTP_CREATED, $res);
  }

  // @route PUT /user/{userid}/video/{videoid}
  public function putVideo(VideosModel $video, Request $req) {


    // @assumption userid matches token
    $updatedVideo  = $video->find([
        'id' => $req->param('videoid')
    ]);


    if(!$updatedVideo->id) {
        return Response::statusCode(HTTP_NOT_FOUND);
    }

    if($updatedVideo->userid !== $req->param('userid')) {
      return Response::statusCode(HTTP_BAD_REQUEST);
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
