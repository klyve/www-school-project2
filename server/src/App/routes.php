<?php
use MVC\Core\Route;
use MVC\Http\Request;

Route::get('/', 'SimpleJSONController.hello');
Route::get('/hello', 'SimpleJSONController.sayHelloJSON');



Route::post('user/login', 'AuthController.postLogin');
Route::post('user/register', 'AuthController.postRegister');

Route::get('/test', 'AuthController.getUser');

Route::put('/user/password', 'AuthController.putPassword', ['IsAuthenticated']);


Route::get('error', function() {
    return \MVC\Http\Response::statusCode(201, "hello world");
});

Route::get('graphql', function() {
    return json_encode([]);
});

Route::post('graphql', function() {
    return json_encode([]);
});


// Error class
Route::notFound(function() {
    echo "404 not found";
});

Route::onInternal(function() {
    die("ERROR 500");
    // return MVC\Core\View::render('500');
});
