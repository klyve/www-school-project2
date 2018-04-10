<?php namespace App\Models;
/**
 * This is the model for comments, which describes the name of the table, and the name of
 * the fields
 * @TODO: bjarte, forklar linje 25 - 36
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

class CommentsModel extends Model {

    public $table = 'comments'; // the name of the table
    public $commentId;  //the PK autoIncrement field
    public $videoId;    //the FK to video
    public $comment;    //the comment body
    public $userId;     //the FK to user

    public $user = UsersModel::class;

    public $exclude = ['commentId', 'user'];

/**
 * @TODO: [getManyInstance description]
 * @param  [type] $class [description]
 * @return [type]        [description]
 */
    public function getManyInstance($class) {
        if($class->table == 'videos') {
            return $this->all([
                'videoId' => $class->id,
            ]);
        }
    }
}
