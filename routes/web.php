<?php


Route::get('/', 'WelcomeController')->name('portal');
Auth::routes();

Route::get('/home', 'HomeController@index');
