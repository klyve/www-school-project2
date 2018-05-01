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


// 
//  USER ROUTES @TODO document this
// 

// UsersController
Route::get('/user', "UsersController.getUser")
    ->withMiddlewares(['IsAuthenticated']);

Route::put('/user', 'UsersController.putUser')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/user', 'UsersController.deleteUser')
    ->withMiddlewares(['IsAuthenticated']);

// AuthController
Route::post('/user/login', 'AuthController.postLogin')
    ->withValidators(['UserValidations.login']);

Route::post('/user/register', 'AuthController.postRegister')
    ->withValidators(['UserValidations.register']);

Route::put('/user/password', 'AuthController.putPassword')
    ->withMiddlewares(['IsAuthenticated'])
    ->withValidators(['UserValidations.changePassword']);

Route::post('user/refresh', 'AuthController.refreshToken')
    ->withMiddlewares(['IsAuthenticated']);

Route::get('testtoken', 'AuthController.updateToken')
    ->withMiddlewares(['SetActiveUser']);



//
// VIDEO ROUTES
//
// VideosController
Route::post('/video', 'VideosController.postVideo')
    ->withMiddlewares(['IsAuthenticated']);

Route::put('/video/{videoid}', 'VideosController.putVideo')
    ->withMiddlewares(['IsAuthenticated']);

        
Route::delete('/video/{videoid}', 'VideosController.deleteVideo')
    ->withMiddlewares(['IsAuthenticated']);


// Video rate controller
Route::put('/video/{videoid}/rate', 'VideoRatingsController.putRating')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/video/{videoid}/rate', 'VideoRatingsController.deleteRating')
    ->withMiddlewares(['IsAuthenticated']);


// CommentsController
Route::post('/video/{videoid}/comment', 'CommentsController.postComment')
    ->withMiddlewares(['IsAuthenticated']);

Route::put('/video/{videoid}/comment/{commentid}', 'CommentsController.putComment')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/video/{videoid}/comment/{commentid}', 'CommentsController.deleteComment')
    ->withMiddlewares(['IsAuthenticated']);


// VideoTags Controller
Route::post('/video/{videoid}/tag', 'VideoTagsController.postTag')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/video/{videoid}/tag/{tagname}', 'VideoTagsController.deleteTag')
    ->withMiddlewares(['IsAuthenticated']);


//
// PLAYLIST ROUTES
//

// PlaylistsController
Route::post('/playlist', 'PlaylistsController.postPlaylist')
    ->withMiddlewares(['IsAuthenticated']);

Route::put('/playlist/{playlistid}', 'PlaylistsController.putPlaylist')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/playlist/{playlistid}', 'PlaylistsController.deletePlaylist')
    ->withMiddlewares(['IsAuthenticated']);

// PlaylistVideosController
Route::post('/playlist/{playlistid}/video', 'PlaylistVideosController.postPlaylistVideo')
    ->withMiddlewares(['IsAuthenticated']);

Route::post('playlist/{playlistid}/reorder', 'PlaylistVideosController.reorderPlaylist')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/playlist/{playlistid}/video/{id}', 'PlaylistVideosController.deletePlaylistVideo')
    ->withMiddlewares(['IsAuthenticated']);


// SubscriptionController
Route::post('/playlist/{playlistid}/subscribe', 'SubscriptionsController.postSubscriptions')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/playlist/{playlistid}/subscribe', 'SubscriptionsController.deleteSubscriptions')
    ->withMiddlewares(['IsAuthenticated']);


// PlaylisTagController
Route::post('/playlist/{playlistid}/tag', 'PlaylistTagController.postTag')
    ->withMiddlewares(['IsAuthenticated']);

Route::delete('/playlist/{playlistid}/tag/{tagname}', 'PlaylistTagController.deleteTag')
    ->withMiddlewares(['IsAuthenticated']);


//
// OTHER ROUTES
//
// FilesController
Route::post('/tempfile', 'FilesController.postTempfile')
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

Route::get('graphql', 'GraphQLController.query')
    ->withMiddlewares(['SetActiveUser']);
Route::post('graphql', 'GraphQLController.query')
    ->withMiddlewares(['SetActiveUser']);


// Error class
Route::notFound(function() {
    echo "404 not found";
});

Route::onInternal(function() {
    die("ERROR 500");
    // return MVC\Core\View::render('500');
});
