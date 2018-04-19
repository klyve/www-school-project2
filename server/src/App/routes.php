<?php
use MVC\Core\Route;
use MVC\Http\Request;

// Route::get('/', 'SimpleJSONController.hello')
//     ->withMiddlewares(['IsAuthenticated']);
Route::get('/hello', 'SimpleJSONController.sayHelloJSON');


Route::get('/', function() {
    return ['hey'];
})
// ->withMiddlewares(['IsAuthenticated'])
->withValidators(['UserValidations.login']);


Route::post('user/login', 'AuthController.postLogin')
    ->withValidators(['UserValidations.login']);

Route::post('user/register', 'AuthController.postRegister')
    ->withValidators(['UserValidations.register']);

Route::put('/user/password', 'AuthController.putPassword')
    ->withMiddlewares(['IsAuthenticated'])
    ->withValidators(['UserValidations.changePassword']);


Route::post('/user/{userid}/video', 'VideoController.postVideo')
    ->withMiddlewares(['IsAuthenticated']);

Route::put('/user/{userid}/video/{videoid}', 'VideoController.putVideo')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/user/{userid}/video/{videoid}', 'VideoController.deleteVideo')
    ->withMiddlewares(['IsAuthenticated']);

// Route::get('adsfgnjwd', 'gnrgwklgweg')
//     ->withMiddlewares(['IsAuthenticated'])
//     ->withValidators(['FileName.function.function.function']);

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
