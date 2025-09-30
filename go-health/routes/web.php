<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckinController;
use App\Models\User;


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
    $users = User::all(); // busca todos os usuários
    return view('index', compact('users'));
});

Route::resource('groups', GroupController::class);

Route::resource('users', UserController::class); //rota pra crud de users

Route::resource('checkins', CheckinController::class)->only(['index', 'store']);

// Route::get('/checkins', [CheckinController::class, 'index'])->name('checkins.index');
// Route::post('/checkins', [CheckinController::class, 'store'])->name('checkins.store');

