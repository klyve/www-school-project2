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

class Database {
    private static $connection = false;
    /**
     * @return $connection @TODO describe whats returned
     */
    public static function instance() {
        global $config;
        if(!self::$connection) {
            self::$connection = new \PDO('mysql:dbname='.$config['database']['database'].';host=' . $config['database']['host'], $config['database']['username'], $config['database']['password'],
            array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        }
        return self::$connection;
    }
}
