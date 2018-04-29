<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;

use \App\Models\VideosModel;
use \App\Models\RatingsModel;


// HTTP STATUS CODES
const HTTP_OK            = 200;  // Success and returning content
const HTTP_CREATED       = 201;  // Successfull creation
const HTTP_ACCEPTED      = 202;  // Marked for  deletion, not deleted yet


class VideoRatingsController extends Controller {

    public function postRating(Request $req, 
                               VideosModel $videos,
                               RatingsModel $ratings) {
                                   
        return Response::statusCode(HTTP_OK, "Not implemented");
    }

    public function deleteRating(Request $req, 
                                 VideosModel $videos,
                                 RatingsModel $ratings) {

        return Response::statusCode(HTTP_OK, "Not implemented");        
    }
}