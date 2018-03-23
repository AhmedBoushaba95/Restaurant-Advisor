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

Route::post('login', 'API\PassportController@login');
Route::post('register', 'API\PassportController@register');

// Method Get WITHOUT header
Route::get('get-all-resto', 'RestoController@getDetails');
Route::get('get-menus-resto/{idResto}', 'RestoController@getMenus');
Route::get('/resto/{id}', 'RestoController@show');
Route::get('/menu/{idMenu}', 'MenusController@showMenu');
Route::get('/resto/get-all/{idResto}', 'RestoController@getAllInformations');
Route::get('/resto/get-all-resto/most-recent', 'RestoController@getAll_MostRecent');
Route::get('/resto/get-all-resto/note', 'RestoController@getAll_ByNote');
Route::get('/avis/get-all-avis/', 'AvisController@getAllAvis');
Route::get('/avis/get-avis-resto/{idResto}', 'AvisController@getAllAvisOfRestaurant');
Route::get('/avis/get-one-avis/{idAvis}', 'AvisController@getOpinion');

// Method Post WITHOUT header
Route::post('/resto/name', 'RestoController@showbyname');

// Routes group proteted by the authentification
Route::group(['middleware' => 'auth:api'], function() {
	// Method POST
	Route::post('/resto/register', 'RestoController@registerRestos');
	Route::post('/resto/update', 'RestoController@updateResto');
	Route::post('/menu/register', 'MenusController@registerMenu');
	Route::post('/menu/update/{menu_id}', 'MenusController@updateMenu');
	Route::post('avis/register/{idResto}', 'AvisController@registerAvis');
	Route::post('avis/update/{idAvis}', 'AvisController@updateAvis');

	// Method Get
	Route::get('get-details', 'API\PassportController@getDetails');
	Route::get('get-current-user', 'API\PassportController@getUser');
	Route::get('/resto/delete/{id}', 'RestoController@deleteRestos');
	Route::get('/menu/delete/{idMenu}', 'MenusController@deleteMenu');
	Route::get('avis/delete/{idAvis}', 'AvisController@deleteAvis');
});
