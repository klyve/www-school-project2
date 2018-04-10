<?php namespace App\Models;


class TestModel extends \MVC\Core\Model implements \MVC\Core\Model\OneToMany {

  public $table = 'test';
  public $id;
  public $name;
  public $age;

  public $exclude = ['id'];


  public function getSingleInstance($instance) {
    if($instance->table == 'users') {
      return $this->find([
        'age' => $instance->id,
      ]);
    }
  }

  public function getManyInstance($instance) {
    return $this->all([
      'age' => $instance->id,
    ]);
  }
}
