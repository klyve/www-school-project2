<?php namespace App\Controllers;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Controllers
 * @package    rewind
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

use \MVC\Core\Session;
use \MVC\Core\View;
use \MVC\Http\Response;
use \MVC\Http\Request;
use \App\Models as Models;



class PageController extends BaseController {

/**
 * @return render @TODO describe whats returned
 */
    public function getDashboard() {

        return $this->render('pages/index');
    }


/**
 * @param Response $res the response
 * @return render @TODO describe whats returned
 */
    public function getLandingPage(Response $res) {
        if(Session::get('uid')) {
            $res->redirect('index');
        }
        return View::render('landingpage');
    }
/**
 * @param Request $req the request
 * @param VideoModel object used to find videos
 * @param VideoContainsPlaylistsModel object to find relation between video and playlist
 * @param PlaylistsModel object used to find playlist
 * @param CommentsModel object used to find comments
 * @param UsersModel finds user
 * @return render renders view
 */
    public function getVideoView(Request $req,
                                 Models\VideoModel $video,
                                 Models\CommentsModel $comment,
                                 Models\VideoContainsPlaylistsModel $pcv,
                                 Models\PlaylistsModel $playlist,
                                 Models\CommentsModel $com,
                                 Models\UsersModel $usr,
                                 Models\RatingsModel $rate) {



      // Find video from id
      $vid = $video->find(["id" => $req->input("id")]);

      // Gets comments for the video
      $comments = $comment->all([
        "videoId" => $vid->id
      ]);

      // Find id of videos playlist
      $playlistId = $pcv->find(["videoId" => $vid->id])->playlistId;

      // Find name of playlist
      $playlistName = $playlist->find(["id" => $playlistId])->name;

      $rates = $rate->all(["videoId" => $vid->id]);
      $rating = 0;
      foreach ($rates as $key => $value) {
        $rating += (int)$value->rating;
      }


      if ($req->input("id") !== "" && isset($vid->id)) {
        return $this->render('pages/videoView', [
          "vid" => $vid,
          "playlist" => $playlistName,
          "playlistId" => $playlistId,
          "comments" => $comments,
          "rating" => $rating
        ]);
      } else {
        die("Video Not Found!");
      }
    }
/**
  * @param Request $req the request
  * @param Response $res the response
  * @param UsersModel $user @TODO describe this model
  * @param VideoModel $video @TODO describe this model
  * @param CommentsModel $com @TODO describe this model
 */
    public function postCommentsVideoView(Request $req,
                                          Response $res,
                                          Models\UsersModel $user,
                                          Models\CommentsModel $comment,
                                          Models\VideoContainsPlaylistsModel $pcv,
                                          Models\VideoModel $video,
                                          Models\CommentsModel $com,
                                          Models\PlaylistsModel $playlist,
                                          Models\RatingsModel $rate) {
      // Find user id from session
      $user = Session::get('uid');
      // Find video from id
      $vid = $video->find(["id" => $req->input("id")]);

      // Gets comments for the video
      $comments = $comment->all([
        "videoId" => $vid->id
      ]);

      // Find id of videos playlist
      $playlistId = $pcv->find(["videoId" => $vid->id])->playlistId;

      // Find name of playlist
      $playlistName = $playlist->find(["id" => $playlistId])->name;

      $vote = false;

      // If thumbs up and down
      if ($req->input("voteup")) {
        $rate->rating = 1;
        $rate->videoId = $vid->id;
        $rate->save();
        $vote = true;
      }
      if ($req->input("votedown")) {
        $rate->rating = -1;
        $rate->videoId = $vid->id;
        $rate->save();
        $vote = true;
      }

      $rates = $rate->all(["videoId" => $vid->id]);
      $rating = 0;
      foreach ($rates as $key => $value) {
        $rating += (int)$value->rating;
      }

      $com->videoId = $vid->id;
      $com->comment = $req->input("comment");
      $com->userId = $user->id;
      $com->save();

      return $this->render('pages/videoview', [
        "vid" => $vid,
        "playlist" => $playlistName,
        "playlistId" => $playlistId,
        "comments" => $comments,
        "rating" => $rating,
        "vote" => $vote,
      ]);
    }

/**
 * @param Request $req the request
 * @param VideoContainsPlaylistsModel $videosInPlaylist @TODO describe this model
 * @param playlistsModel $playlist @TODO describe this model
 * @param videoModel $video @TODO describe this model
 * @return render @TODO describe whats returned
 */

    public function getPlaylist(
                                  Request $req,
                                  Models\VideoContainsPlaylistsModel $videosInPlaylist,
                                  Models\playlistsModel $playlist,
                                  Models\videoModel $video
                                ) {

      // Abort if no ID
      if (!$req->input("id")) {
        echo "404 not found";
        return;
      }

      // Find the playlist with the ID
      $playlist->find([
        'id' => $req->input("id")
      ]);

      // all video id's in the playlist
      $tmp = $videosInPlaylist->all([
        "playlistId" => $playlist->id
      ]);
      // $tmp = $videosInPlaylist->getManyInstance($playlist);

      // itterate over video id's and find the video information
      $videoList = [];
      foreach($tmp as $entry) {
        $tmp = $video->find([
          "id" => $entry->videoId
        ]);
        array_push($videoList, [
          "name" => $tmp->name,
          "id" => $tmp->id
        ]);
      };



      return $this->render('pages/playlistPage', [
        "playlist" => $playlist,
        "videoList" => $videoList
      ]);
    }

    public function postPlaylist(
            Request $req,
            Models\VideoContainsPlaylistsModel $videosInPlaylist,
            Models\playlistsModel $playlist,
            Models\videoModel $video,
            Models\SubscriptionModel $sub
          ) {

      // Abort if no ID
      if (!$req->input("id")) {
        echo "404 not found";
        return;
      }



      // Find the playlist with the ID
      $playlist->find([
      'id' => $req->input("id")
      ]);
      
      $userid = Session::get('uid');
      $sub->find([
        "playlistId" => $playlist->id,
        "userId" => $userid
      ]);
      if(!$sub->id) {
        $sub->playlistId = $playlist->id;
        $sub->userId = Session::get('uid');
        $sub->save();
      }
      

      // all video id's in the playlist
      $tmp = $videosInPlaylist->all([
        "playlistId" => $playlist->id
      ]);
      // $tmp = $videosInPlaylist->getManyInstance($playlist);

      // itterate over video id's and find the video information
      $videoList = [];
      foreach($tmp as $entry) {
      $tmp = $video->find([
        "id" => $entry->videoId
      ]);
      array_push($videoList, [
        "name" => $tmp->name,
        "id" => $tmp->id
      ]);
      };



      return $this->render('pages/playlistPage', [
      "playlist" => $playlist,
      "videoList" => $videoList
      ]);
    }
}
