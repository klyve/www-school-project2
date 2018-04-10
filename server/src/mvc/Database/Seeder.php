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

class Seeder {


    private static $seederClasses = [];
/**
 * @TODO: [invalidType description]
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
            return self::$seederClasses;

        $ret = [];
        foreach($fileArray as $file) {
            if(in_array($file, self::$seederClasses)) {
                $ret[] = $file;
            }
        }
        return $ret;
    }

/**
 * @TODO: [seedUp description]
 * @param  [type] $files [description]
 */
    public static function seedUp($files) {
        foreach($files as $file) {
            self::runSeeding($file, 'up');
        }
    }
    /**
     * @TODO: [seedDown description]
     * @param  [type] $files [description]
     * @return [type]        [description]
     */
    public static function seedDown($files) {
        foreach($files as $file) {
            self::runSeeding($file, 'down');
        }
    }
    /**
     * @TODO: [runSeeding description]
     * @param  [type] $file [description]
     * @param  [type] $type [description]
     */
    public static function runSeeding($file, $type) {
        $className = "\App\Database\Seeder\\$file";
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

        self::$seederClasses = Helpers\File::getClassNames('App\Database\Seeder');
        $files = self::matchFiles(array_slice($type, 1));
        switch($type[0]) {
            case 'up': {
                echo "Running seed:up\n\r";
                self::seedUp($files);
            }break;
            case 'down': {
                echo "Running seed:down\n\r";
                self::seedDown($files);
            }break;
            case 'refresh': {
                echo "Running seed:refresh\n\r";
                self::seedDown($files);
                self::seedUp($files);
            }break;
            default:
                return self::invalidType();
        }
    }
}
