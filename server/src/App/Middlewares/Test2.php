<?php namespace App\Middlewares;


use App\Models as Model;

class Test2 {

    public function run($request, $next) {
        // var_dump($request, $next);
        
        // echo "IN run 2";
        // print_r($request->all());
        $next($request);
    }
}