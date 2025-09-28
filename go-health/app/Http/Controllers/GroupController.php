<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::all();
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $users = User::all();
        return view('groups.create', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            Group::create($request->all());
            return redirect()->route('groups.index')
                             ->with('sucesso', 'Grupo criado com sucesso!');
        } catch (\Exception $e) {
            Log::error("ERRO AO SALVAR GRUPO: " . $e->getMessage());
            return redirect()->route('groups.index')
                             ->with('erro', 'Erro ao criar o grupo.');
        }
    }

    public function show(Group $group)
    {
        // Podemos implementar a página de visualização de um grupo depois, se quiser.
    }

    public function edit(Group $group)
    {
        $users = User::all();
        return view('groups.edit', compact('group', 'users'));
    }

    public function update(Request $request, Group $group)
    {
        try {
            $group->update($request->all());
            return redirect()->route('groups.index')
                             ->with('sucesso', 'Grupo atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("ERRO AO ATUALIZAR GRUPO: " . $e->getMessage());
            return redirect()->route('groups.index')
                             ->with('erro', 'Erro ao atualizar o grupo.');
        }
    }

    public function destroy(Group $group)
    {
        try {
            $group->delete();
            return redirect()->route('groups.index')
                             ->with('sucesso', 'Grupo excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error("ERRO AO EXCLUIR GRUPO: " . $e->getMessage());
            return redirect()->route('groups.index')
                             ->with('erro', 'Erro ao excluir o grupo.');
        }
    }
}