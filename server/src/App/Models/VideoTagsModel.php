<?php namespace App\Models;


class VideoTagsModel extends \MVC\Core\Model {

  public $table = 'videotags';
  // Primary key, autoincrementer
  public $id;
  // Foreign key
  public $videoid;
  // Foreign key
  public $tagid;
  
  public $exclude = ['id'];
}
