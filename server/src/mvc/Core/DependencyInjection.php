<?php namespace MVC\Core;



class DependencyInjection {

    public static function Inject($className) {
        $reflection = new ReflectionClass($className);
        $params = $reflection->getConstructor()->getParameters();

        $class = null;
        $args = [];
        foreach ($params AS $param) {
            echo $param . "<br />";
            if($param->hasType()) {
                $className = $param->getClass()->name;
                $args[] = DI($className);
            }
        }
        
        $instance = $reflection->newInstanceArgs($args);
        return $instance;
    }

}