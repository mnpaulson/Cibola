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
// Customer Routes
Route::post('customers/store', 'CustomerController@store')->name('customers.store');
Route::post('customers/delete', 'CustomerController@delete')->name('customers.delete');
Route::post('customers/show', 'CustomerController@show')->name('customers.show');
Route::post('customers/update', 'CustomerController@update')->name('customers.update');
Route::get('customers/index', 'CustomerController@index')->name('customers.index');
Route::get('customers/searchList', 'CustomerController@searchList')->name('customers.searchList');
Route::get('customers/recentCustomerList', 'CustomerController@recentCustomerList')->name('customers.recentCustomerList');

//Everything else vue
Route::get('/{any}', 'SpaController@index')->where('any', '.*');
