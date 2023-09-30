<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('blank-page', ['type_menu' => '']);
// });

Route::get('/', function () {
    // return view('welcome');
    return view('auth.login', ['type_menu' => '']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('home', function () {
        return view('pages.dashboard', ['type_menu' => '']);
    })->name('home')->middleware('can:dashboard');
    Route::get('edit-profile', function () {
        return view('pages.profile', ['type_menu' => '']);
    })->name('profile.edit');
    Route::resource('user', UserController::class);
});


// Route::get('/login', function () {
//         // return view('welcome');
//         return view('auth.login');
//     });