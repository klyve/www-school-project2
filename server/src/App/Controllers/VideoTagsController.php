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

	/**
	 * @api {post} /video/:videoid/tag Post a new tag to be applied to the video.
	 * @apiName Post video tag
	 * @apiGroup Video
	 * @apiGroup Tag
	 *
	 * @apiParam {Number} videoid Videos unique ID.
	 * @apiParam {String} name content of the tag. 
	 * 
	 * @apiParamExample /video/:videoid/tag {json} Post tag:
	 * 		{
	 * 			name: "JSON"
	 * 		}
	 * 
	 *
	 * @apiSuccessExample When new tag is added to the video.
	 * 		HTTP/1.1 201 OK
	 * 		{
	 * 			data: "Tag created and inserted"
	 * 		}
	 * 
	 * @apiSuccessExample When existing tag is added to the video.
	 *		HTTP/1.1 200 OK
	 * 		{
	 * 			data: "Tag inserted"
	 * 		}
	 * 
	 * @apiErrorExample {json} Error user does not own video with given id:
     *     HTTP/1.1 401 Unauthorized
 	 *     {
 	 *        code: 401,
     *		  error: 1,
     *   	  message: 'You do not have rights for given video',
 	 *     }
 	 * 
	 * @apiErrorExample {json} Error video already contains the given tag:
     *     HTTP/1.1 409 Conflict
 	 *     {
 	 *        code: 409,
     *		  error: 2,
     *   	  message: 'The video already contains the tag',
 	 *     }
	 */
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

	/**
	 * @api {Delete} /video/:videoid/tag/:tagname Remove a tag from video.
	 * @apiName Delete video tag
	 * @apiGroup Video
	 * @apiGroup Tag
	 *
	 * @apiParam {Number} videoid Videos unique ID.
	 * @apiParam {String} name content of the tag. 
	 * 
	 *
	 * @apiSuccessExample When new tag is added to the video.
	 * 		HTTP/1.1 202 OK
	 * 		{
	 * 			data: "Tag created and inserted"
	 * 		}
	 * 
	 * @apiSuccessExample When existing tag is added to the video.
	 *		HTTP/1.1 200 OK
	 * 		{
	 * 			data: "Delete accepted"
	 * 		}
	 * 
	 * @apiErrorExample {json} Error user does not own video with given id:
     *     HTTP/1.1 401 Unauthorized
 	 *     {
 	 *        code: 401,
     *		  error: 1,
     *   	  message: 'You do not have rights for given video',
 	 *     }
 	 * 
	 * @apiErrorExample {json} Error video with tag does not exist:
     *     HTTP/1.1 404 Not Found
 	 *     {
 	 *        code: 404,
     *		  error: 3,
     *   	  message: 'Could not find resource',
 	 *     }
	 */
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