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

    /**
     * @api {Post} /playlist/:playlistid/subscribe Subscribe to playlist.
     * @apiName Subscribe to playlist.
     * @apiGroup Playlist
     * @apiPermission user
     *
     * @apiParam {Number} playlistid Id of playlist to subscrie to.
     * 
     * @apiParamExample Subscribe to playlist
     *      {
     * 
     *      }
     * 
     * @apiUse data
     * 
     * @apiSuccessExample Created playlist
     *      HTTP/1.1 201 Created
     *      {
     *          data: "Subscribed to playlist"
     *      }
     * 
     * @apiSuccessExample Already subscribed
     *      HTTP/1.1 200 OK
     *      {
     *          data: "Already Subscribed"
     *      }
     * 
     * @apiSuccessExample Resubscribed
     *      HTTP/1.1 201 Created
     *      {
     *          data: "Resubscribed to playlist"
     *      }
     * 
     * @apiUse errorCode
     * 
     * @apiErrorExample {json} Error Not Found
     *     HTTP/1.1 404 Not Found
     *     {
     *        code: 404,
     *        error: 3,
     *        message: "Could not find playlist with given playlistid and userid"
     *     }
     */
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


		if ($existingSubscription->id
		&& empty($existingSubscription->deleted_at)) {	
			return response::statusCode(200, "Already Subscribed");
		}
			
		if ($existingSubscription->id
		&& $existingSubscription->deleted_at) {	

			$existingSubscription->deleted_at = null;
			$existingSubscription->save();
			return response::statusCode(201, "Resubscribed to playlist");
		}
			
		$subscriptions->userid = $currentUser->id;
		$subscriptions->playlistid = $currentPlaylist->id;

		$subscriptions->save();
		return response::statusCode(201, "Subscribed to playlist");
	}

    /**
     * @api {Delete} /playlist/:playlistid/subscribe Unsubscribe from playlist.
     * @apiName Unsubscribe from playlist.
     * @apiGroup Playlist
     * @apiPermission user
     *
     * @apiParam {Number} playlistid Id of playlist to subscrie to.
     * 
     * @apiParamExample Delete subscription
     *      {
     * 
     *      }
     * 
     * @apiUse data
     * 
     * @apiSuccessExample Unsubscribe
     *      HTTP/1.1 202 Accepted
     *      {
     *          data: "Delete accepted"
     *      }
     * 
     * @apiSuccessExample Already unsubscribed
     *      HTTP/1.1 200 OK
     *      {
     *          data: "Not subscribed"
     *      }
     * 
     * @apiUse errorCode
     * 
     * @apiErrorExample {json} Error Not Found
     *     HTTP/1.1 404 Not Found
     *     {
     *        code: 404,
     *        error: 3,
     *        message: "Could not find playlist with given playlistid and userid"
     *     }
     */
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

		// Already subscribed, delete subscription
		if($existingSubscription->id
		&& empty($existingSubscription->deleted_at)) {
			
			$subscriptions->update([
				'id' => $existingSubscription->id,
			],[
				'deleted_at' => date("Y-m-d H:i:s"),
			]);

			return response::statusCode(202, "Delete accepted");
		} else {						// Not subscribed, subscribing.

			return response::statusCode(200, "Not subscribed");
		}

		return response::statusCode(500, "Unexpected execution path");
	}
}