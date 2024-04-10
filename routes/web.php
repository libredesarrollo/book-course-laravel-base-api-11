<?php

use Illuminate\Support\Facades\Route;

Route::get('/vue/{n1?}/{n2?}/{n3?}', function () {
    return view('vue');
});