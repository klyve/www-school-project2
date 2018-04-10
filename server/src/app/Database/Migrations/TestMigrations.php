<?php namespace App\Database\Migrations;
/**
 * This is a test migrator
 *
 * PHP version 7
 *
 *
 * @category   Migrators
 * @package    rewind
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

use \MVC\Database\Schema;

class TestMigrations {

/**
 * The up function creates a new table with a set of fields with its properties
 */

    public function up() {
        Schema::create('test2', function($table){
            $table->primary('id');
            $table->string('testString');
            $table->string('hello');
        });
    }

/**
 * The down function destroys the table
 */
 
    public function down() {
        Schema::destroy('test2');
    }

}
