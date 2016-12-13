<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {
        $api->group(['prefix' => 'auth'], function($api){
            $api->post('register', 'RegisterController');
            $api->post('login', 'LoginController');

            $api->group(['middleware' => 'api.auth'], function($api){
                $api->get('user', 'AuthenticatedUser');
            });
        });
    });
   
});


Route::get('/', 'WelcomeController')->name('portal');
// Auth::routes();

// Route::get('/home', 'HomeController@index');


