<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use App\Models\VideosModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;

const HTTP_NOT_IMPLEMENTED = 501;

class VideoController extends Controller {
  
  public function postVideo(VideosModel $video, Request $req) {

    return Response::statusCode(HTTP_NOT_IMPLEMENTED, $video);
  }

  public function putVideo(VideosModel $video, Request $req) {

    return Response::statusCode(HTTP_NOT_IMPLEMENTED, $video);
  }

  public function deleteVideo(VideosModel $video, Request $req) {

    return Response::statusCode(HTTP_NOT_IMPLEMENTED, $video);
  }
}
