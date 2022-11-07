<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.login.login');
});

//----------------------------Login&Registration----------------------------//
Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login');
Route::get('/registration', [AuthController::class, 'registration']);
Route::post('/registration', [AuthController::class, 'registrationSubmit'])->name('registration');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
//----------------------------Home----------------------------//
Route::get('/home', [HomeController::class, 'home'])->name('home')->middleware('VerifyUser');
Route::post('/home', [PostController::class, 'addPost'])->name('addPost')->middleware('VerifyUser');
//----------------------------Post----------------------------//
Route::post('/home', [PostController::class, 'addPost'])->name('addPost')->middleware('VerifyUser');
Route::get('/post/{id}', [PostController::class, 'post'])->name('post')->middleware('VerifyUser');
Route::get('/post/{id}/delete', [PostController::class, 'deletePost'])->name('deletePost')->middleware('VerifyUser');
Route::get('/post/{id}/edit', [PostController::class, 'editPost'])->name('editPost')->middleware('VerifyUser');
Route::post('/post/{id}/edit', [PostController::class, 'editPostSubmit'])->name('editPost')->middleware('VerifyUser');
//----------------------------Comment----------------------------//
Route::post('/post/{id}', [PostController::class, 'addComment'])->name('addComment')->middleware('VerifyUser');
Route::get('/comment/edit/{id}', [PostController::class, 'updateComment'])->name('updateComment')->middleware('VerifyUser');
Route::post('/comment/edit/{id}', [PostController::class, 'updateCommentSubmit'])->name('updateCommentSubmit')->middleware('VerifyUser');
Route::get('/comment/delete/{id}', [PostController::class, 'deleteComment'])->name('deleteComment')->middleware('VerifyUser');
//----------------------------Profile----------------------------//
Route::get('/profile', [UserController::class, 'profile'])->name('profile')->middleware('VerifyUser');
Route::get('/profile/edit', [UserController::class, 'profileEdit'])->name('profileEdit')->middleware('VerifyUser');
Route::post('/profile', [UserController::class, 'updateProfile'])->name('updateProfile')->middleware('VerifyUser');
//----------------------------Reaction----------------------------//
Route::post('/reaction', [PostController::class, 'addReaction'])->name('addReaction')->middleware('VerifyUser');
Route::post('/post/{id}/reaction', [PostController::class, 'addReactionFromPost'])->name('addReactionFromPost')->middleware('VerifyUser');