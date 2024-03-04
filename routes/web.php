<?php

use App\Models\EmailAudit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    for ($i = 0; $i < 100; $i++) {

        $transaction_id =  Str::random(40);
        DB::table('api_audits')->insert(
            [
                'transaction_id' => $transaction_id,
                'user_id' => 1,
                'service_id' => 1,
                'created_at' => fake()->dateTimeBetween(Carbon::now()->subDays(30), Carbon::now())
            ]
        );

        DB::table('email_audits')->insert(
            [
                'transaction_id' => $transaction_id,
                'message' => 'test',
                'to' => fake()->email(),
                'subject' => fake()->sentence(),
                'service' => 'Contact form service',
                'template' => 'Contact Form',
                'created_at' => fake()->dateTimeBetween( Carbon::now()->subDays(30), Carbon::now())
            ]
        );
    }
});


Route::get('/', function () {
    return response()->json(['time' => time()]);
});
