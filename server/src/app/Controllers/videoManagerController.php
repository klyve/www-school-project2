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
use \MVC\Core\Config;
use \MVC\Helpers\Hash;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \App\Models;
use \App\Views;


class videoManagerController extends BaseController {


  // compare function between comments' #created_at
  /**
   * @TODO: [sortNewestFirst description]
   * @param  [type] $a [description]
   * @param  [type] $b [description]
   * @return [type]    [description]
   */
  private function sortNewestFirst($a, $b) {
    switch ($a->table) {
      case 'comments' || 'videos':
        //No need for conversion
        break;

      default:
        die("COMPARE ERROR (sortNewestFirst): entity not supported");
        break;
    }

    if (strtotime($a->created_at) == strtotime($b->created_at)) {
        return 0;
    }
    return (strtotime($a->created_at) > strtotime($b->created_at) ) ? -1 : 1;
  }

  /**
   * @param Request $req the request
   * @param VideoModel $video @TODO describe this model
   * @param VideoContainsPlaylistsModel $pcv @TODO describe this model
   * @param PlaylistsModel $playlist @TODO describe this model
   * @param CommentsModel $comment @TODO describe this model
   * @param UsersModel $user @TODO describe this model
   * @return render @TODO describe whats returned
   */
  public function getManageComments(
      Models\VideoModel $video,
      Models\VideoContainsPlaylistsModel $pcv,
      Models\PlaylistsModel $playlist,
      Models\CommentsModel $comment
  ) {


    // Gets all videos for current user
    $videos = $video->all([
      'userId' => Session::get('uid')
    ]);

    // Gets comments for the videos
    $comments = [];
    foreach ($videos as $video) {
      $comments = array_merge($comments, $comment->all([
        "videoId" => $video->id
      ]));
    }


    // Sorts newest comments first
    uasort($comments, array($this, 'sortNewestFirst'));

    // Gets all the videos in order (spaghetti ahead)
    $videos = [];
    foreach ($comments as $comment) {
      // Finds the video information
      if(!$comment->videoId) continue;

      $tmpVideo = $video->find([
        "id" => $comment->videoId
      ]);
      

      // Finds the playlist information based on the match between videos and playlists
      $tmpPlaylist = $playlist->find([
        "id" => $pcv->find([
          "videoId"  => $comment->videoId
        ])->playlistId
      ]);


      // Have to do this... or the array will be overwritten
      array_push($videos, [
        "videoName" => $tmpVideo->name,
        "videoId" => $tmpVideo->id,
        "playlistName" => $tmpPlaylist->name
      ]);
    }

    // Renders the page with the given parameters
    return $this->render('videoManager/comments', [
      "videos" => array_values($videos),
      "comments" => array_values($comments)
    ]);
  }





/**
 * @TODO: [getManagePlaylists description]
 * @param  ModelsPlaylistsModel $playlist [description]
 * @return [type]                         [description]
 */
  public function getManagePlaylists(
      Models\PlaylistsModel $playlist
  ) {

    // Gets all playlists for current user
    $playlists = $playlist->all([
      'userId' => Session::get('uid')
    ]);

    return $this->render('videoManager/playlists', [
      "playlists" => $playlists
    ]);
  }
/**
 * @TODO: [postManagePlaylists description]
 * @param  Request              $req      [description]
 * @param  Response             $res      [description]
 * @param  ModelsUsersModel     $user     [description]
 * @param  ModelsPlaylistsModel $playlist [description]
 * @return [type]                         [description]
 */
  public function postManagePlaylists(
      Request $req,
      Response $res,
      Models\UsersModel $user,
      Models\PlaylistsModel $playlist
  ) {

    // Find user id from session
    $user = $user->find(["id" => Session::get('uid')]);

    $playlist->name = $req->input("playlistName");
    $playlist->userId = $user->id;

    $playlist->save();

    $res->redirect('managePlaylists');
  }





/**
 * @TODO: [getManageVideos description]
 * @param  ModelsVideoModel                  $video    [description]
 * @param  ModelsVideoContainsPlaylistsModel $pcv      [description]
 * @param  ModelsPlaylistsModel              $playlist [description]
 * @return [type]                                      [description]
 */
  public function getManageVideos(
      Models\VideoModel $video,
      Models\VideoContainsPlaylistsModel $pcv,
      Models\PlaylistsModel $playlist
  ) {

    // Gets all videos for current user
    $videos = $video->all([
      'userId' => Session::get('uid')
    ]);

    // Sorts newest videos first
    uasort($videos, array($this, 'sortNewestFirst'));

    $list = [];
    foreach ($videos as $video) {
      $playlistId = $pcv->find([
        "videoId" => $video->id
      ]);

      $playlist = $playlist->find([
        "id" => $playlistId->id
      ]);

      array_push($list, [
        "video" => $video,
        "playlistName" => $playlist->name //have to do this (...else mutable)
      ]);
    }


    return $this->render('videoManager/videos', [
      'list' => array_values($list)
    ]);
  }

/**
 * @param Request $req the request
 * @param UsersModel $usr the model containing the user
 * @param PlaylistsModel $playlists @TODO describe $playlists
 * @return render @TODO describe whats returned
 */
  public function getManageUploads(Request $req,
                                   Models\UsersModel $usr,
                                   Models\PlaylistsModel $playlists) {
    $usr->find(["id" => Session::get("uid")]);

    
    $allPlaylists = $playlists->all(["userid" => $usr->id]);
    if ($usr->id) {
      return $this->render('videoManager/upload', ["playlists" => $allPlaylists]);
    }
  }

  /**
   * @param Request $req the request
   * @param VideoModel $vid @TODO describe $vid
   * @param VideoContainsPlaylistsModel $playlist @TODO describe $playlist
   * @return render @TODO describe whats returned
   */
  public function postManageUploads(Request $req,
                                    Response $res,
                                    Models\VideoModel $vid,
                                    Models\VideoContainsPlaylistsModel $playlist) {
    $validator = $req->validate([
      'video' => 'required|uploaded_file:0,50MB,mp4',
    ]);

    $usr = Session::get('uid');


    if($usr && !$validator->fails()) {
      $video = $req->getFile('video');

      $videoFileType = strtolower(pathinfo($video["name"],PATHINFO_EXTENSION));

      $fileRoot = WWW_ROOT . '/' .  Config::get('filepaths.videos') . '/';
      $fileName = Hash::md5($video["tmp_name"]).'.'.$videoFileType;
      if (move_uploaded_file($video["tmp_name"], $fileRoot.$fileName)) {

          $vid->userId = $usr;
          $vid->description = $req->input('description');
          $vid->name = $req->input('name');
          $vid->videoId = $fileName;
          $vid->created_at = date('m:d:y', time());


          $videoID = $vid->save();


          if($req->input('playlist') != 'NULL') {
              $playlist->playlistId = $req->input('playlist');
              $playlist->videoId = $videoID;
              $playlist->save();
          }
          $res->redirect('editvideos&id='.$videoID);

      }else {
        die("Could not upload file!");
      }
    }else {
      die("Could not validate file!");
    }
    return $this->render('videoManager/upload');
  }



  public function getEditVideo(Request $req, Models\UsersModel $usr, Models\PlaylistsModel $playlists, Models\VideoModel $video, Models\VideoContainsPlaylistsModel $vcm) {

    $videoId = $req->input('id');

    $video->find([
      'id' => $videoId,
      'userid' => Session::get('uid')
    ]);
    
    if(!$video->id) {
      return $this->render('404', ['message' => 'Video not found']);
    }

    $playlistsGroup = $playlists->all([
      'userId' => Session::get('uid')
    ]);

    $vcm->find([
      'videoId' => $videoId,
    ]);
    $playlistId = $vcm->playlistId;
    


    return $this->render('videoManager/editvideo', ['video' => $video, 'playlistid' => $playlistId, 'playlists' => $playlistsGroup]);
  }

  public function postEditVideo(Request $req, Models\UsersModel $usr, Models\PlaylistsModel $playlists, Models\VideoModel $video, Models\VideoContainsPlaylistsModel $vcm) {

    $videoId = $req->input('id');

    $video->find([
      'id' => $videoId,
      'userid' => Session::get('uid')
    ]);
    
    if(!$video->id) {
      return $this->render('404', ['message' => 'Video not found']);
    }


    $playlistsGroup = $playlists->all([
      'userId' => Session::get('uid')
    ]);

    $vcm->find([
      'videoId' => $videoId,
    ]);
      
    $vcm->playlistId = $req->input('playlist');
    $fileName = false;
    $thumb = $req->getFile('thumbnail');
    if($thumb && $thumb["size"] > 0) {
      $validator = $req->validate([
        'thumbnail' => 'uploaded_file:0,500K,png,jpeg',
      ]);

      if(!$validator->fails()) {
        $avatar = $req->getFile('thumbnail');
        
        $imageFileType = strtolower(pathinfo($avatar["name"],PATHINFO_EXTENSION));

        $fileRoot = WWW_ROOT . '/' .  Config::get('filepaths.images') . '/';
        $fileName = Hash::md5($avatar["tmp_name"]).'.'.$imageFileType;
        if (move_uploaded_file($avatar["tmp_name"], $fileRoot.$fileName)) {

          if($vcm->playlistId > 0) {
            $vcm->save();
          }
            
            $video->update([
              'id' => $video->id,
            ], [
              'name' => $req->input('name'),
              'description' => $req->input('description'),
              'thumbnail' => $fileName,
            ]);

        }else {
          die("Could not upload file!");
        }
      }else {
        die("Could not validate file!");
      }
    }else {
      if($vcm->playlistId > 0) {
        $vcm->save();
      }
          
      $video->update([
        'id' => $video->id,
      ], [
        'name' => $req->input('name'),
        'description' => $req->input('description'),
      ]);
    }

    $video->find([
      'id' => $video->id,
    ]);
    $playlistId = $vcm->playlistId;

    return $this->render('videoManager/editvideo', ['video' => $video, 'playlistid' => $playlistId, 'playlists' => $playlistsGroup]);
  }

}
