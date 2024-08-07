<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/vue/{n1?}/{n2?}/{n3?}', function () {
    return view('vue');
});

Route::post('user/login', [LoginController::class, 'authenticate']);