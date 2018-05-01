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

    public function putRating(Request $req, 
                               VideosModel $videos,
                               RatingsModel $ratings) {
        
        $userid = $req->token()->userid;
        $videoid = $req->param('videoid');
        $rating = $req->input('rating');

        $existingVideo = $videos->find([
            'id' => $videoid 
        ]);

        if(!$existingVideo->id) {
            return new Error(ErrorCode::get('not_found'));
        }

        // Find existing rating if it exists
        $existingRating = $ratings->find([
            'userid' => $userid,
            'videoid' => $videoid
        ]);

        // If rating already exist, update rating
        if ($existingRating->id) {
            $existingRating->rating = $rating;
            $existingRating->deleted_at = null;
            $existingRating->save();
            return Response::statusCode(HTTP_OK, "Rating created");            
        }

        $ratings->userid = $userid;
        $ratings->videoid = $videoid;
        $ratings->rating = $req->input('rating');
        
        $lastinsertid = $results->save();

        if (!$lastinsertid) {
            return new Error(ErrorCode::get('sql_insert_error'));
        }

        return Response::statusCode(HTTP_OK, "Rating created");
    }

    public function deleteRating(Request $req, 
                                 VideosModel $videos,
                                 RatingsModel $ratings) {

        $userid = $req->token()->userid;
        $videoid = $req->param('videoid');

        $existingRating = $ratings->find([
            'userid' => $userid,
            'videoid' => $videoid
        ]);

        if (!$existingRating->id) {
            return new Error(ErrorCode::get('not_found'));
        }

        $existingRating->deleted_at = date("Y-m-d H:i:s");
        $existingRating->save();

        return Response::statusCode(HTTP_ACCEPTED, "Rating deleted");        
    }
}