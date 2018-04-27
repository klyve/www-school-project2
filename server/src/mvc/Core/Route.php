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

use \MVC\Http;

class Route {

    static $httpMethods = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];
    static $staticRoutes = [];
    static $_route = '/';

    static $_requestParams = [];
    

    
/**
 * @param $name @TODO describe this parameter
 * @param $callback @TODO describe this parameter
 * @param $middleware @TODO describe this parameter
 */
    public static function get($name, $callback, $middleware = false) {
        $obj = new RouteObject($name, $callback);
        self::$httpMethods["GET"][] = $obj;
        return $obj;
    }

/**
 * @param $name @TODO describe this parameter
 * @param $callback @TODO describe this parameter
 * @param $middleware @TODO describe this parameter
 */
    public static function post($name, $callback, $middleware = false) {
        $obj = new RouteObject($name, $callback);
        self::$httpMethods["POST"][] = $obj;
        return $obj;
    }

/**
 * @param $name @TODO describe this parameter
 * @param $callback @TODO describe this parameter
 * @param $middleware @TODO describe this parameter
 */
    public static function delete($name, $callback, $middleware = false) {
        $obj = new RouteObject($name, $callback);
        self::$httpMethods["DELETE"][] = $obj;
        return $obj;
    }

/**
 * @param $name @TODO describe this parameter
 * @param $callback @TODO describe this parameter
 * @param $middleware @TODO describe this parameter
 */
    public static function put($name, $callback, $middleware = false) {
        $obj = new RouteObject($name, $callback);
        self::$httpMethods["PUT"][] = $obj;
        return $obj;
    }

/**
 * @param $callback @TODO describe this parameter
 */
    public static function notFound($callback) {
        self::$staticRoutes['404'] = $callback;
    }

/**
 * @param $callback @TODO describe this parameter
 */
    public static function onInternal($callback) {
        self::$staticRoutes['500'] = $callback;
    }

/**
 * @TODO: [init description]
 * @return [type] [description]
 */
    public static function init() {
        try {
            $route = '/';
            if(isset($_GET['page']) && $_GET['page'] !== '') {
                $route = $_GET['page'];
            }elseif(isset($_SERVER['PATH_INFO'])) {
                $route = $_SERVER['PATH_INFO'];
            }
            self::$_route = $route;

            $serverMethod = $_SERVER['REQUEST_METHOD'];
            if(isset(self::$httpMethods[$serverMethod])) {
                self::match(self::$httpMethods[$serverMethod]);
            }else {
                self::match(self::$staticRoutes);
            }
        } catch(\Exception $e) {
            echo $e->getMessage();
            die();
        }
    }


/**
 * @param $route @TODO describe this parameter
 * @return $_route == $route @TODO describe whats returned
 */
    public static function isRoute($route) {
        return self::$_route == $route;
    }

/**
 * @return $_route @TODO describe whats returned
 */
    public static function getRoute() {
        return self::$_route;
    }


/**
 * @param $routes @TODO describe this parameter
 */
    protected static function match($routes) {
        $route = self::$_route;

        $match = self::regexMatch($routes, $route);
        if($match) {
            $fn = null;
            if(is_string($match->getCallback())) {
                $fn = self::buildClassQuery($match->getCallback());
            }else if(is_callable($match->getCallback())) {
                $fn = [
                    "object" => $match->getCallback(),
                    "method" => false
                ];
            }
            if($fn === null) {
                die("Internal server Error");
            }
            
            $fn["middleware"] = $match->getMiddlewares();
            $fn["validators"] = $match->getValidators();

            // var_dump($fn);
            
            self::callMiddlewares($fn, function($fn) {
                self::callValidators($fn, function($fn) {
                    Http\Response::send(DependencyInjection::inject($fn));
                });
            });

        }else {
            if(is_callable(self::$staticRoutes['404'])) {
                self::$staticRoutes['404']();
            }else {
                die("Internal server error");
            }
        }
    }

/**
 * @param $name @TODO describe this parameter
 * @return array @TODO describe whats returned
 */
    protected static function buildClassQuery($name) {
        $parts = explode('.', $name);
        if(!count($parts) == 2) {
            die("Error 500");
        }
        $className = $parts[0];
        $function = $parts[1];

        return [
            "object" => 'App\\Controllers\\'.$parts[0],
            "method" => $parts[1]
        ];
    }

    protected static function regexMatch($routes, $route) {
        foreach($routes as $obj) {
            if(preg_match($obj->getRegex(), trim($route, '/'), $matches)) {
                foreach ($matches as $k => $v) {
                    if (!is_int($k)) {
                        self::$_requestParams[$k] = $v;
                    }
                }
                return $obj;
            }
        }
        return false;
    }

    /**
     * @param $middlewares @TODO describe this parameter
     * @param $callback @TODO describe this parameter
     */
    protected static function callMiddlewares($middlewares, $callback) {
        $middleware = new \MVC\Http\Middleware($middlewares, $callback);
    }

    protected static function callValidators($validators, $callback) {
        $middleware = new \MVC\Http\Validation($validators, $callback);
    }

}
