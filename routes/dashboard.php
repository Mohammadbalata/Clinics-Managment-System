<?php

use App\Http\Controllers\Dashboard\ClinicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['auth'],
    'as' => 'dashboard.',
    'prefix' => 'dashboard',
], function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');


    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');





    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::resource('/clinics', ClinicController::class);
    Route::resource('/rooms', RoomController::class);
});
