<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['namespace'=>'\App\Http\Controllers'],function(){
    Route::post('login','UsersController@login');
});

