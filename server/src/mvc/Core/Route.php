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
    

    public static function regexReplace($string) {
        $matches = [];
        $pattern = '/\{(\w+)\}/';
        $string = trim($string, '/');
        preg_match_all($pattern, $string, $matches);
        if(count($matches) > 0) {
            foreach($matches[0] as $key => $value) {
                $string = preg_replace('/'.$value.'/', '(?<'.$matches[1][$key].'>.+)', $string);
            }
            $string = preg_replace('/\//', '\/', $string);
        }
        $string = '/(?<uri>^'.$string.'$)/';
        return $string;
    }
/**
 * @param $name @TODO describe this parameter
 * @param $callback @TODO describe this parameter
 * @param $middleware @TODO describe this parameter
 */
    public static function get($name, $callback, $middleware = false) {
        self::$httpMethods["GET"][] = [
            "fn" => $callback,
            "middleware" => $middleware,
            "uri" =>self::regexReplace($name),
        ];
    }

/**
 * @param $name @TODO describe this parameter
 * @param $callback @TODO describe this parameter
 * @param $middleware @TODO describe this parameter
 */
    public static function post($name, $callback, $middleware = false) {
        self::$httpMethods["POST"][] = [
            "fn" => $callback,
            "middleware" => $middleware,
            "uri" =>self::regexReplace($name),
        ];
    }

/**
 * @param $name @TODO describe this parameter
 * @param $callback @TODO describe this parameter
 * @param $middleware @TODO describe this parameter
 */
    public static function delete($name, $callback, $middleware = false) {
        self::$httpMethods["DELETE"][] = [
            "fn" => $callback,
            "middleware" => $middleware,
            "uri" =>self::regexReplace($name),
        ];
    }

/**
 * @param $name @TODO describe this parameter
 * @param $callback @TODO describe this parameter
 * @param $middleware @TODO describe this parameter
 */
    public static function put($name, $callback, $middleware = false) {
        self::$httpMethods["PUT"][] = [
            "fn" => $callback,
            "middleware" => $middleware,
            "uri" =>self::regexReplace($name),
        ];
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
            }
            self::$_route = $route;

            $serverMethod = $_SERVER['REQUEST_METHOD'];
            if(isset(self::$httpMethods[$serverMethod])) {
                self::match(self::$httpMethods[$serverMethod]);
            }else {
                self::match(self::$staticRoutes);
            }
        } catch(\Exception $e) {
            die("ERROR COULD NOT DO SOMETHING");
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


    protected static function regexMatch($routes, $route) {
        foreach($routes as $key => $value) {
            if(preg_match($value["uri"], trim($route, '/'), $matches)) {
                foreach ($matches as $k => $v) {
                    if (!is_int($k)) {
                        self::$_requestParams[$k] = $v;
                    }
                }
                return $value;
            }
        }
        return false;
    }

/**
 * @param $routes @TODO describe this parameter
 */
    protected static function match($routes) {
        $route = self::$_route;

        $match = self::regexMatch($routes, $route);
        if($match) {
            $fn = null;
            if(is_string($match["fn"])) {
                $fn = self::buildClassQuery($match["fn"]);
            }else if(is_callable($match["fn"])) {
                $fn = [
                    "object" => $match["fn"],
                    "method" => false
                ];
            }
            $fn["middleware"] = $match["middleware"];

            if($fn === null) {
                die("Internal server Error");
            }

            if($match["middleware"]) {
                self::callMiddlewares($fn, function($fn) {
                    Http\Response::send(DependencyInjection::inject($fn));
                });
            }else {
                Http\Response::send(DependencyInjection::inject($fn));
            }

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

    /**
     * @param $middlewares @TODO describe this parameter
     * @param $callback @TODO describe this parameter
     */
    protected static function callMiddlewares($middlewares, $callback) {
        $middleware = new \MVC\Http\Middleware($middlewares, $callback);
    }

}
