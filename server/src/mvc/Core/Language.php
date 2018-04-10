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

class Language {

    private static $language;
    private static $data;

/**
 * @return true returns true
 */
    public static function init() {
        if($lang = Session::get('language')) {
            self::$language = $lang;
        }else {
            self::$language = Config::get('defaults.language.default');
        }

        self::loadFile();
        return true;
    }
/**
 * @return true returns true
 */
    private static function loadFile() {

        $langPath = Config::get('defaults.language.path');
        $filePath = APP_ROOT.'/'.$langPath.'/'.self::$language.'.json';
        if(!file_exists($filePath)) {
            $defaultLanguage = Config::get('defaults.language.default');
            $filePath = APP_ROOT.'/'.$langPath.'/'.$defaultLanguage.'.json';

        }

        self::$data = json_decode(file_get_contents($filePath), true);
        return true;
    }

/**
 * @param $name @TODO describe this parameter
 * @return findNested @TODO describe whats returned
 * @return false returns false
 */
    public static function get($name) {
        if(isset(self::$data[$name]))
            return self::$data[$name];
        $parts = explode('.', $name);
        if(count($parts) > 0) {
            return self::findNested(0, $parts, self::$data);
        }
        return false;
    }

    /**
     * @param $key @TODO describe this parameter
     * @param $value @TODO describe this parameter
     * @return true returns true
     */
    public static function set($key, $value) {
        self::$data[$key] = $value;
        return true;
    }

    /**
     * @param $pos @TODO describe this parameter
     * @param $parts @TODO describe this parameter
     * @param $location @TODO describe this parameter
     * @return false returns false
     * @return $location @TODO describe whats returned
     */
    private static function findNested($pos, $parts, $location) {
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
 * @return $data @TODO describe whats returned
 */
    public static function dump() {
        return self::$data;
    }
}
