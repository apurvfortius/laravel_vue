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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'API\AuthController@login');
Route::post('/register', 'API\AuthController@register');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', 'AuthController@logout');
    
    Route::apiResources([
        'users' => 'API\UserController',
        'roles' => 'API\RoleController',
        'permissions' => 'API\PermissionController',
        'business' => 'API\BusinessController',
        'productype' => 'API\ProductTypeController',
    ]);

    Route::get('getroles', 'API\UserController@getRoles');
    Route::get('profile', 'API\UserController@profile');
    Route::put('profile', 'API\UserController@updateprofile');
    Route::get('finduser', 'API\UserController@findUser');

    Route::get('findrole', 'API\RoleController@findRole');
    
    Route::get('getpermissions', 'API\PermissionController@getPermissions');
    Route::get('findpermission', 'API\PermissionController@findPermission');

    Route::get('findbusiness', 'API\BusinessController@findBusiness');

    Route::get('getbusiness', 'API\ProductTypeController@getBusiness');
    Route::get('findproductype', 'API\ProductTypeController@findProductType');
});



