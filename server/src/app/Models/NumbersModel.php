<?php namespace App\Models;
/**
 * @TODO: Bjarte forklar denne fila
 * This is the model for numbers, which describes the name of the table, and the name of
 * the fields
 *
 * PHP version 7
 *
 * @category   Models
 * @package    rewind
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */
use MVC\Core\Model;

use MVC\Core\Model\OneToOne;
use MVC\Core\Model\OneToMany;

class NumbersModel extends Model implements OneToOne, OneToMany {

    public $table = 'numbers'; //the name of the table

    public $uid;
    public $number;
/**
 * @TODO: [__construct description]
 */
    public function __construct() {
        // echo "Loaded numbersmodel";
    }


    public function getSingleInstance($class) {
        if($class->table == 'test') {
            return $this->find([
                'uid' => $class->id
            ]);
        }
    }

    public function getManyInstance($class) {
        if($class->table == 'test') {
            return $this->all([
                'uid' => $class->id
            ]);
        }
    }

}
