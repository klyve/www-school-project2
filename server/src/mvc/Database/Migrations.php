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

use \MVC\Helpers as Helpers;
use \App\Database\Migrations as AppMigrations;
use \MVC\Core\DependencyInjection as DI;

class Migrations {


    private static $migrationClasses = [];
/**
 * @TODO: [invalidType description]
 * @return [type] [description]
 */
    private static function invalidType() {
        echo "Requires :up, :down or :refresh\n\r";
    }
/**
 * @TODO: [matchFiles description]
 * @param  [type] $fileArray [description]
 * @return [type]            [description]
 */
    private static function matchFiles($fileArray) {
        if(count($fileArray) === 0)
            return self::$migrationClasses;
        // var_dump($fileArray);
        $ret = [];
        foreach($fileArray as $file) {
            if(in_array($file, self::$migrationClasses)) {
                $ret[] = $file;
            }
        }
        return $ret;
    }

/**
 * @TODO: [migrateUp description]
 * @param  [type] $files [description]
 */
    public static function migrateUp($files) {
        foreach($files as $file) {
            self::runMigration($file, 'up');
        }
    }
    /**
     * @TODO: [migrateDown description]
     * @param  [type] $files [description]
     */
    public static function migrateDown($files) {
        foreach($files as $file) {
            self::runMigration($file, 'down');
        }
    }
    /**
     * @TODO: [runMigration description]
     * @param  [type] $file [description]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public static function runMigration($file, $type) {
        // $object = $arr["object"];
        // $method = $arr["method"];
        $className = "\App\Database\Migrations\\$file";

        if(class_exists($className)) {
            DI::inject([
                "object" => $className,
                "method" => $type
            ]);
        }
    }
/**
 * @TODO: [runCLI description]
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
    public static function runCLI($type) {
        if(count($type) === 0)
            return self::invalidType();

        self::$migrationClasses = Helpers\File::getClassNames('App\Database\Migrations');
        $files = self::matchFiles(array_slice($type, 1));
        switch($type[0]) {
            case 'up': {
                echo "Running migrate:up\n\r";
                self::migrateUp($files);
            }break;
            case 'down': {
                echo "Running migrate:down\n\r";
                self::migrateDown($files);
            }break;
            case 'refresh': {
                echo "Running migrate:refresh\n\r";
                self::migrateDown($files);
                self::migrateUp($files);
            }break;
            default:
                return self::invalidType();
        }
    }
}
