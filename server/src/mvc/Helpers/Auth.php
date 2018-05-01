<?php namespace MVC\Helpers;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Helpers
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

class Auth {
    protected static $_user = false;

    public static function setUser($user) {
        self::$_user = $user;
    }

    public static function user() {
        return self::$_user;
    }
}
