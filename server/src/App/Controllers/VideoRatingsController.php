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

    /**
     * @api {post} /video/:videoid/rate Change users rating of video.
     * @apiName Change users rating of video.
     * @apiGroup Video
     * @apiPermission user
     *
     * @apiParam {Number} videoid video's unique ID.
     * @apiParam {Number} rating Rating of the video. 
     * 
     * @apiParamExample {json} put rating:
     *      {
     *          rating: 1
     *      }
     * 
     * @apiUse data
     * 
     * @apiSuccessExample put rating.
     *      HTTP/1.1 200 OK
     *      {
     *          data: "Rating created"
     *      }
     * 
     * @apiUse errorCode
     * 
     * @apiErrorExample {json} Error not found
     *     HTTP/1.1 404 Not Found
     *     {
     *        code: 404,
     *        error: 1,
     *        message: 'Could not find data with given parameters',
     *     }
     * 
     * @apiErrorExample {json} Error SQL
     *     HTTP/1.1 500 Conflict
     *     {
     *        code: 500,
     *        error: 2,
     *        message: 'Server had an error when trying to create resource in the datbase',
     *     }
     */
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


    /**
     * @api {post} /video/:videoid/rate Delete users rating of video.
     * @apiName Delete users rating of video.
     * @apiGroup Video
     * @apiPermission user
     *
     * @apiParam {Number} videoid video's unique ID.
     * 
     * @apiUse data
     * 
     * @apiSuccessExample delte accepted.
     *      HTTP/1.1 202 Accepted
     *      {
     *          data: "Rating deletedd"
     *      }
     * 
     * @apiUse errorCode
     * 
     * @apiErrorExample {json} Error not found
     *     HTTP/1.1 404 Not Found
     *     {
     *        code: 404,
     *        error: 1,
     *        message: 'Could not find data with given parameters',
     *     }
     */
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