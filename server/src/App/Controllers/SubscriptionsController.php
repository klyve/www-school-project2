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

		$currentPlaylist = $playlist->find([
			'id' => $req->param('playlistid'),
		]);

		if(!$currentPlaylist->id) {	// Could not find playlist
			return new Error(ErrorCode::get('playlist.not_found'));
		}

		$token = $req->token();
	    $currentUser = $user->find([
	    	'id' => $token->userid,
	    ]);

		$existingSubscription = $subscriptions->find([
			'userid' => $currentUser->id,
			'playlistid' => $currentPlaylist->id,
		]);

		if($existingSubscription->id){	// Already subscribed, causing a conflict.
			return new Error(ErrorCode::get('subscriptions.already_subscribed'));

		}else{						// Not subscribed, subscribing.
			print_r($existingSubscription);
			$subscriptions->userid = $currentUser->id;
			$subscriptions->playlistid = $currentPlaylist->id;

			$subscriptions->save();
			return response::statusCode(201, "Subscribed to playlist");
		}

		return response::statusCode(500, "Unexpected execution path");
	}

	public function deleteSubscriptions(
		UsersModel $user, PlaylistsModel $playlist,
		SubscriptionsModel $subscriptions, Request $req){

		$currentPlaylist = $playlist->find([
			'id' => $req->param('playlistid'),
		]);

		if(!$currentPlaylist->id) {	// Could not find playlist
			return new Error(ErrorCode::get('playlist.not_found'));
		}

		$token = $req->token();
	    $currentUser = $user->find([
	    	'id' => $token->userid,
	    ]);

		$existingSubscription = $subscriptions->find([
			'userid' => $currentUser->id,
			'playlistid' => $currentPlaylist->id,
		]);

		if($existingSubscription->id && $existingSubscription->deleted_at == null){	// Already subscribed, delete subscription
			
			$subscriptions->update([
				'id' => $existingSubscription->id,
			],[
				'deleted_at' => date("Y-m-d H:i:s"),
			]);

			return response::statusCode(202, "Delete accepted");
		}else{						// Not subscribed, subscribing.

			return response::statusCode(200, "Not subscribed");
		}

		return response::statusCode(500, "Unexpected execution path");
	}
}