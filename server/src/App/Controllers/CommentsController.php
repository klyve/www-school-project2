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