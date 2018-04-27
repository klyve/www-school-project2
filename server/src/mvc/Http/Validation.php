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

class Validation {

    private $validationCount = 0;
    private $count = 0;
    private $validations = [];
    private $callback;
    private $request;
    private $match;

    // These are my private parts ;)
    private $parts = [];
/**
 * @TODO: [__construct description]
 * @param [type] $match    [description]
 * @param [type] $callback [description]
 */
    public function __construct($match, $callback) {

        $this->match = $match;
        $validations = $match["validators"];
        if(is_array($validations)) {
            $this->validationCount = count($validations);
            $this->validations = $validations;
        }else {
            $this->validationCount = 1;
            $this->validations = [$validations];
        }
        $this->callback = $callback;
        $this->request = new Request();
        $this->run();
    }

/**
 * @TODO: [run description]
 */
    public function run() {
        $this->callNext($this->request);
    }

/**
 * @TODO: [callNext description]
 * @param  [type] $request [description]
 */
    protected function callNext($request) {
        if($this->count < $this->validationCount) {
            $this->runCallback($request);
        }else {
            $callback = $this->callback;
            $callback($this->match);
        }
    }


    protected function callRecursive() {
        $index = $this->parts["index"];
        $instance = $this->parts["instance"];
        $parts = $this->parts["parts"];
        $request = $this->parts["request"];

        if($index == count($parts)) {
            $this->parts = [];
            return $this->callNext($request);
        }
        $method = $parts[$index];
        $this->parts["index"]++;
        $instance->$method($request, function($request) {
           $this->callRecursive();
        });
    }
/**
 * @TODO: [runCallback description]
 * @param  [type] $request [description]
 */
    protected function runCallback($request) {
        $validations = $this->validations[$this->count];
        $parts = explode('.', $validations);
        $validation = $parts[0];
        array_shift($parts);

        $class = \MVC\Core\DependencyInjection::inject('App\\Http\Validation\\'.$validation);
        $this->count++;
        $this->parts = [
            "parts" => $parts,
            "index" => 0,
            "instance" => $class,
            "request" => $request
        ];
        $this->callRecursive();
    }

}
