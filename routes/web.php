<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiGoldController;



Route::get('/',[ApiGoldController::class,'index'])->name('api.gold.index');
Route::get('/dashbord', function(){
    return view('dashbord');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
