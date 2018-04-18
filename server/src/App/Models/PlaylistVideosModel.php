<?php namespace App\Models;


class PlaylistVideosModel extends \MVC\Core\Model {

  public $table = 'playlistvideos';
  // Primary key, autoincrementer
  public $id;
  // Foreign key
  public $playlistid;
  // Foreign key
  public $videoid;
  // int, position in playlist
  public $position;

  public $exclude = ['id'];
}
