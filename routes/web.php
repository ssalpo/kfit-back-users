<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $params = [
        'title' => 'Подписка на 90 дней',
        'description' => 'Подписка на 90 дней 123',
        'price' => 2890
    ];

    $exists = DB::table('products')
//        ->where('email', '')
        ->whereRaw("platform->'$.gurucan'=?", '5faa63aa5e95387c036c7926')
        ->exists();

    $action = $exists ? 'update' : 'insert';

    DB::table('products')->{$action}($params);

//    /** @var \App\Services\ExternalCrmImport\Contracts\ImportContract $service */
//    $service = \App\Services\ExternalCrmImport\ExternalServiceManager::make('gurucan');
//
//    foreach ($service->products(1) as $product) {
//        DB::table('products')->updateOrInsert(
//            \Illuminate\Support\Arr::only($product, ['platform']),
//            $product
//        );
//    }
//
//    return view('welcome');
});
