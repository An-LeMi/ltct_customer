<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// public routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
// get all active users
Route::get('users/active', [UserController::class, 'active']);
// get all inactive users
Route::get('users/inactive', [UserController::class, 'inactive']);
// get customer id
Route::get('customer-id/{phone}', [AuthController::class, 'getCustomerID']);

// protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'getUser']);
    // update user info
    Route::put('user/{id}', [UserController::class, 'update']);
    // delete user
    Route::delete('user/{id}', [UserController::class, 'destroy']);
});
