<?php namespace App\Models;
/**
 * @TODO: Bjarte forklar 29 - 37
 * This is the model for videoContainsPlaylists, which describes the name of the table,
 * and the name of the fields
 *
 * PHP version 7
 *
 * @category   Models
 * @package    rewind
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

use MVC\Core\Model;
use \MVC\Core\Model\OneToMany;

class VideoContainsPlaylistsModel extends Model implements OneToMany {

    public $table = 'videoContainsPlaylists'; //the name of the table

    public $id;           //the PK autoIncrement field
    public $playlistId;   //the FK to playlist
    public $videoId;      //the FK to video

    public $video = VideoModel::class;

    public $exclude = ['id', 'video'];
/**
 * @TODO: [getManyInstance description]
 * @param  [type] $class [description]
 * @return [type]        [description]
 */
    public function getManyInstance($class) {
        if($class->table == 'playlists') {
            $playlists = $this->all([
                'playlistId' => $class->id,
            ]);
        }
    }

}
