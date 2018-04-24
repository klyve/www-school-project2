<?php namespace App\Models;


class SearchModel {

  public $value = '';
  public $limit = 0;

  public function __construct($args) {
    if(isset($args['value'])) {
      $this->value = $args['value'];
    }
    if(isset($args['limit'])) {
      $this->limit = $args['limit'];
    }
  }
}
