<?php namespace MVC\Http;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   HTTP
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

use \Rakit\Validation\Validator;


class Request {

    protected $method;
    protected $_input = [];
    protected $_files = [];
    /**
     * [__construct description]
     */
    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        foreach($_REQUEST as $key => $request) {
            $this->_input[$key] = $request;
        }
        foreach($_FILES as $key => $value) {
            $this->_files[$key] = $value;
        }
    }

/**
 * @TODO: [only description]
 * @param  [type] $arr [description]
 * @return [type]      [description]
 */
    public function only($arr) {
        $ret = [];
        foreach($arr as $key) {
            if(isset($this->_input[$key])) {
                $ret[$key] = $this->_input[$key];
            }
        }
        return $ret;
    }
/**
 * @TODO: [files description]
 * @return [type] [description]
 */
    public function files() {
        return $this->_files;
    }
/**
 * @TODO: [getFile description]
 * @param  [type] $key [description]
 * @return [type]      [description]
 */
    public function getFile($key) {
        if(isset($this->_files[$key]))
            return $this->_files[$key];
        return false;
    }
/**
 * @TODO: [all description]
 * @return [type] [description]
 */
    public function all() {
        return $this->_input;
    }
    /**
     * @TODO: [input description]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function input($name) {
        if(isset($this->_input[$name])) {
            return $this->_input[$name];
        }
        return false;
    }
/**
 * @TODO: [isMethod description]
 * @param  [type]  $name [description]
 * @return boolean       [description]
 */
    public function isMethod($name) {
        return strtolower($this->method) === strtolower($name);
    }
/**
 * @TODO: [validate description]
 * @param  [type] $rules    [description]
 * @param  array  $messages [description]
 * @return [type]           [description]
 */
    public function validate($rules, $messages = []) {
        $validator = new Validator;
        return $validator->validate(array_merge($this->_input, $this->_files), $rules, $messages);
    }

}
