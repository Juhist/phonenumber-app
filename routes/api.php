<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
Route::get('/users',                                    [UserController::class, 'index']);
Route::post('/user',                                    [UserController::class, 'create']);
Route::prefix('users')->group(
    function () {
        Route::get('{id}',                              [UserController::class, 'view']);
        Route::delete('{id}',                           [UserController::class, 'delete']);

        Route::get('/phone-numbers',                    [PhoneNumberController::class, 'index']);
        Route::post('/phone-numbers',                   [PhoneNumberController::class, 'create']);
        Route::prefix('phone-numbers')->group(
            function () {
                Route::get('{id}',                      [PhoneNumberController::class, 'view']);
                Route::delete('{id}',                   [PhoneNumberController::class, 'delete']);
            }
        );        
    }
); 