<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use \App\Models\UsersModel;
use \App\Models\VideosModel;
use \App\Models\CommentsModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;

class CommentsController extends Controller {

    /**
     * @api {Post} /video/:videoid/comment Post new comment on a video.
     * @apiName Comment on video.
     * @apiGroup Video
     * @apiPermission user
     * 
     * 
     * @apiParam {Number} videoid ID of video to post comment to.
     * 
     * @apiParam {String} content Comment message to post.
     * 
     * @apiParamExample {json} Post comment:
     *      {
     *          content: "Commenting the video"
     *      }
     * 
     * @apiSuccess {Number} id Identifier for posted comment.
     * @apiSuccess {String} message Success message.
     * 
     * @apiSuccessExample {json} Post new comment.
     *      HTTP/1.1 201 Created
     *      {
     * 			id: 7,
     * 			message: "Comment created and inserted"
     *      }
     */
	public function postComment(
		UsersModel $users, VideosModel $videos, 
		CommentsModel $comments, Request $req){

		// Accure users permissions and video to update
		$token = $req->token();
	    $currentUser = $users->find([
	    	'id' => $token->userid,
	    ]);
	    $currentVideos = $videos->find([
	    	'id' => $req->param('videoid'),
	    ]);

	    // Check if video exists
	    if(!$currentVideos->id){
	    	return new Error(ErrorCode::get('videos.not_found'));
	    }


		$comments->userid = $currentUser->id;
  		$comments->videoid = $currentVideos->id;
  		$comments->content = $req->input('content');

  		$lastInsertId;
  		try{
  			$lastInsertId = $comments->save();
  		} catch (Exeption $e) {
  			error_log($e);
  			return response::statusCode(500, "Could not create comment");
  		}

  		$res = [
  			'id' => $lastInsertId,
  			'message' => "Comment created and inserted",
  		];

		return response::statusCode(201, $res);

	}

    /**
     * @api {Put} /video/:videoid/comment/:commentid Edit a video comment.
     * @apiName Change a comment.
     * @apiGroup Video
     * @apiPermission owner
     * 
     * 
     * @apiParam {Number} videoid ID of video to post comment to.
     * @apiParam {Number} commentid
     * 
     * @apiParam {String} content New comment message to post.
     * 
     * @apiParamExample {json} Post comment:
     *      {
     *          content: "Commenting the video"
     *      }
     * 
     * @apiSuccess {String} data Success message
     * 
     * @apiSuccessExample {json} Edit comment.
     *      HTTP/1.1 200 Created
     *      {
     * 			data: "Comment updated"
     *      }
     * 
	 * @apiErrorExample {json} Not owner
	 *     HTTP/1.1 401 Unauthorized
	 *     {
	 * 		 code: 401,
	 * 		 error: 1,
	 *       message: "You do not have rights for given comment"
	 *     }
     */
	public function putComment(
		UsersModel $users, CommentsModel $comments, Request $req){

		$token = $req->token();
	    $currentUser = $users->find([
	    	'id' => $token->userid,
	    ]);

	    $existingComment = $comments->find([
	    	'id' => $req->param('commentid'),
	    	'userid' => $currentUser->id,
	    ]);

	    // Check if comment exists for user
	    if (!$comments->id) {
	    	return new Error(ErrorCode::get('comments.not_authorized'));
	    }

	    try {
	    	$comments->update([
	    		'id' => $existingComment->id,
	    		'userid' => $existingComment->userid,
	    	],[
	    		'content' => $req->input('content'),
	    	]);
	    } catch (Exeption $e){
	    	return response::statusCode(500, "Could not update comment");
	    }

	    return response::statusCode(200, "Comment updated");
	}

    /**
     * @api {Delete} /video/:videoid/comment/:commentid Delete a comment.
     * @apiName Delete a comment.
     * @apiGroup Video
     * @apiPermission owner
     * 
     * @apiParam {Number} videoid ID of video to post comment to.
     * @apiParam {Number} commentid
     * 
     * @apiSuccess {String} data Success message
     * 
     * @apiSuccessExample {json} Delete comment.
     *      HTTP/1.1 202 Created
     *      {
     * 			data: "Comment updated"
     *      }
     * 
	 * @apiErrorExample {json} Not owner
	 *     HTTP/1.1 401 Unauthorized
	 *     {
	 * 		 code: 401,
	 * 		 error: 1,
	 *       message: "You do not have rights for given comment"
	 *     }
	 */
	public function deleteComment(
		UsersModel $users, CommentsModel $comments, Request $req){

		$token = $req->token();
	    $currentUser = $users->find([
	    	'id' => $token->userid,
	    ]);

	    $existingComment = $comments->find([
	    	'id' => $req->param('commentid'),
	    	'userid' => $currentUser->id,
	    ]);

	    // Check if comment exists for user
	    if (!$comments->id) {
	    	return new Error(ErrorCode::get('comments.not_authorized'));
	    }

	    try {
	    	$comments->update([
	    		'id' => $existingComment->id,
	    		'userid' => $existingComment->userid,
	    	],[
	    		'deleted_at' =>  date ("Y-m-d H:i:s"),
	    	]);
	    } catch (Exeption $e){
	    	return response::statusCode(500, "Could not update comment");
	    }

	    return response::statusCode(202, "Comment updated");

	}

}