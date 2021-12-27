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
// get all blocked users
Route::get('users/blocked', [UserController::class, 'blocked']);
// get customer id
Route::get('customer-id/{phone}', [AuthController::class, 'getCustomerID']);

// protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('user_info', [AuthController::class, 'getUser']);

    // user resource
    Route::resource('user', UserController::class)->except(['create', 'edit', 'store']);
    // search user by name or phone
    Route::post('user/search', [UserController::class, 'search']);
});
