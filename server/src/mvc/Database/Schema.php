<?php namespace MVC\Database;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Database
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

use \MVC\Core\Database as Database;
use \MVC\Core\DependencyInjection as DI;

class Schema {
/**
 * @TODO: [test description]
 * @return [type] [description]
 */
    public static function test() {
        echo "Running schema test \n";
    }
/**
 * @TODO: [create description]
 * @param  [type] $name [description]
 * @param  [type] $cb   [description]
 */
    public static function create($name, $cb) {
        $table = new Schema\Table($name);
        $cb($table);

        $query = $table->build();
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute();
        echo "Created table $name \n\r";
    }
/**
 * @TODO: [destroy description]
 * @param  [type] $name [description]
 */
    public static function destroy($name) {
        $table = new Schema\Table($name);
        $query = $table->destroy();
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute();
        echo "Dropped table $name \n\r";
    }
/**
 * @TODO: [truncate description]
 * @param  [type] $name [description]
 */
    public static function truncate($name) {
        $table = new Schema\Table($name);
        $query = $table->truncate();
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute();
        echo "Dropped table $name \n\r";
    }
/**
 * @TODO: [insert description]
 * @param  [type] $cb [description]
 * @return [type]     [description]
 */
    public static function insert($cb) {
        $instance = DI::inject($cb);
    }
}
