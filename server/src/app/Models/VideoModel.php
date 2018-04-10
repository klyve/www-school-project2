<?php namespace App\Models;
/**
 * @TODO: Bjarte forklar 31
 * This is the model for videos, which describes the name of the table,
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

class VideoModel extends Model {

    public $table = 'videos'; //the name of the table

    public $id;           //the PK autoIncrement field
    public $videoId;      //the unique, randomized id for the video
    public $name;         //the name (title) of the video
    public $description;  //the description of the video
    public $userId;       //the FK to user which describes who uploaded the video
    public $created_at;   //timestamp for upload
    public $deleted_at;   //timestamp for soft deletion (will not hard delete before
                          //some time (e.g 30 days) has passed in case of mistake.


    public $comments = [CommentsModel::class];
    public $user = UsersModel::class;

    public $exclude = ['id', 'comments', 'user', 'created_at', 'deleted_at'];
    // public $protected = ['password'];
/**
 * @TODO: [getSingleInstance description]
 * @param  [type] $class [description]
 * @return [type]        [description]
 */
    public function getSingleInstance($class) {
        if($class->table == 'videoContainsPlaylists') {
            return $this->all([
                'id' => $class->videoId,
            ]);
        }
    }
}
