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

class Middleware {

    private $middlewareCount = 0;
    private $count = 0;
    private $middlewares = [];
    private $callback;
    private $request;
    private $match;
/**
 * @TODO: [__construct description]
 * @param [type] $match    [description]
 * @param [type] $callback [description]
 */
    public function __construct($match, $callback) {

        $this->match = $match;
        $middlewares = $match["middleware"];
        if(is_array($middlewares)) {
            $this->middlewareCount = count($middlewares);
            $this->middlewares = $middlewares;
        }else {
            $this->middlewareCount = 1;
            $this->middlewares = [$middlewares];
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
        if($this->count < $this->middlewareCount) {
            $this->runCallback($request);
        }else {
            $callback = $this->callback;
            $callback($this->match);
        }
    }
/**
 * @TODO: [runCallback description]
 * @param  [type] $request [description]
 */
    protected function runCallback($request) {
        $middleware = $this->middlewares[$this->count];
        $class = \MVC\Core\DependencyInjection::inject('App\\Middlewares\\'.$middleware);
        $this->count++;
        $class->run($request, function($request) {
            $this->callNext($request);
        });


    }

}
