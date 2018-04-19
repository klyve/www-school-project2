<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use App\Models\VideosModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;

// HTTP STATUS CODES
const HTTP_OK            = 200;  // Success and returning content
const HTTP_CREATED       = 201;  // Successfull creation
const HTTP_ACCEPTED      = 202;  // Marked for  deletion, not deleted yet
const HTTP_NOCONTENT     = 204;  // Successfull update
const HTTP_NOTFOUND      = 404; 
const HTTP_NOTIMPLMENTED = 501;
const HTTP_INTERNAL_ERROR = 500;


class VideoController extends Controller {
  
  public function postVideo(Request $req) {

    $newVideo = new VideosModel();
    $newVideo->userid = $req->param('userid');
    $newVideo->title  = $req->input('title');
    $newVideo->description = $req->input('description');

    // @TODO 1. Parse optional file parameters
    //       2. Store files to disk storage.
    //       3. Computed hashed filename. - - jSolsvik 19.04.2018

    $res = array('videoid' => $newVideo->save($newVideo));

    return Response::statusCode(HTTP_CREATED, $res);
  }

  public function putVideo(VideosModel $video, Request $req) {

    $updatedVideo  = $video->find([
        'id' => $req->param('videoid'),
    ]);

    if(!$updatedVideo->id) {
        return Response::statusCode(HTTP_NOTFOUND, []);
    }

    $updatedVideo->title       = $req->input('title');
    $updatedVideo->description = $req->input('description');
    $videoid = $updatedVideo->save();

    // @TODO - figure out why videoid === NULL even though the updated happend 
    //            successfully.??  - jSolsvik 19.04.2018
    /*
    if ($updatedVideo->id !== $videoid) {
        print_r([$videoid, $updatedVideo->id]);
        return Response::statusCode(HTTP_INTERNAL_ERROR, []);
    }
    */
    
    return Response::statusCode(HTTP_NOCONTENT, $updatedVideo);
  }

  public function deleteVideo(VideosModel $video, Request $req) {

    return Response::statusCode(HTTP_NOTIMPLMENTED, 0);
  }
}
