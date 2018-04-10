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

class DiException extends \Exception {}
class DiInvalidTypeException extends DiException {}
class DiNotFoundException extends DiException {}
class DiFunctionNotFoundException extends DiNotFoundException {}
class DiMethodNotFoundException extends DiNotFoundException {}
class DiClassNotFoundException extends DiNotFoundException {}

class DependencyInjection {

/**
 * @param $object @TODO describe this parameter
 * @return injectArray @TODO describe whats returned
 * @return injectFunction @TODO describe whats returned
 * @return injectClass @TODO describe whats returned
 */
    public static function inject($object) {
        switch(gettype($object)) {
            case is_array($object): {
                return self::injectArray($object);
            }break;
            case "object" && is_callable($object): {
                return self::injectFunction($object);
            }break;
            case "string" && class_exists($object): {
                return self::injectClass($object);
            }break;
            default:
                throw new DiInvalidTypeException('Unknown type for dependency injection');
        }

    }

/**
 * @param $arr @TODO describe this parameter
 * @return injectClass @TODO describe whats returned
 * @return injectFunction @TODO describe whats returned
 */
    public static function injectArray($arr) {
        $object = $arr["object"];
        $method = $arr["method"];
        if(is_string($object) && class_exists($object)) {
            return self::injectClass($object, $method);
        }
        if(is_callable($object)) {
            return self::injectFunction($object);
        }

    }

    /**
     * @param $params @TODO describe this parameter
     * @return $args @TODO describe whats returned
     */
    private static function parseParams($params) {
        $args = [];
        foreach ($params AS $param) {
            // echo $param . "<br />";
            if($param->hasType()) {
                $className = $param->getClass()->name;
                $args[] = self::inject($className);
            }
        }
        return $args;
    }

    /**
     * @param $className @TODO describe this parameter
     * @param $method @TODO describe this parameter
     * @return invokeArgs @TODO describe whats returned
     * @return $instance @TODO describe whats returned
     */
    public static function injectClass($className, $method = null) {
        $reflection = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();
        $args = [];
        if($constructor) {
            $params = $constructor->getParameters();
            $args = self::parseParams($params);
        }


        $instance = $reflection->newInstanceArgs($args);


        // Find parent class
        $reflection = new \ReflectionObject($instance);
        $parentReflection = $reflection->getParentClass();
        if($parentReflection) {
            $namespace = $parentReflection->getNamespaceName();
            $parts = explode('\\', $namespace);

            if(count($parts) > 0) {
                if($parts[0] !== 'MVC') {
                    // var_dump($parts);
                    if($parentReflection->hasMethod('dependencies')) {
                        $parentMethod = $parentReflection->getMethod('dependencies');
                        $parentParams = $parentMethod->getParameters();
                        $parentArguments = self::parseParams($parentParams);
                        $parentMethod->invokeArgs($instance, $parentArguments);
                    }

                }
            }

        }


        if($method !== null) {
            $methodReflection = $reflection->getMethod($method);
            if(!$methodReflection) die("Method does not exist!");

            $reflectionParams = $methodReflection->getParameters();
            $methodArgs = self::parseParams($reflectionParams);
            return $methodReflection->invokeArgs($instance, $methodArgs);
        }
        return $instance;
    }

    /**
     * @param $func @TODO describe this parameter
     * @param $args @TODO describe this parameter
     * @return invokeArgs @TODO describe whats returned
     */
    public static function injectFunction($func, $args = []) {
        $closure    = &$func;
        $reflection = new \ReflectionFunction($closure);
        $params = $reflection->getParameters();
        $args = self::parseParams($params);



        return $reflection->invokeArgs($args);
    }


}
