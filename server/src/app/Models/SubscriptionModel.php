<?php namespace App\Models;
/**
 * This is the model for ratings, which describes the name of the table, and the name of
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

class SubscriptionModel extends Model {

    public $table = 'subscription'; //the name of the table

    public $id;         //the PK autoIncrement field
    public $playlistId; //the FK to playlists
    public $userId;     //the FK to users
    public $playlist = PlaylistsModel::class;

    public $exclude = ['id', 'playlist']; //excludes id, because it is automatically incremented

}
