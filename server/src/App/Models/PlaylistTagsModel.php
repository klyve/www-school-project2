<?php namespace App\Models;


class PlaylistTagsModel extends \MVC\Core\Model {

  public $table = 'playlisttags';
  // Primary key, autoincrementer
  public $id;
  // Foreign key
  public $playlistid;
  // Foreign key
  public $tagid;
  
  public $exclude = ['id'];
}
