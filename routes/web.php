<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');


//kelompok yang boeh diakses hanya setelah login
Route::middleware(['isLoggedIn'])->group(function(){
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    // nama route diawali dangan admin. contoh: admin.dashboard
 Route::middleware(['isAdmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

Route::resource('book-categories', BookCategoryController::class);
    });
});
//menggunakan get karena adanya proses dulu yaitu menghapus sesi
Route::middleware(['isGuest'])->group(function(){
    Route::get('/register', function () {
    return view('register');
    })->name('register');

    Route::post('/register', [UserController::class, 'register'])->name('register.store')->middleware('throttle:5,1');
    //namacontrollwer, function,

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [UserController::class, 'login'])->name('login.store')->middleware('throttle:5,1');
});
