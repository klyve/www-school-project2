<?php namespace App\Models;


class PlaylistsModel extends \MVC\Core\Model {

  public $table = 'playlists';
  // primary key, auto incrementer
  public $id;
  // foreign key
  public $userid;

  public $title;

  public $description;

  public $exclude = ['id'];
}
