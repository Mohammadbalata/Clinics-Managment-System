<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// instead of put the prefix & middleware in the app.php, I did it here, in case we need to add other web pages that doesn't require those prefix. 
Route::prefix('dashboard')
    ->middleware(['auth'])
    ->as('dashboard.')
    ->group(__DIR__ . '/dashboard.php');
require __DIR__ . '/auth.php';
