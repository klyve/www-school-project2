<?php namespace App\Models;
/**
 * @TODO: Bjarte forklar 28 - 53
 * This is the model for users, which describes the name of the table, and the name of
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


class UsersModel extends Model implements \MVC\Core\Model\OneToMany{

    public $table = 'users'; //the name of the table

    public $id;             //the PK autoIncrement field
    public $name;           //the name of the user
    public $email;          //the email of the user
    public $usergroup;      //the $usergroup level of the user
    public $password;       //the password of the user
    public $language;       //the preferred language of the user
    public $suggestedRole;

    // public $playlists = [PlaylistsModel::class];

    // public $protected = [''];
    public $exclude = ['id', 'playlists'];
    // public $protected = ['password'];
/**
 * @TODO: [getManyInstance description]
 * @param  [type] $class [description]
 * @return [type]        [description]
 */
    public function getManyInstance($class) {

        if($class->table == 'comments') {
            return $this->all([
                'id' => $class->userId,
            ]);
        }
    }
/**
 * @TODO: [getSingleInstance description]
 * @param  [type] $class [description]
 * @return [type]        [description]
 */
    public function getSingleInstance($class) {
      if($class->table == 'videos') {
        return $this->find([
          'id' => $class->userId,
        ]);
      }
    }
}
