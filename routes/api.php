<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ResetPasswordController;
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
Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::post('create-movie/{id}', [MovieController::class, 'store'])->name('movie.store');
Route::get('movies-list', [MovieController::class, 'getList'])->name('movie.list');
Route::get('movie/{id}', [MovieController::class, 'getMovie'])->name('movie.get');
Route::get('delete-movie/{id}', [MovieController::class, 'delete'])->name('movie.delete');
Route::patch('update-movie/{id}', [MovieController::class, 'update'])->name('movie.update');

//create another route to verify email in other table

//more routes to be added
Route::group(['middleware' => 'jwt.auth'], function () {
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');
	Route::get('user', [UserController::class, 'index'])->name('user.index');
	Route::post('reset-password', [ResetPasswordController::class, 'SendResetLink'])->name('new.password.email');
	Route::post('update-user', [UserController::class, 'update'])->name('user.update');
});
Route::controller(ResetPasswordController::class)->group(function () {
	Route::post('forgot-password', 'email')->name('password.email');
	Route::post('password-recovery', 'update')->name('password.update');
});
Route::get('auth/redirect', [AuthController::class, 'googleAuthentication'])->name('google.auth');
Route::get('auth/callback', [AuthController::class, 'googleRedirect'])->name('google.redirect');
