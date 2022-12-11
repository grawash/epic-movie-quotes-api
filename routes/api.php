<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
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

Route::controller(MovieController::class)->group(function () {
	Route::prefix('movies')->group(function () {
		Route::get('/', 'index')->name('movies.index');
		Route::post('/', 'store')->name('movies.store');
		Route::get('/{movie}', 'show')->name('movies.show');
		Route::delete('/{movie}', 'destroy')->name('movies.destroy');
		Route::patch('/{movie}', 'update')->name('movies.update');
	});
});
Route::controller(QuoteController::class)->group(function () {
	Route::prefix('quotes')->group(function () {
		Route::get('/', 'index')->name('quote.index');
		Route::post('/', 'store')->name('quote.store');
		Route::delete('/{quote}', 'destroy')->name('quote.destroy');
	});
});

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
