<?php namespace App\Database\Seeder;
/**
 * This is a testseeder
 *
 * PHP version 7
 *
 * @category   Seeders
 * @package    rewind
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */
use \MVC\Database\Schema;
use \App\Models as Models;
use \MVC\Core\Database as Database;
class TestSeeder {


    public function up() {
        // Schema::insert(function(Models\NumbersModel $model) {
        //     $model->uid = 3;
        //     $model->number = 3852628;
        //     $model->save();
        // });
    }
    public function down() {
        // Schema::truncate('numbers');
    }
}
