<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {

        // Authentication and Registration related endpoints
        $api->group(['prefix' => 'auth'], function($api){

            $api->post('register', 'RegisterController');
            $api->post('login', 'LoginController');

            $api->post('login', 'LoginController');
            
            $api->group(['middleware' => 'api.auth'], function($api){
                $api->get('refresh', 'RefreshTokenController');
                $api->get('user', 'AuthenticatedUserController@getDetails');

                $api->get('lacking-requirements', 'AuthenticatedUserController@getStatus');

                $api->post('update-basic-information', 'AuthenticatedUserController@updateBasicInformation');
                $api->post('change-password', 'AuthenticatedUserController@changePassword');
            });

        });

        $api->group(['middleware' => 'api.auth'], function($api){

            $api->post('report', 'ReportController@store');

            $api->group(['prefix' => 'admin'], function($api){
                $api->get('users', 'AdminController@users');
                $api->get('user/{id}/history', 'AdminController@userHistory');
                $api->get('reports', 'AdminController@reports');
                $api->get('user/{id}', 'AdminController@fetchUser');
                $api->patch('user/{id}', 'AdminController@moderateUser');
            });
            

            $api->group(['prefix' => 'ride-requests'], function($api){

                $api->group(['prefix' => 'driver'], function($api){
                    $api->get('{id}', 'DriverRideRequestController@fetch');
                    $api->post('{id}/accept', 'DriverRideRequestController@accept');
                    $api->post('{id}/reject', 'DriverRideRequestController@reject');
                });

                $api->group(['prefix' => 'commuter'], function($api){
                    // get all reide request with params
                    $api->get('/', 'CommuterRideRequestController@all');

                    //get commuter active reqeust
                    $api->get('/active', 'CommuterRideRequestController@active');

                    $api->get('{id}', 'CommuterRideRequestController@fetch');
                    $api->post('{id}/accept', 'CommuterRideRequestController@accept');
                    $api->post('{id}/reject', 'CommuterRideRequestController@reject');
                    $api->post('{id}/rate', 'CommuterRideRequestController@rate');
                    $api->delete('{id}', 'CommuterRideRequestController@cancel');
                });
            });

            $api->get('carpool/{routeId}', 'DriverRouteController@fetchAll');
            $api->post('carpool/{routeId}/rating', 'DriverRouteController@saveRating');

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
            $api->get('driver-routes/requests/{requestId}', 'DriverRouteController@getRequest');

            // get routes posted by the driver
            $api->get('my-routes', 'DriverRouteController@myRoutes');
            
            $api->get('conversation/{partnerId}', 'AuthenticatedUserController@getConversation');
            $api->post('conversation', 'AuthenticatedUserController@sendMessage');
            $api->get('conversation/{partnerId}/poll/{lastMessageId}', 'AuthenticatedUserController@pollConversation');

            $api->group(['prefix' => 'commuter'], function($api){
                $api->get('requests', 'CommuterController@getRequests');
                $api->get('has-active-request', 'CommuterController@hasActiveRideRequest');
            });
        });
    });
});


Route::get('/', 'WelcomeController')->name('portal');
// Auth::routes();

// Route::get('/home', 'HomeController@index');


