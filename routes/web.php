<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiGoldController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/',[ApiGoldController::class,'index'])->name('api.gold.index');
Route::get('/dashbord', function(){
    return view('dashbord');
});
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/', function () {
//     return view('home');
// })->name('home');


// /*
// |--------------------------------------------------------------------------
// | Authentication
// |--------------------------------------------------------------------------
// */

// Route::get('/register', [AuthController::class, 'showRegister'])
//     ->name('register');

// Route::post('/register', [AuthController::class, 'register'])
//     ->name('register.store');


// Route::get('/login', [AuthController::class, 'showLogin'])
//     ->name('login');

// Route::post('/login', [AuthController::class, 'login'])
//     ->name('login.store');


// Route::post('/logout', [AuthController::class, 'logout'])
//     ->name('logout');