<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use \App\Models\UsersModel;
use \App\Models\PlaylistsModel;
use \App\Models\SubscriptionsModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;

class SubscriptionsController extends Controller {

	public function postSubscriptions(
		UsersModel $user, PlaylistsModel $playlist,
		SubscriptionsModel $subscriptions, Request $req){
return new Error(ErrorCode::get('playlists.not_found'));
		$currentPlaylist = $playlist->find([
			'id' => $req,
		]);
		print_r($currentPlaylist);
		if(!$currentPlaylist->id) {	// Could not find playlist
			return new Error(ErrorCode::get('playlists.not_found'));
		}

		$token = $req->token();
	    $currentUser = $user->find([
	    	'id' => $token->userid,
	    ]);

		$subscriptions->find([
			'userid' => $currentUser->id,
			'playlistid' => $currentPlaylist->id,
		]);

		if(!$currentPlaylist->id){	// Already subscribed, causing a conflict.
			return new Error(ErrorCode::get('subscriptions.already_subscribed'));
		}else{	// Not subscribed, subscribing.
			$subscriptions->userid = $currentUser->id;
			$subscriptions->currentPlaylist = $currentPlaylist->id;

			$subscriptions->save();
			return response::statusCode(201, "Subscribed to playlist");
		}

		return response::statusCode(500, "Unexpected execution path");
	}

	public function deleteSubscriptions(
		UsersModel $user, PlaylistsModel $playlist,
		SubscriptionsModel $subscriptions, Request $req){

		return new Error(ErrorCode::get('videos.not_authorized'));
	}
}