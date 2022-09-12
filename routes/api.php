<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/users/me', 'UserController@me');
    Route::apiResource('/users', 'UserController')->except(['delete']);

    Route::post('file/upload', 'FileController@upload');
    Route::get('file/{folder}/{filename}', 'FileController@file')->name('file.show');

    Route::apiResource('products', 'ProductController');
});
