<?php
use MVC\Core\Route;

Route::get('playlist', 'Test.playlist', ['Test', 'Test2']);


Route::get('/', 'PageController.getLandingPage');

Route::get('login', 'AuthController.getLogin');
Route::post('login', 'AuthController.postLogin');
Route::get('logout', 'AuthController.getLogout');

Route::get('register', 'AuthController.getRegister');

Route::post('register', 'AuthController.postRegister');

Route::get('index', 'PageController.getDashboard', ['LoggedIn']);



Route::get('admin', 'AdminUsersController.getDashboard', ['LoggedIn', 'IsAdmin']);
Route::post('admin', 'AdminUsersController.postUserGroup', ['LoggedIn', 'IsAdmin']);
Route::get('admin/search', 'AdminUsersController.getSearch', ['LoggedIn', 'IsAdmin']);

Route::get('admin/edituser', 'AdminUsersController.getEditUser', ['LoggedIn', 'IsAdmin']);
Route::post('admin/edituser', 'AdminUsersController.postEditUser', ['LoggedIn', 'IsAdmin']);

Route::get('admin/edituserinfo', 'AdminUsersController.getEditUserInfo', ['LoggedIn', 'IsAdmin']);
Route::post('admin/edituserinfo', 'AdminUsersController.postEditUserInfo', ['LoggedIn', 'IsAdmin']);

Route::get('watch', 'PageController.getVideoView', ['LoggedIn']);

Route::post('watch', 'PageController.postCommentsVideoView', ['LoggedIn']);

Route::get('testjson', function() {

    return [
        "hello" => "world"
    ];
});

Route::get('search', 'SearchController.getSearch', ['LoggedIn']);

Route::get('playlist', 'PageController.getPlaylist', ['LoggedIn']);
Route::post('playlist', 'PageController.postPlaylist', ['LoggedIn']);

Route::get('settings', 'SettingsController.getAvatar', ['LoggedIn']);
Route::get('settings/name', 'SettingsController.getName', ['LoggedIn']);
Route::get('settings/language', 'SettingsController.getLanguage', ['LoggedIn']);
Route::get('settings/password', 'SettingsController.getPassword', ['LoggedIn']);


Route::post('settings', 'SettingsController.postAvatar', ['LoggedIn']);
Route::post('settings/name', 'SettingsController.postName', ['LoggedIn']);
Route::post('settings/language', 'SettingsController.postLanguage', ['LoggedIn']);
Route::post('settings/password', 'SettingsController.postPassword', ['LoggedIn']);




// VideoManager
Route::get('manageComments', 'videoManagerController.getManageComments', ['LoggedIn', 'IsLecturer']);

Route::get('manageUpload', 'videoManagerController.getManageUploads', ['LoggedIn', 'IsLecturer']);
Route::post('manageUpload', 'videoManagerController.postManageUploads', ['LoggedIn', 'IsLecturer']);

Route::get('managePlaylists', 'videoManagerController.getManagePlaylists', ['LoggedIn', 'IsLecturer']);
Route::post('managePlaylists', 'videoManagerController.postManagePlaylists', ['LoggedIn', 'IsLecturer']);

Route::get('manageVideos', 'videoManagerController.getManageVideos', ['LoggedIn', 'IsLecturer']);

Route::get('editvideos', 'videoManagerController.getEditVideo', ['LoggedIn', 'IsLecturer']);
Route::post('editvideos', 'videoManagerController.postEditVideo', ['LoggedIn', 'IsLecturer']);




Route::notFound(function() {
    echo "404 not found";
});


Route::onInternal(function() {
    die("ERROR 500");
    // return MVC\Core\View::render('500');
});
