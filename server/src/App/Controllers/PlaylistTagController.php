<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use \App\Models\UsersModel;
use \App\Models\PlaylistsModel;
use \App\Models\PlaylistTagsModel;
use \App\Models\TagsModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;

class PlaylistTagController extends Controller {

	public function postTag(
		UsersModel $user, PlaylistsModel $playlist,
		TagsModel $tag, PlaylistTagsModel $playlistTag,
		Request $req){

		// Accure users permissions and playlist to update
		$token = $req->token();
	    $currentUser = $user->find([
	    	'id' => $token->userid,
	    ]);
	    $currentList = $playlist->find([
	    	'id' => $req->param('playlistid'),
	    	'userid' => $currentUser->id,
	    ]);

	    // Check if the playlist belongs to the user
	    if (!$currentList->id) {
	    	return new Error(ErrorCode::get('playlist.not_authorized'));
	    }

	    $currentTagName = $req->input('name');
	    $existingTag = $tag->find(['text' => $currentTagName]);
	  
	    if ($existingTag->id == null) {	// Save tag in database if it does not exist and update playlist
	    	$tag->text = $currentTagName;
	    	$lastInsertId = $tag->save();

	    	$playlistTag->playlistid = $currentList->id;
			$playlistTag->tagid = $lastInsertId;

			$playlistTag->save();

			return response::statusCode(201, "Tag created and inserted");

	    }else if ($playlistTag->find([
		    	'playlistid' =>  $currentList->id,
		    	'tagid' =>  $existingTag->id
	    	])->id == null) {	// Tag exists, only update playlist
	    	
	    	$playlistTag->playlistid = $currentList->id;
			$playlistTag->tagid = $existingTag->id;

			$playlistTag->save();

			return response::statusCode(200, "Tag inserted");
	    
	    }else{	// Playlist already has the tag

	    	return new Error(ErrorCode::get('playlist.tag_conflict'));
	    }

		return Response::statusCode(202);	
	}

	public function deleteTag(
		UsersModel $user, PlaylistsModel $playlist,
		TagsModel $tag, PlaylistTagsModel $playlistTag,
		Request $req){

		// Accure users permissions and playlist to delete from
		$token = $req->token();
	    $currentUser = $user->find([
	    	'id' => $token->userid,
	    ]);
	    $currentList = $playlist->find([
	    	'id' => $req->param('playlistid'),
	    	'userid' => $currentUser->id,
	    ]);

	    // Check if the playlist belongs to the user
	    if (!$currentList->id) {
	    	return new Error(ErrorCode::get('playlist.not_authorized'));
	    }

	    $currentTagName = $req->param('tagname');
	    $existingTag = $tag->find([
	    	'text' => $currentTagName
	    ]);
	  
		$currentPlaylistTag = $playlistTag->find([
	    	'playlistid' =>  $currentList->id,
	    	'tagid' =>  $existingTag->id
	    ]);

	    // Check if the playlist contains the tag, marks tag as deleted
	    if($currentPlaylistTag->id != null && $currentPlaylistTag->deleted_at == null){
	    	
	    	$currentPlaylistTag->deleted_at = date ("Y-m-d H:i:s");
	    	$playlistTag->update([
	    		'id' => $currentPlaylistTag->id
	    	],[ 
	    		'deleted_at' => $currentPlaylistTag->deleted_at
	    	]);

	    	return response::statusCode(202, "Delete accepted");
	    
	    }

	    // Playlist does not contain tag for deletion
		return new Error(ErrorCode::get('playlist.not_found'));
	}
}