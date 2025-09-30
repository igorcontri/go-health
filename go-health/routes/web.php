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
|
| Aqui é onde você registra as rotas web da aplicação.
|
*/

// Rota home: lista usuários se ninguém estiver logado, ou tela de check-in se houver usuário na sessão
Route::get('/', function () {
    $users = User::all();
    return view('index', compact('users'));
})->name('home');

// CRUD de grupos
Route::resource('groups', GroupController::class);

// CRUD de usuários
Route::resource('users', UserController::class);

// LOGIN SIMPLES: cria a sessão para o usuário selecionado
Route::post('/login-as/{user}', function (User $user) {
    Session::put('user', $user); // guarda o objeto do usuário na sessão
    return redirect()->route('home')->with('sucesso', "Logado como {$user->name}");
})->name('users.loginAs');

// LOGOUT SIMPLES: remove a sessão
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
