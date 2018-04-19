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



class RouteObject {


    private $_route;
    private $_regex;
    private $_callback = null;
    private $_middlewares = [];
    private $_validators = [];

    public function __construct($route, $callback) {
        $this->_route = $route;
        $this->_regex = $this->regexReplace($route);
        $this->_callback = $callback;
    }

    public function getRegex() {
        return $this->_regex;
    }

    public function getCallback() {
        return $this->_callback;
    }
    public function getMiddlewares() {
        return $this->_middlewares;
    }
    public function getValidators() {
        return $this->_validators;
    }


    public function regexReplace($string) {
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

    public function withMiddlewares($middlewares) {
        $this->_middlewares = array_merge($this->_middlewares, $middlewares);
        return $this;
    }

    public function withValidators($validators) {
        $this->_validators = array_merge($this->_validators, $validators);
        return $this;
    }

}