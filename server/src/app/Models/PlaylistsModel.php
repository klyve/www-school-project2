<?php namespace App\Models;
/**
 * @TODO: Bjarte forklar 28-36
 * This is the model for playlists, which describes the name of the table, and the name of
 * the fields
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

class PlaylistsModel extends Model implements OneToMany {

    public $table = 'playlists';  //the name of the table


    public $id;     //the PK autoIncrement field
    public $name;   //the name of the playlist
    public $userId; //the FK to user


    public $videos = [VideoContainsPlaylistsModel::class];

    public $exclude = ['playlistId', 'videos'];
/**
 * @TODO: [getManyInstance description]
 * @param  [type] $class [description]
 * @return [type]        [description]
 */
    public function getManyInstance($class) {
        if($class->table == 'users') {
            return $this->all([
                'userId' => $class->id,
            ]);
        }
    }

    public function getSingleInstance($class) {
        if($class->table == 'subscription') {
            return $this->find([
                "id" => $class->playlistId
            ]);
        }
    }
}
