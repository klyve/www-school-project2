<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use \App\Models\UsersModel;
use \App\Models\VideosModel;
use \App\Models\VideoTagsModel;
use \App\Models\TagsModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;

class VideoTagsController extends Controller {

	public function postTag(
		UsersModel $user, VideosModel $videos,
		TagsModel $tag, VideoTagsModel $videoTags,
		Request $req){

		// Accure users permissions and video to update
		$token = $req->token();
	    $currentUser = $user->find([
	    	'id' => $token->userid,
	    ]);
	    $currentVideos = $videos->find([
	    	'id' => $req->param('videoid'),
	    	'userid' => $currentUser->id,
	    ]);

	    // Check if the user has the requested video
	    if (!$currentVideos->id) {
	    	return new Error(ErrorCode::get('videos.not_authorized'));
	    }

	    $currentTagName = $req->input('name');
	    $existingTag = $tag->find(['text' => $currentTagName]);
	  
	    if ($existingTag->id == null) {	// Save tag in database if it does not exist and update video
	    	$tag->text = $currentTagName;
	    	$lastInsertId = $tag->save();

	    	$videoTags->videoid = $currentVideos->id;
			$videoTags->tagid = $lastInsertId;

			$videoTags->save();

			return response::statusCode(201, "Tag created and inserted");

	    }else if ($videoTags->find([
		    	'videoid' =>  $currentVideos->id,
		    	'tagid' =>  $existingTag->id
	    	])->id == null) {	// Tag exists, only update video
	    	
	    	$videoTags->videoid = $currentVideos->id;
			$videoTags->tagid = $existingTag->id;

			$videoTags->save();

			return response::statusCode(200, "Tag inserted");
	    
	    }else{	// Video already has the tag

	    	return new Error(ErrorCode::get('videos.tag_conflict'));
	    }

		return Response::statusCode(202);	
	}

	public function deleteTag(
		UsersModel $user, VideosModel $videos,
		TagsModel $tag, VideoTagsModel $videoTags,
		Request $req){

		// Accure users permissions and video to delete from
		$token = $req->token();
	    $currentUser = $user->find([
	    	'id' => $token->userid,
	    ]);
	    $currentVideos = $videos->find([
	    	'id' => $req->param('videoid'),
	    	'userid' => $currentUser->id,
	    ]);

	    // Check if the user has the requested video
	    if (!$currentVideos->id) {
	    	return new Error(ErrorCode::get('videos.not_authorized'));
	    }

	    $currentTagName = $req->param('tagname');
	    $existingTag = $tag->find([
	    	'text' => $currentTagName
	    ]);
	  
		$currentVideosTag = $videoTags->find([
	    	'videoid' =>  $currentVideos->id,
	    	'tagid' =>  $existingTag->id
	    ]);

	    // Check if the playlist contains the tag, marks tag as deleted
	    if($currentVideosTag->id != null && $currentVideosTag->deleted_at == null){
	    	
	    	$currentVideosTag->deleted_at = date ("Y-m-d H:i:s");
	    	$videoTags->update([
	    		'id' => $currentVideosTag->id
	    	],[ 
	    		'deleted_at' => $currentVideosTag->deleted_at
	    	]);

	    	return response::statusCode(202, "Delete accepted");
	    
	    }

	    // Playlist does not contain tag for deletion
		return new Error(ErrorCode::get('videos.not_found'));
	}
}