<?php namespace App\Models;


class TempVideosModel extends \MVC\Core\Model {

  public $table = 'temp_videos';
  // primary key, auto incrementer
  public $id;
  // foreign key
  public $userid;
  public $fname;

  public $size;
  public $mime;

  public $exclude = ['id'];
}
