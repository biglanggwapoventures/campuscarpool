<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {

        // Authentication and Registration related endpoints
        $api->group(['prefix' => 'auth'], function($api){

            $api->post('register', 'RegisterController');
            $api->post('login', 'LoginController');
            
            $api->group(['middleware' => 'api.auth'], function($api){
                $api->get('refresh', 'RefreshTokenController');
                $api->get('user', 'AuthenticatedUserController@getDetails');
            });

        });

        $api->group(['middleware' => 'api.auth'], function($api){

            $api->post('requirements', 'UserRequirements@save');

            // post new route
            $api->post('driver-route', 'DriverRouteController@store');

            // get route details
            $api->get('driver-route/{id}', 'DriverRouteController@fetch');

            // get active routes
            $api->get('driver-routes', 'DriverRouteController@all');

            // make a request to ride
            $api->post('driver-routes/request', 'DriverRouteController@request');

            // get all requests on a posted route
            $api->get('driver-routes/{id}/requests', 'DriverRouteController@getRequests');

            // respond to request (accept or reject)
            $api->patch('driver-routes/requests/{requestId}', 'DriverRouteController@setRequest');

            // get routes posted by the driver
            $api->get('my-routes', 'DriverRouteController@myRoutes');
            
            $api->get('conversation/{partnerId}', 'AuthenticatedUserController@getConversation');
            $api->post('conversation', 'AuthenticatedUserController@sendMessage');
            $api->get('conversation/{partnerId}/poll/{lastMessageId}', 'AuthenticatedUserController@pollConversation');

            $api->group(['prefix' => 'commuter'], function($api){
                $api->get('requests', 'CommuterController@getRequests');
            });
            
        });
        

           
    });
   
});


Route::get('/', 'WelcomeController')->name('portal');
// Auth::routes();

// Route::get('/home', 'HomeController@index');


