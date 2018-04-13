<?php namespace App\Middlewares;


use App\Models as Model;

class Test {

    public function run($request, $next) {
        // var_dump($request, $next);
        // echo "<hr /><pre>";
        // var_dump($request->only(['id', 'test']));
        // echo "IN RUN 1<br />";
        $next($request);
    }
}