<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::inertia('/about', 'About');
Route::inertia('/contact', 'Contact');
Route::inertia('/map', 'Map');
Route::inertia('/forgot-password', 'Auth/ForgotPassword');

Route::middleware(['auth:sanctum', 'verified'])->group(function (){
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::inertia('/news', 'Components/News')->middleware('admin');
    Route::inertia('/barangay', 'Components/barangay');
    Route::inertia('/disease', 'Components/Disease')->middleware('admin');
    Route::inertia('/case', 'Components/Case');
    Route::inertia('/accounts', 'Components/Users')->middleware('admin');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->name('dashboard');
