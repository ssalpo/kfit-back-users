<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    // User rotes
    Route::get('/users/me', 'UserController@me');
    Route::post('/users/{user}/reset-password', 'UserController@resetPassword');
    Route::apiResource('/users', 'UserController')->except(['destroy']);

    // File routes
    Route::post('/files/upload', 'FileController@upload');
    Route::get('/files/{folder}/{filename}', 'FileController@file');
    Route::get('/files/image/{filename}/{width}/{height}', 'FileController@image');

    // Product routes
    Route::apiResource('products', 'ProductController');
});
