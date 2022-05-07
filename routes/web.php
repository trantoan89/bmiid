<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CasesPDFController;
use App\Http\Controllers\ContactController;

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
    return Inertia::render('Map', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/login', [LoginController::class, 'authenticate']);

Route::inertia('/about', 'About');
Route::inertia('/contact', 'Contact');
Route::inertia('/newstips', 'Welcome');
Route::inertia('/forgot-password', 'Auth/ForgotPassword');

Route::post('/contact/send', [ContactController::class, 'send']);

Route::middleware(['auth:sanctum', 'verified'])->group(function (){
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::inertia('/news', 'Components/News')->middleware('admin');
    Route::inertia('/barangay', 'Components/barangay');
    Route::inertia('/disease', 'Components/Disease')->middleware('admin');
    Route::inertia('/case', 'Components/Case');
    Route::inertia('/accounts', 'Components/Users')->middleware('admin');
    
    Route::get('/casesview', [CasesPDFController::class, 'getPDF']);
});

//Route::get('/hash/{p}', function ($p) {
//    return \Illuminate\Support\Facades\Hash::make($p);
//});

