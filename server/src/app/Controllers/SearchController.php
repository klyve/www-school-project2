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

use \MVC\Core\View;
use \MVC\Http\Request;
use \MVC\Core\Session;
use \MVC\Core\Config;
use \App\Models\UsersModel;
use \App\Models\VideoModel;
use \App\Models\PlaylistsModel;
use \App\Models\VideoContainsPlaylistsModel;
use \App\Models\VideoMetaDatasModel;






class SearchController extends BaseController {
/**
 * @TODO: [getSearch description]
 * @param  Request                     $req  [description]
 * @param  UsersModel                  $usr  [description]
 * @param  PlaylistsModel              $play [description]
 * @param  VideoContainsPlaylistsModel $vcp  [description]
 * @param  VideoModel                  $vid  [description]
 * @param  VideoMetaDatasModel         $meta [description]
 * @return [type]                            [description]
 */
    public function getSearch(Request $req,
                              UsersModel $usr,
                              PlaylistsModel $play,
                              VideoContainsPlaylistsModel $vcp,
                              VideoModel $vid,
                              VideoMetaDatasModel $meta) {
                                
        if ($req->input("query") == "") {
          return $this->render('pages/index');
        }

        // Search through metadata
        $metaData = $meta->search(["value" => $req->input("query")]);
        $metaSearch = [];
        foreach ($metaData as $key) {
          $tmp = $vid->find(["id" => $key->videoId]);
          array_push($metaSearch, $tmp);
        }

        // Search trough playlists
        $playlists = $play->search(["name" => $req->input("query")]);

        $videoIds = [];
        foreach ($playlists as $playlist) {
          $tmp = $vcp->all(["playlistId" => $playlist->id]);
          $videos = [];
          $playlist->videos = [];
          foreach($tmp as $videoItem) {
            $video = $vid->find(["id" => $videoItem->videoId]);
            if($video->id) {
              $found = false;
              foreach($videos as $videoObject) {
                if($videoObject->id === $video->id) $fount = true;
              }
              if(!$found) {
                $videos[] = $video;
              }
            }
          }
          $playlist->videos = $videos;
        }

        // Search through videos
        $videoSearch = $vid->search(["name" => $req->input("query")]);

        // Search through users
        $users = $usr->search(["name" => $req->input("query")]);
        $userSearch = [];
        foreach ($users as $key) {
          $tmp = $vid->all(["userId" => $key->id]);
          $userSearch = array_merge($userSearch, $tmp);
        }

        $videos = [];
        

        foreach($videoSearch as $video) {
          if(count($videos) == 0) {
            $videos[] = $video;
            continue;
          }
          foreach($videos as $item) {
            if($item->id != $video->id) {
              $videos[] = $video;
              break;
            }
          }
        }
        foreach($userSearch as $video) {
          if(count($videos) == 0) {
            $videos[] = $video;
            continue;
          }
          foreach($videos as $item) {
            if($item->id !== $video->id) {
              $videos[] = $video;
              break;
            }
          }
        }
        foreach($metaSearch as $video) {
          if(count($videos) == 0) {
            $videos[] = $video;
            continue;
          }
          foreach($videos as $item) {
            if($item->id !== $video->id) {
              $videos[] = $video;
              break;
            }
          }
        }

        if ($playlists) {
          return $this->render('pages/search', ["videos" => $videos,"playlists" => $playlists]);
        } else {
          return $this->render('pages/search', ["videos" => $videos]);
        }
    }


}
