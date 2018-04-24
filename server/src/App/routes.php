<?php
use MVC\Core\Route;
use MVC\Http\Request;
use App\Models\UsersModel;

// Route::get('/', 'SimpleJSONController.hello')
//     ->withMiddlewares(['IsAuthenticated']);
Route::get('/hello', 'SimpleJSONController.sayHelloJSON');

Route::get('/', function() {
    
    return ['hey'];
});



// ->withMiddlewares(['IsAuthenticated'])
//->withValidators(['UserValidations.login']);


Route::post('user/login', 'AuthController.postLogin')
    ->withValidators(['UserValidations.login']);

Route::post('user/register', 'AuthController.postRegister')
    ->withValidators(['UserValidations.register']);

Route::put('user/password', 'AuthController.putPassword')
    ->withMiddlewares(['IsAuthenticated'])
    ->withValidators(['UserValidations.changePassword']);

Route::post('user/refresh', 'AuthController.refreshToken')
    ->withMiddlewares(['IsAuthenticated']);



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



// PlaylistsController

Route::post('/playlist', 'PlaylistsController.postPlaylist')
    ->withMiddlewares(['IsAuthenticated']);

Route::put('/playlist/{playlistid}', 'PlaylistsController.putPlaylist')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/playlist/{playlistid}', 'PlaylistsController.deletePlaylist')
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


Route::get('test', functioN() {
    $users = new UsersModel();
    $users->search([
        'name' => 'username'
    ], 2);
    var_dump($users);
});

Route::get('graphql', 'GraphQLController.query');
Route::post('graphql', 'GraphQLController.query');


// Error class
Route::notFound(function() {
    echo "404 not found";
});

Route::onInternal(function() {
    die("ERROR 500");
    // return MVC\Core\View::render('500');
});
