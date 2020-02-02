<?php

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

Route::get('/','HomeController@index')->middleware('auth');


Auth::routes(['register' => false]);

/*Admin Routes*/
Route::group( ['middleware' => ['role:admin','auth']], function() {
    Route::prefix('')->as('admin.')->group(function () {
        Route::get('/home', 'HomeController@index')->name('home');
        Route::post('generate_users','Admin\\UsersController@generateUsers')->name('generate_users');
        Route::post('draw','Admin\\LuckyWinnersController@draw')->name('lucky_winners.draw');
        Route::resource('winning_numbers', 'Admin\\WinningNumbersController');;
    });
});
