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


class RatingsModel extends Model {

    public $table = 'ratings';  //the name of the table

    public $ratingId;   //the PK autoIncrement field
    public $rating;     //the value of the rating
    public $videoId;    //the FK to video


    public $exclude = ['ratingId']; //excludes id, because it is automatically incremented

}
