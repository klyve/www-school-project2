<?php
use MVC\Core\Route;


Route::get('/', 'SimpleJSONController.hello');
Route::get('/hello', 'SimpleJSONController.sayHelloJSON');

Route::post('/login', 'AuthController.postLogin');
Route::post('/register', 'AuthController.postRegister');

Route::get('/test', 'AuthController.getUser');

Route::put('/user/password', 'AuthController.putPassword');


// Error class
Route::notFound(function() {
    echo "404 not found";
});

Route::onInternal(function() {
    die("ERROR 500");
    // return MVC\Core\View::render('500');
});
