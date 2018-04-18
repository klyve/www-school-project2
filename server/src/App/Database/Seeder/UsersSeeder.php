<?php namespace App\Database\Seeder;
/**
 * This is a seeder which fills the comments table with data for development reasons.
 * We use the CLI tool phptoolbox seed:up function to fill the table with data,
 * phptoolbox seed:down to truncate the tables data, and we can use phptoolbox seed:refresh
 * to do the down function first then the up function after.
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
use \MVC\Helpers\Hash;

class UsersSeeder {

/**
 * The up function fills the table with dummy data for development
 */

    public function up() {
        Schema::insert(function(Models\UsersModel $model) {

          for($i = 0; $i < 6; $i++) {
            $model->name = "username" . $i;
            $model->email = "useremail" . $i . "@kruskontroll.no";
            $model->password = Hash::password("password" . $i);
            $model->save();
          }
        });
    }

/**
 * The down function truncates the table and removes all data
 */

    public function down() {
        Schema::truncate('users');
    }
}
