<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;


// Gate in inside controller
// Route::get('admin',function(){

//     if(Gate::allows('visitAdminPages')){
//         return 'Admin Can only access the page';
//     }
//     else{
//         return 'Not Allowed';
//     }

// });

// Gate in call middleware
Route::get('admin',function(){
    return 'Admin Can only access the page';

})->middleware('can:visitAdminPages');

// User related routes
Route::get('/', [UserController::class,"homepage"])->name('login');
Route::post('/register', [UserController::class,"register"])->middleware('guest');
Route::post('/login', [UserController::class,"login"])->middleware('guest');
Route::post('/logout',[UserController::class,"logout"])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar',[UserController::class,"manageAvatar"])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar',[UserController::class,"updateAvatar"])->middleware('mustBeLoggedIn');


// Blog related Routes
Route::get('/create-post',[PostController::class,"showCreatePost"])->middleware('mustBeLoggedIn');
Route::post('/create-post',[PostController::class,"storeNewPost"])->middleware('mustBeLoggedIn');
Route::get('/post/{post}',[PostController::class,"viewSinglePost"]);
Route::delete('/post/{post}',[PostController::class,"delete"])->middleware('can:delete,post');
Route::get('/post/{post}/edit',[PostController::class,"showEditPost"])->middleware('can:update,post');
Route::put('/post/{post}',[PostController::class,"updatePost"])->middleware('can:update,post');


// Profile  related routes
Route::get('/profile/{username:username}',[UserController::class,"profile"]);