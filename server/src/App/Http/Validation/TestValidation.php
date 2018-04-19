<?php namespace App\Http\Validation;


class TestValidation {


    public function test($request, $next) {
        $next($request);
    }

    public function test2($request, $next) {
        // $next($request);
    }
}