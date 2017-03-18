<?php
//header('Access-Control-Allow-Origin:  *');
//header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
//header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/user/permission', 'PermissionController@index')
    ->middleware('auth:api');


/**
 * TODO: REMOVE THIS!
 */

/* Installation resource route */
Route::get('installation', 'Installation\ItemController@index')
    ->middleware('auth:api', 'permission:installation.read');
Route::get('installation/{installationId}', 'Installation\ItemController@show')
    ->middleware('auth:api', 'permission:installation.read', 'auth.route.installation');
Route::delete('installation/{installationId}', 'Installation\ItemController@destroy')
    ->middleware('auth:api', 'permission:installation.delete', 'auth.route.installation');
Route::put('installation/{installationId}', 'Installation\ItemController@update')
    ->middleware('auth:api', 'permission:installation.update', 'auth.route.installation');
Route::post('installation', 'Installation\ItemController@create')
    ->middleware('auth:api', 'permission:installation.create');

/* Space resource route */
Route::get('space', 'Space\ItemController@index')
    ->middleware('auth:api', 'permission:space.read');
Route::get('space/{spaceId}', 'Space\ItemController@show')
    ->middleware('auth:api', 'permission:space.read', 'auth.route.space');
Route::delete('space/{spaceId}', 'Space\ItemController@destroy')
    ->middleware('auth:api', 'permission:space.delete', 'auth.route.space');
Route::put('space/{spaceId}', 'Space\ItemController@update')
    ->middleware('auth:api', 'permission:space.update', 'auth.route.space');
Route::post('space', 'Space\ItemController@create')
    ->middleware('auth:api', 'permission:space.create');

/* Device resource route */
Route::get('device', 'Device\ItemController@index')
    ->middleware('auth:api', 'permission:device.read');
Route::get('device/{deviceId}', 'Device\ItemController@show')
    ->middleware('auth:api', 'permission:device.read', 'auth.route.device');
Route::delete('device/{deviceId}', 'Device\ItemController@destroy')
    ->middleware('auth:api', 'permission:device.delete', 'auth.route.device');
Route::put('device/{deviceId}', 'Device\ItemController@update')
    ->middleware('auth:api', 'permission:device.update', 'auth.route.device');
Route::post('device', 'Device\ItemController@create')
    ->middleware('auth:api', 'permission:device.create');

/* Device data resource route */
Route::get('device/{deviceId}/data/{type}', function ($type, $device) {
        return App::call(sprintf('\App\Http\Controllers\Device\DataController@%s', $type), [$type, $device]);
    })
    ->where(['type' => 'energy|temperature|event|emergency'])
    ->middleware('auth:api', 'permission:device.read', 'auth.route.device', 'aware.data');

/* Device command resource route */
Route::put('device/{deviceId}/command/{type}', function ($type, $deviceId) {
        return App::call(sprintf('\App\Http\Controllers\Device\CommandController@%s', $type), [$type, $deviceId]);
    })
    ->where(['type' => 'on|off|lock|unlock|fullLock|dim|open|close|start|stop|set'])
    ->middleware('auth:api', 'permission:device.read', 'auth.route.device', 'aware.command');


//TODO: need to add route groups for shared middleware
//Route::group(['middleware' => 'auth'], function () {
//    Route::get('/', function ()    {
//        // Uses Auth Middleware
//    });
//
//    Route::get('user/profile', function () {
//        // Uses Auth Middleware
//    });
//});