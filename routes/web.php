<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/debug', function () {

    $data = [
        'data' =>
        [
            "customer_name" => fake()->name(),
            "customer_email" => fake()->email(),
            "product_name" => fake()->catchPhrase()
        ],
        'to' => [
            'to' => fake()->email()
        ]
    ];
    return response()->json($data);
});


Route::get('/', function () {
    return response()->json(['time' => time()]);
});
