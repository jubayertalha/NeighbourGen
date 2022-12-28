<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\HomeApiController;
use App\Http\Controllers\PostApiController;
use App\Http\Controllers\UserApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login',[AuthApiController::class,'login']);
Route::post('/registration',[AuthApiController::class,'registration']);
Route::post('/verify',[AuthApiController::class,'verify']);
Route::post('/logout',[AuthApiController::class,'logout']); 
Route::get('/neighbourhoods',[AuthApiController::class,'neighbourhoods']);

Route::get('/profile', [UserApiController::class, 'profile'])->name('profile')->middleware('VerifyUser');
Route::get('/profile/edit', [UserApiController::class, 'profileEdit'])->name('profileEdit')->middleware('VerifyUser');
Route::post('/profile', [UserApiController::class, 'updateProfile'])->name('updateProfile')->middleware('VerifyUser');

Route::get('/home',[HomeApiController::class,'home'])->name('home')->middleware('VerifyUser');

Route::post('/home', [PostApiController::class, 'addPost'])->name('addPost')->middleware('VerifyUser');
Route::get('/post/{id}', [PostApiController::class, 'post'])->name('post')->middleware('VerifyUser');
Route::delete('/post/{id}', [PostApiController::class, 'deletePost'])->name('deletePost')->middleware('VerifyUser');
Route::patch('/post/{id}', [PostApiController::class, 'editPostSubmit'])->name('editPost')->middleware('VerifyUser');

Route::post('/post/{id}', [PostApiController::class, 'addComment'])->name('addComment')->middleware('VerifyUser');
Route::patch('/comment/edit/{id}', [PostApiController::class, 'updateCommentSubmit'])->name('updateCommentSubmit')->middleware('VerifyUser');
Route::delete('/comment/{id}', [PostApiController::class, 'deleteComment'])->name('deleteComment')->middleware('VerifyUser');

Route::post('/reaction', [PostApiController::class, 'addReaction'])->name('addReaction')->middleware('VerifyUser');
Route::post('/post/{id}/reaction', [PostApiController::class, 'addReactionFromPost'])->name('addReactionFromPost')->middleware('VerifyUser');