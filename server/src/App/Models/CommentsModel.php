<?php namespace App\Models;


class CommentsModel extends \MVC\Core\Model {

  public $table = 'comments';
  // Primary key, autoincrementer
  public $id;
  // Foreign key
  public $userid;
  // Foreign key
  public $videoid;
  // text
  public $content;

  public $exclude = ['id'];
}
