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

class SessionHandlerException extends \Exception {}
class SessionDisabledException extends SessionHandlerException {}
class InvalidArgumentTypeException extends SessionHandlerException {}
class ExpiredSessionException extends SessionHandlerException {}
class SessionUseOnlyCookiesException extends SessionHandlerException {}
class SessionHttpOnlyCookieException extends SessionHandlerException {}
class SessionCookieSecureException extends SessionHandlerException {}


class Session {
    protected static $SESSION_AGE = 3600;


/**
 * @param $key @TODO describe this parameter
 * @param $value @TODO describe this parameter
 * @return $value @TODO describe whats returned
 */
    public static function write($key, $value) {
        if ( !is_string($key) )
            throw new InvalidArgumentTypeException('Session key must be string value');
        self::_init();
        $_SESSION[$key] = $value;
        self::_age();
        return $value;
    }


/**
 * @param $key @TODO describe this parameter
 * @param $child @TODO describe this parameter
 * @return $_SESSION @TODO describe whats returned
 */
    public static function get($key, $child = false) {
        if ( !is_string($key) )
            throw new InvalidArgumentTypeException('Session key must be string value');
        self::_init();
        if (isset($_SESSION[$key])) {
            self::_age();

            if (false == $child) {
                return $_SESSION[$key];
            } else {
                if (isset($_SESSION[$key][$child])) {
                    return $_SESSION[$key][$child];
                }
            }
        }
        return false;
    }

/**
 * @return session_id @TODO describe whats returned
 */
    public static function getSessionId() {
      if(!isset($_ENV['SESSION_DRIVER']) || !isset($_ENV['SESSION_DRIVER']) || $_ENV['SESSION_DRIVER'] !== 'array')
        return session_id();
      return 1;
    }

/**
 * @param $key @TODO describe this parameter
 */
    public static function delete($key) {
        if ( !is_string($key) )
            throw new InvalidArgumentTypeException('Session key must be string value');
        self::_init();
        unset($_SESSION[$key]);
        self::_age();
    }
/**
 * @TODO: [dump description]
 * @return [type] [description]
 */
    public static function dump() {
        self::_init();
        echo nl2br(print_r($_SESSION));
    }

    /**
     * @return _init @TODO describe whats returned
     */
    public static function start() {
        return self::_init();
    }
/**
 * @TODO: [_age description]
 * @return [type] [description]
 */
    private static function _age() {
        $last = isset($_SESSION['LAST_ACTIVE']) ? $_SESSION['LAST_ACTIVE'] : false ;

        if (false !== $last && (time() - $last > self::$SESSION_AGE)) {
            self::destroy();
            throw new ExpiredSessionException();
        }
        $_SESSION['LAST_ACTIVE'] = time();
    }
/**
 * @TODO: [destroy description]
 * @return [type] [description]
 */
    public static function destroy() {
        if (self::getSessionId() !== '') {
            $_SESSION = array();

            if(!isset($_ENV['SESSION_DRIVER']) || $_ENV['SESSION_DRIVER'] !== 'array')
              session_destroy();
        }
    }

/**
 * @return session_start @TODO describe whats returned
 * @return session_regenerate_id @TODO describe whats returned
 */
    private static function _init() {
        if (function_exists('session_status')) {
            if (session_status() == PHP_SESSION_DISABLED)
                throw new SessionDisabledException();
        }

        if (self::getSessionId() === '') {
            $secure = true;
            $httponly = true;

            if (ini_set('session.use_only_cookies', 1) === false) {
                throw new SessionUseOnlyCookiesException();
            }

            if (ini_set('session.cookie_httponly', 1) === false) {
                throw new SessionHttpOnlyCookieException();
            }

            if(!isset($_ENV['SESSION_DRIVER']) || $_ENV['SESSION_DRIVER'] !== 'array')
              return session_start();
        }
        if(!isset($_ENV['SESSION_DRIVER']) || $_ENV['SESSION_DRIVER'] !== 'array')
          return session_regenerate_id(true);
    }

}
