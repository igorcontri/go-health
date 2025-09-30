<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckinController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota home: lista usuários se ninguém estiver logado, ou check-in se usuário logado
Route::get('/', function () {
    $user = Session::get('user');
    $users = User::all();
    return view('index', compact('user', 'users'));
})->name('home');

// CRUD de grupos
Route::resource('groups', GroupController::class);

// CRUD de usuários
Route::resource('users', UserController::class);

// LOGIN SIMPLES: cria a sessão para o usuário selecionado
Route::post('/login-as/{user}', function (User $user) {
    Session::put('user', $user); // guarda objeto na sessão
    return redirect()->route('home')->with('sucesso', "Logado como {$user->name}");
})->name('users.loginAs');

// LOGOUT SIMPLES: remove usuário da sessão
Route::post('/logout', function () {
    Session::forget('user');
    return redirect()->route('home')->with('sucesso', "Sessão encerrada.");
})->name('users.logout');

// Check-ins: apenas index e store
Route::resource('checkins', CheckinController::class)->only(['index', 'store']);

// Gerenciamento de membros de grupo
Route::get('/groups/{group}/members', [GroupController::class, 'manageMembers'])->name('groups.members');
Route::post('/groups/{group}/members', [GroupController::class, 'addMember'])->name('groups.members.add');
Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.members.remove');
