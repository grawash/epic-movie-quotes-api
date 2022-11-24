<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\UserController;
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

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('email/verify/{user:id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

//more routes to be added
Route::group(['middleware' => 'jwt.auth'], function () {
	Route::get('logout', [AuthController::class, 'logout'])->name('logout');
	Route::get('user', [UserController::class, 'index'])->name('user.index');
});
