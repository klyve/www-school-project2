<?php namespace App\Models;


class RatingsModel extends \MVC\Core\Model {

  public $table = 'ratings';
  // Primary key, autoincrementer
  public $id;
  // Foreign key
  public $videoid;
  // Foreign key
  public $userid;
  // 0 dislike, 1 like
  public $rating;

  public $exclude = ['id'];
}
