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

Route::get('info', function() {
    return phpinfo();
});

// Home Route
Route::get('/', 'HomeController@index');

// Auth Routes
Auth::routes();

Route::group(['middleware' => 'auth'], function() {

    // User Profile Pages
    Route::get('profile/{user}', 'UsersController@profile')->name('users.profile');
    Route::put('users/{user}', 'UsersController@update')->name('users.update');

    Route::group(['middleware' => 'users.twilio'], function() {

        // User Routes
        Route::resource('users', 'UsersController', ['except' => ['update']]);

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

Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');
