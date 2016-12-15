<?php

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
})//->middleware('auth:api')
;

Route::resource('questions', 'QuestionsController');


/*
 * Installation resource route
 */
// list installations
Route::get('installation', 'InstallationController@index');
// get installation
Route::get('installation/{id}', 'InstallationController@show');
// delete installation
Route::delete('installation/{id}', 'InstallationController@destroy');
// update installation
Route::put('installation', 'InstallationController@store');
// create installation
Route::post('installation', 'InstallationController@store');