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
use \MVC\Core\Route;
use \MVC\Helpers\Hash;

class Request {

    protected $method;
    protected $_input = [];
    protected $_files = [];
    protected $_params = [];

    protected $_token = null;
    protected $_user = null;
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

        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);
        if(is_array($decoded)) {
            foreach($decoded as $key => $value) {
                $this->_input[$key] = $value;
            }
        }

        $this->_token = $this->getBearerToken();
        
        $this->_params = Route::$_requestParams;
    }


    /** 
     * Get hearder Authorization
     * https://stackoverflow.com/questions/40582161/how-to-properly-use-bearer-tokens?rq=1
     * */
    public function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    /**
    * get access token from header
    * */
    public function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s((.*)\.(.*)\.(.*))/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
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
        return $validator->validate(array_merge($this->_input, $this->_files, $this->_params), $rules, $messages);
    }

    public function params() {
        return $this->_params;
    }

    public function param($name) {
        if(isset($this->_params[$name])) {
            return $this->_params[$name];
        }

        return false;
    }

    public function token() {
        if($this->hasToken() && Hash::verifyJWT($this->_token))
            return Hash::getJWT($this->_token);
        return null;
    }
    public function hasToken() {
        return !!$this->_token;
    }


    public function setUser($user) {
        $this->_user = $user;
    }

    public function user() {
        return $this->_user;
    }

}
