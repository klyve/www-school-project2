<?php namespace App\Models;
/**
 * This is a test Model
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


class TestModel extends Model {

    public $table = 'test';
    public $id;
    public $name;
    public $age;

    public $numbers = NumbersModel::class;
    public $numbers2 = [NumbersModel::class];

    public $exclude = ['id'];

    public function __construct() {
        echo "Loaded Test model<br />";
    }


}
