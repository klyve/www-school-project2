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



// VideosController
Route::post('/video', 'VideosController.postVideo')
    ->withMiddlewares(['IsAuthenticated']);

Route::put('video/{videoid}', 'VideosController.putVideo')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('video/{videoid}', 'VideosController.deleteVideo')
    ->withMiddlewares(['IsAuthenticated']);


// FilesController
Route::post('/tempfile', 'FilesController.postTempfile')
    ->withMiddlewares(['IsAuthenticated']);



// UsersController
Route::get('/user', "UsersController.getUser");

Route::put('/user', 'UsersController.putUser')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/user', 'UsersController.deleteUser')
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
