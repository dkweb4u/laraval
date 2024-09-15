<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

// User related routes
Route::get('/', [UserController::class,"homepage"])->name('login');
Route::post('/register', [UserController::class,"register"])->middleware('guest');
Route::post('/login', [UserController::class,"login"])->middleware('guest');
Route::post('/logout',[UserController::class,"logout"])->middleware('mustBeLoggedIn');;

// Blog related Routes
Route::get('/create-post',[PostController::class,"showCreatePost"])->middleware('mustBeLoggedIn');
Route::post('/create-post',[PostController::class,"storeNewPost"])->middleware('mustBeLoggedIn');
Route::get('/post/{post}',[PostController::class,"viewSinglePost"]);

// Profile  related routes
Route::get('/profile/{username:username}',[UserController::class,"profile"]);