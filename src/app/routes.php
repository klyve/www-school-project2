<?php
use MVC\Core\Route;

Route::Get('/', function() {
    echo "WORKS";
});

// Route::Post('/', function() {
//     echo "Hello Post";
// });

// Route::Put('/', function() {
//     echo "Hello Put";
// });

// Route::Delete('/', function() {
//     echo "Hello Delete";
// });

Route::notFound(function() {
    echo "404 not found";
});

