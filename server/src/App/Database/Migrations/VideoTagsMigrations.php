<?php namespace App\Database\Migrations;
/**
 * This is a migrator which creates the comments table and sets the fields
 * name, type, primary key or not and length if applicable.
 * We use the CLI tool phptoolbox migrate:up to create the table, the phptoolbox migrate:down
 * to destroy the table and phptoolbox migrate:refresh to run the down function first, then
 * the up function.
 *
 * PHP version 7
 *
 *
 * @category   Migrators
 * @package    kruskontroll
 * @version    2.0
 * @link       https://bitbucket.org/klyve/imt2291-prosjekt2-v2018/
 * @since      File available since Release 2.0
 */

use \MVC\Database\Schema;
use \MVC\Core\Config;

class VideoTagsMigrations {

/**
 * The up function creates a new Tag table with name string as primary key.
 */

    public function up() {
        Schema::create('videotags', function($table){
            $table->primary('id')->autoIncrement();
            $table->number('videoid');
            $table->number('tagid');
        });
    }

/**
 * The down function destroys the Tag table.
 */

    public function down() {
        Schema::destroy('videotags');
    }

}
