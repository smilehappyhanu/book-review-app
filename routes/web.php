<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

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

// Front routes
Route::get('phpinfo', fn () => phpinfo());
Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/book/detail/{id}',[HomeController::class,'bookDetail'])->name('book.detail');
Route::post('/book/review',[HomeController::class,'bookReview'])->name('book.review');


// Admin routes
Route::group(['prefix' => 'account'],function(){
    Route::group(['middleware'=> 'guest'],function(){
        Route::get('register',[AccountController::class,'register'])->name('account.register');
        Route::post('register',[AccountController::class,'handleRegister'])->name('account.handleRegister');
        Route::get('login',[AccountController::class,'login'])->name('account.login');
        Route::post('login',[AccountController::class,'authenticate'])->name('account.authenticate');
    });
    Route::group(['middleware' => 'auth'],function(){
        // Account routes
        Route::get('profile',[AccountController::class,'profile'])->name('account.profile');
        Route::post('profile',[AccountController::class,'handleUpdateProfile'])->name('account.handleUpdateProfile');
        Route::get('my-review',[AccountController::class,'myReview'])->name('account.myReview');
        Route::get('my-review/{id}',[AccountController::class,'editMyReview'])->name('myReview.edit');
        Route::post('my-review/{id}',[AccountController::class,'updateMyReview'])->name('myReview.update');
        Route::delete('my-review/delete',[AccountController::class,'deleteMyReview'])->name('myReview.delete');
        Route::get('logout',[AccountController::class,'logout'])->name('account.logout');

        // Book routes
        Route::get('books',[BookController::class,'index'])->name('books.index');
        Route::get('books/create',[BookController::class,'create'])->name('books.create');
        Route::post('books/store',[BookController::class,'store'])->name('books.store');
        Route::get('books/edit/{id}',[BookController::class,'edit'])->name('books.edit');
        Route::post('books/edit/{id}',[BookController::class,'update'])->name('books.update');
        Route::delete('books/delete/',[BookController::class,'destroy'])->name('books.delete');

        // Review routes
        Route::get('reviews',[ReviewController::class,'index'])->name('reviews.index');
        Route::get('reviews/edit/{id}',[ReviewController::class,'edit'])->name('reviews.edit');
        Route::post('reviews/edit/{id}',[ReviewController::class,'update'])->name('reviews.update');
        Route::delete('reviews/delete',[ReviewController::class,'destroy'])->name('reviews.delete');

    });
});
