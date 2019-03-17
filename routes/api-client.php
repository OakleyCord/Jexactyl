<?php

use Pterodactyl\Http\Middleware\Api\Client\Server\AuthenticateServerAccess;

/*
|--------------------------------------------------------------------------
| Client Control API
|--------------------------------------------------------------------------
|
| Endpoint: /api/client
|
*/
Route::get('/', 'ClientController@index')->name('api.client.index');

Route::group(['prefix' => '/account'], function () {
    Route::get('/', 'AccountController@index')->name('api.client.account');

    Route::put('/email', 'AccountController@updateEmail')->name('api.client.account.update-email');
    Route::put('/password', 'AccountController@updatePassword')->name('api.client.account.update-password');
});

/*
|--------------------------------------------------------------------------
| Client Control API
|--------------------------------------------------------------------------
|
| Endpoint: /api/client/servers/{server}
|
*/
Route::group(['prefix' => '/servers/{server}', 'middleware' => [AuthenticateServerAccess::class]], function () {
    Route::get('/', 'Servers\ServerController@index')->name('api.client.servers.view');
    Route::get('/utilization', 'Servers\ResourceUtilizationController@index')
        ->name('api.client.servers.resources');

    Route::post('/command', 'Servers\CommandController@index')->name('api.client.servers.command');
    Route::post('/power', 'Servers\PowerController@index')->name('api.client.servers.power');

    Route::group(['prefix' => '/databases'], function () {
        Route::get('/', 'Servers\DatabaseController@index')->name('api.client.servers.databases');
        Route::post('/', 'Servers\DatabaseController@store');
        Route::delete('/{database}', 'Servers\DatabaseController@delete')->name('api.client.servers.databases.delete');
    });

    Route::group(['prefix' => '/files'], function () {
        Route::post('/download/{file}', 'Servers\FileController@download')
            ->where('file', '.*')
            ->name('api.client.servers.files.download');
    });
});
