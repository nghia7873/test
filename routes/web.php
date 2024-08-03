<?php

use App\Http\Controllers\EmbedController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
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

Route::get('/embed/{id}', [EmbedController::class, 'index'])->name('embed');
Route::get('/crawler', [EmbedController::class, 'crawler']);
Route::get('/test', function () {
    //$response = Http::get('https://google.com');
 $response = Http::get('https://09042024-106.click/txt/hls14/e20b1475be3504b8690d1d87686306b4_360/e20b1475be3504b8690d1d87686306b4-0.aaa?m=m25');
    return $response->body();
});
