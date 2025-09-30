<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view("users.index", compact("users"));
    }

    public function create()
    {
        return view("users.create");
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['password'] = Hash::make($request->password);

            User::create($data);

            return redirect()->route("users.index")
                             ->with("sucesso", "Usuário criado com sucesso!");
        } catch(\Exception $e) {
            Log::error("ERRO AO SALVAR USUÁRIO: " . $e->getMessage());
            return redirect()->route("users.index")
                             ->with("erro", "Erro ao criar usuário!");
        }
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view("users.show", compact("user"));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view("users.edit", compact("user"));
    }

    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $data = $request->all();

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return redirect()->route("users.index")
                             ->with("sucesso", "Usuário alterado com sucesso!");
        } catch(\Exception $e) {
            Log::error("ERRO AO ALTERAR USUÁRIO: " . $e->getMessage());
            return redirect()->route("users.index")
                             ->with("erro", "Erro ao alterar usuário!");
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route("users.index")
                             ->with("sucesso", "Usuário excluído com sucesso!");
        } catch(\Exception $e) {
            Log::error("ERRO AO EXCLUIR USUÁRIO: " . $e->getMessage());
            return redirect()->route("users.index")
                             ->with("erro", "Erro ao excluir usuário!");
        }
    }
}