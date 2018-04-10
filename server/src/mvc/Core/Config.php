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

class Config {

    private static $configData = [];

    /**
     * @param $configFile @TODO describe this parameter
     * @return true Returns true
     */
    public static function init($configFile) {
        self::$configData = $configFile;
        return true;
    }
    /**
     * @param $name @TODO describe this parameter
     * @return $configData @TODO describe whats returned
     * @return findNested @TODO describe whats returned
     * @return false returns false
     */
    public static function get($name) {
        if(isset(self::$configData[$name])) {
            return self::$configData[$name];
        }
        $parts = explode('.', $name);
        if(count($parts) > 0) {
            return self::findNested(0, $parts, self::$configData);
        }
        return false;
    }

    /**
     * @param $pos @TODO describe this parameter
     * @param $parts @TODO describe this parameter
     * @param $location @TODO describe this parameter
     * @return findNested @TODO describe whats returned
     * @return false returns false
     * @return $location @TODO describe whats returned
     */
    public static function findNested($pos, $parts, $location) {
        if($pos != count($parts)) {
            if(isset($location[$parts[$pos]])) {
                return self::findNested($pos+1, $parts, $location[$parts[$pos]]);
            }
            return false;
        }else {
            return $location;
        }
    }
    /**
     * @param $key @TODO describe this parameter
     * @param $value @TODO describe this parameter
     * @return true returns true
     */
    public static function set($key, $value) {
        self::$configData[$key] = $value;
        return true;
    }

    /**
     * @param $name @TODO describe this parameter
     * @param $value @TODO describe this parameter
     * @return $cfg == $value @TODO describe whats returned
     * @return false returns false
     */
    public static function is($name, $value) {
        if($cfg = self::get($name)) {
            return $cfg == $value;
        }
        return false;
    }

    /**
     * @return $configData @TODO describe whats returned
     */
    public static function dump() {
        return self::$configData;
    }
}
