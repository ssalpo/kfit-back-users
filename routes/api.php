<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api,api-clients']], function () {
    // User rotes
    Route::get('/users/goods/{type}', 'UserController@relatedGoodsByType');
    Route::get('/users/me', 'UserController@me');
    Route::post('/users/{user}/reset-password', 'UserController@resetPassword');
    Route::apiResource('/users', 'UserController');

    // File routes
    Route::post('/files/upload', 'FileController@upload');
    Route::get('/files/{model}/{folder}/{filename}', 'FileController@file');
    Route::get('/files/image/{model}/{folder}/{filename}/{width}/{height}', 'FileController@image');

    // Client routes
    Route::get('/clients/{client}/products', 'ClientController@products');
    Route::get('/clients/me', 'ClientController@me');
    Route::apiResource('/clients', 'ClientController');

    // Product routes
    Route::apiResource('products', 'ProductController');

    // Order routes
    Route::post('/orders/{order}/change-status', 'OrderController@changeStatus');
    Route::apiResource('orders', 'OrderController');
});
