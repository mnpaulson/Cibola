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
Route::get('employees/active', 'EmployeeController@active')->name('employees.active');
Route::get('employees/outstandingJobs', 'EmployeeController@outstandingJobs')->name('employees.outstandingJobs');
Route::get('employees/outstandingStats', 'EmployeeController@outstandingStats')->name('employees.outstandingStats');

//Job Routes
Route::post('jobs/create', 'JobController@create')->name('jobs.create');
Route::post('jobs/update', 'JobController@update')->name('jobs.update');
Route::post('jobs/delete', 'JobController@delete')->name('jobs.delete');
Route::post('jobs/index', 'JobController@create')->name('jobs.index');
Route::post('jobs/show', 'JobController@show')->name('jobs.show');
Route::post('jobs/customerJobs', 'JobController@customerJobs')->name('jobs.customerJobs');
Route::get('jobs/recentJobsList', 'JobController@recentJobsList')->name('jobs.recentJobsList');
Route::post('jobs/allJobsDetails', 'JobController@allJobsDetails')->name('jobs.allJobsDetails');
Route::post('jobs/complete', 'JobController@complete')->name('jobs.complete');
Route::post('jobs/uncomplete', 'JobController@uncomplete')->name('jobs.uncomplete');
Route::post('job_images/delete', 'Job_imageController@delete')->name('jobs.delete');


//Value Routes
Route::post('values/create', 'ValueController@create')->name('values.create');
Route::post('values/update', 'ValueController@update')->name('values.update');
Route::post('values/delete', 'ValueController@delete')->name('values.delete');
Route::get('values/index', 'ValueController@index')->name('values.index');
Route::get('values/active', 'ValueController@active')->name('values.active');
Route::get('values/gettype', 'ValueController@gettype')->name('values.getType');
Route::get('values/getGoldValue', 'ValueController@getGoldValue')->name('values.getGoldValue');
Route::get('values/getPlatValue', 'ValueController@getPlatValue')->name('values.getPlatValue');

//Gold Credit Routes
Route::post('goldcredit/create', 'GoldCreditController@create')->name('goldcredit.create');
Route::post('goldcredit/delete', 'GoldCreditController@delete')->name('goldcredit.delete');
Route::post('goldcredit/update', 'GoldCreditController@update')->name('goldcredit.update');
Route::post('goldcredit/show', 'GoldCreditController@show')->name('goldcredit.show');
Route::post('goldcredit/allCreditsDetails', 'GoldCreditController@allCreditsDetails')->name('goldcredit.allCreditsDetails');
Route::post('goldcredit/customerCredits', 'GoldCreditController@customerCredits')->name('goldcredit.customerCredits');









//Everything else vue
Route::get('/{any}', 'SpaController@index')->where('any', '.*');
