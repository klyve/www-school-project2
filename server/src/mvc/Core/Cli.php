<?php namespace MVC\Core;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Core
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

class Cli {


    protected static $_commands = [
        'migrate' => \MVC\Database\Migrations::class,
        'seed' => \MVC\Database\Seeder::class,
    ];

    protected static $_excluded = [
        'toolbox'
    ];
/**
 * @TODO: [init description]
 * @param  [type] $argv [description]
 * @return [type]       [description]
 */
    public static function init($argv) {
        self::parseCommands($argv, 0);
    }

    /**
     * @param $argv @TODO describe this parameters
     * @param $position @TODO describe this parameters
     * @return parseCommands @TODO describe whats returned
     */
    public static function parseCommands($argv, $position) {
        if(!isset($argv[$position])) return true;

        $value = $argv[$position];

        if(in_array($value, self::$_excluded))
            return self::parseCommands($argv, $position+1);
        $parts = explode(':', $value);
        $arguments = [];
        if(count($parts) > 1) {
            $arguments = array_splice($parts, 1);
        }
        if(isset(self::$_commands[$parts[0]])) {

            for($i = $position+1; $i < count($argv); ++$i) {
                if(!isset($argv[$i])) continue;
                $subParts = explode(':', $argv[$i]);
                if(isset(self::$_commands[$subParts[0]])) {
                    break;
                }else {
                    $position++;
                    $arguments[] = $argv[$i];
                }
            }
            if(is_array(self::$_commands[$parts[0]])) {
              foreach(self::$_commands[$parts[0]] as $part) {
                $part::runCli($arguments);
              }
            }else {
                self::$_commands[$parts[0]]::runCli($arguments);
            }
        }

        return self::parseCommands($argv, $position+1);
    }

}
