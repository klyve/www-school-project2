<?php namespace MVC\Core;

use Controller;

class Route {
    
    static $httpMethods = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];
    static $getRoutes = [];
    static $postRoutes = [];
    

    public static function Get($name, $callback) {
        // self::$getRoutes[$name] = $callback;
        self::$httpMethods["GET"][$name] = $callback;
    }
    public static function Post($name, $callback) {
        // self::$postRoutes[$name] = $callback;
        self::$httpMethods["POST"][$name] = $callback;
    }
    public static function Delete($name, $callback) {
        self::$httpMethods["DELETE"][$name] = $callback;
    }
    public static function Put($name, $callback) {
        self::$httpMethods["PUT"][$name] = $callback;
    }

    public static function notFound($callback) {
        self::$getRoutes['404'] = $callback;
    }

    public static function init() {
        echo $_SERVER['REQUEST_METHOD'] . "<br />";
        $serverMethod = $_SERVER['REQUEST_METHOD'];
        if(isset(self::$httpMethods[$serverMethod])) {
            self::match(self::$httpMethods[$serverMethod]);
        }else {
            self::match(self::$getRoutes);
        }
        
        // if($_SERVER['REQUEST_METHOD'] === 'POST') {
        //    self::match(self::$postRoutes);
        // }else {
        //     self::match(self::$getRoutes);
        // }
    }

    protected static function match($routes) {
        $route = '/';
        
        if(isset($_GET['page']) && $_GET['page'] !== '') {
            $route = $_GET['page'];
        }
        if(isset($routes[$route])) {
            if(is_string($routes[$route])) {
                // call_user_func('App\\Controllers\\'.$routes[$route]);
                self::initCalledClass($routes[$route]);
            }else if(is_callable($routes[$route])) {
                $routes[$route]();
            }else {
                die("Internal server error");
            }
        }else {
            if(is_callable(self::$getRoutes['404'])) {
                self::$getRoutes['404']();
            }else {
                die("Internal server error");
            }
        }
    }

    protected static function initCalledClass($name) {
        $parts = explode('::', $name);
        die("IN HERE");
        if(!count($parts) == 2) {
            die("Error 500");
        }else {
            // $className = $parts[0];
            // $function = $parts[1];
            // $class = DependencyInjection::Inject('App\\Controllers\\'.$className);
            // $class->$function();
            die("IN HERE");
        }
    }

    protected static function inject($fn) {

    }
}