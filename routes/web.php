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

// Auth Routes
Auth::routes();

Route::group(['middleware' => ['auth', 'impersonate']], function() {

    // User Profile Page
    Route::resource('users', 'UsersController', [
        'except' => [
            'show'
        ]
    ]);

    // Impersonation Routes
    Route::group(['middleware' => 'role:admin'], function() {
        Route::get('/users/impersonate/{user}', 'UsersController@impersonate');
    });
    Route::get('/users/stop', 'UsersController@stopImpersonate');

    // Routes that require Twilio integration
    Route::group(['middleware' => 'users.twilio'], function() {

        // Home Route
        Route::get('/', 'HomeController@index');

        // AutoDialer Routes
        Route::group(['prefix' => 'autodialer'], function() {
            Route::get('/', ['as'   => 'autodialer.index', 'uses' => 'AutoDialerController@index']);
            Route::post('/',['as'   => 'autodialer.store', 'uses' => 'AutoDialerController@placeCall']);
            Route::get('bulk', ['as'   => 'autodialer.bulk.index', 'uses' => 'AutoDialerController@bulkIndex']);
            Route::post('bulk', ['as'   => 'autodialer.bulk.store', 'uses' => 'AutoDialerController@bulkStore']);
            Route::get('bulk/{id}', ['as'   => 'autodialer.bulk.show', 'uses' => 'AutoDialerController@bulkShow']);
            Route::get('bulk/{id}/process', ['as'   => 'autodialer.bulk.process', 'uses' => 'AutoDialerController@bulkProcess']);
            Route::post('bulk/{id}/logfile', ['as'   => 'autodialer.bulk.logfile', 'uses' => 'AutoDialerController@bulkLogfile']);
            Route::delete('bulk/{id}', ['as'   => 'autodialer.bulk.destroy', 'uses' => 'AutoDialerController@bulkDestroy']);
            Route::resource('cdrs', 'CdrsController');
        });

        // Audio Messages Routes
        Route::resource('audio-messages', 'AudioMessagesController');

    });
});

// Social Login callbacks and redirects
Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
Route::get('/callback/{provider}', 'SocialAuthController@callback');
