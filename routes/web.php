<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// Customer Routes
Route::post('customers/store', 'CustomerController@store')->name('customers.store');
Route::post('customers/delete', 'CustomerController@delete')->name('customers.delete');
Route::post('customers/show', 'CustomerController@show')->name('customers.show');
Route::post('customers/update', 'CustomerController@update')->name('customers.update');
Route::get('customers/index', 'CustomerController@index')->name('customers.index');
Route::get('customers/searchList', 'CustomerController@searchList')->name('customers.searchList');
Route::get('customers/recentCustomerList', 'CustomerController@recentCustomerList')->name('customers.recentCustomerList');

//Employee Routes
Route::post('employees/create', 'EmployeeController@create')->name('employees.create');
Route::post('employees/update', 'EmployeeController@update')->name('employees.update');
Route::post('employees/delete', 'EmployeeController@delete')->name('employees.delete');
Route::get('employees/index', 'EmployeeController@index')->name('employees.index');

//Job Routes
Route::post('jobs/create', 'JobController@create')->name('jobs.create');
Route::post('jobs/update', 'JobController@create')->name('jobs.update');
Route::post('jobs/delete', 'JobController@create')->name('jobs.delete');
Route::post('jobs/index', 'JobController@create')->name('jobs.index');



//Everything else vue
Route::get('/{any}', 'SpaController@index')->where('any', '.*');
