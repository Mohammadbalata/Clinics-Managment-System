<?php

use App\Http\Controllers\Dashboard\ClinicController;
use App\Http\Controllers\Dashboard\ClinicInsuranceController;
use App\Http\Controllers\Dashboard\FAQController;
use App\Http\Controllers\Dashboard\InsurancesController;
use App\Http\Controllers\Dashboard\ProcedureController;
use App\Http\Controllers\Dashboard\RoomController;
use App\Http\Controllers\ProfileController;
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
    Route::resource('/insurances', InsurancesController::class);
    Route::resource('/clinics/{clinic}/faq', FAQController::class);

    Route::resource('/clinics/{clinic}/clinics-insurances', ClinicInsuranceController::class)
    ->except(['update','show','index','edit']);
    
    Route::resource('/rooms', RoomController::class);
    Route::resource('/procedures', ProcedureController::class);
});
