<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GroupController;

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
    return view('index');
});

Route::resource('groups', GroupController::class);

Route::resource('users', UserController::class); //rota pra crud de users
Route::get('/groups/{group}/members', [GroupController::class, 'manageMembers'])->name('groups.members'); //rota pra gerenciamento de membros
Route::post('/groups/{group}/members', [GroupController::class, 'addMember'])->name('groups.members.add'); // rota pra adicionar membro
Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.members.remove'); // rota pra remover membro
