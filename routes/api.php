<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/users/me', 'UserController@me');
    Route::apiResource('/users', 'UserController')->except(['destroy']);

    Route::post('files/upload', 'FileController@upload');
    Route::get('files/{folder}/{filename}', 'FileController@file');
    Route::get('files/image/{filename}/{width}/{height}', 'FileController@image');

    Route::apiResource('products', 'ProductController');
});
