<?php namespace App\Http\Validation;

use \MVC\Http\Response;
use \MVC\Http\Error;
use \MVC\Http\ErrorCode;
use \MVC\Core\Language;

class UserValidations {


    public function login($request, $next) {
        $validation = $request->validate([
            'email'                 => 'required|email',
            'password'              => 'required|min:6',
        ],[
            'name' => ['Language::get()'],
        ]);
        if($validation->fails()) {
            $statusCode = new Error(ErrorCode::get('user.email_exists'));
            $errors = $validation->errors();
            
            return Response::send($statusCode);
        }
        $next($request);
    }


    public function register($request, $next) {

        $next($request);
    }

    public function changePassword($request, $next) {

        $next($request);
    }
}