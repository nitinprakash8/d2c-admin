<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::get('bookings', 'App\Http\Controllers\BookingController@getBookings')->name('bookings');

Route::post('book', 'App\Http\Controllers\BookingController@bookSeats')->name('book');

Route::get('reset-seats', 'App\Http\Controllers\BookingController@resetSeats')->name('reset-seats');
