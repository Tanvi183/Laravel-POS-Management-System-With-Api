<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['namespace'=>'\App\Http\Controllers'],function(){
    Route::post('login','UsersController@login');
});

Route::group(['namespace'=>'\App\Http\Controllers','middleware'=>'auth:api'], function(){
    Route::get('users', 'Userscontroller@getusers');
    Route::post('users', 'Userscontroller@storeuser');
    Route::get('users/{id}', 'Userscontroller@singleuser');
    Route::put('users/{id}', 'UsersController@update');
    Route::delete('users/{id}', 'Userscontroller@delete');

    Route::apiResource('categories', 'Backend\CategoryController');
    Route::apiResource('sub-categories', 'Backend\SubCategoryController');
    Route::apiResource('brands', 'Backend\BrandController');
});

