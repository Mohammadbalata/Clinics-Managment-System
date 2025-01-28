<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['auth'],
    'as' => 'dashboard.',
    'prefix' => 'dashboard',
], function () {

    Route::get('/profile',[ProfileController::class,'edit'])
    ->name('profile.edit');
    

    Route::patch('/profile',[ProfileController::class,'update'])
    ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
    ->name('profile.destroy');



    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');

    

    



});


