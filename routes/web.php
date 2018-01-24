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
Route::post('customers/store', 'CustomerController@store')->name('customers.store');
Route::post('customers/show', 'CustomerController@show')->name('customers.show');
Route::get('/{any}', 'SpaController@index')->where('any', '.*');
