<?php namespace App\Models;
/**
 * This is the model for videoMetaDatas, which describes the name of the table,
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


class VideoMetaDatasModel extends Model {

    public $table = 'videoMetaDatas'; //the name of the table

    public $id;       //the PK autoIncrement field
    public $videoId;  //the FK to video
    public $metakey;  //the type of metadata (e.g. TAG)
    public $value;    //the value of metadata (e.g. PHP)


    public $exclude = ['id']; //excludes id, because it is automatically incremented

}
