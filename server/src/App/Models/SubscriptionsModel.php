<?php namespace App\Models;


class SubscriptionsModel extends \MVC\Core\Model {

  public $table = 'subscriptions';
  // Primary key, autoincrementer
  public $id;
  // Foreign key
  public $userid;
  // Foreign key
  public $playlistid;
  
  public $exclude = ['id'];
}
