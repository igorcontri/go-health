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
            // 1. Cria o grupo e o armazena em uma variável
            $group = Group::create($request->all());

            // 2. Usa a relação para adicionar o dono como membro
            $group->members()->attach($group->owner_id);

            return redirect()->route('groups.index')
                            ->with('sucesso', 'Grupo criado e dono adicionado como membro!');
        } catch (\Exception $e) {
            Log::error("ERRO AO SALVAR GRUPO: " . $e->getMessage());
            return redirect()->route('groups.index')
                            ->with('erro', 'Erro ao criar o grupo.');
        }
    }

    public function show(Group $group)
    {
        // Podemos implementar a página de visualização de um grupo 
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



    public function manageMembers(Group $group)
    {
    // Pega todos os usuários que NÃO são membros do grupo atual
        $users = User::whereNotIn('id', $group->members->pluck('id'))->get();
    // Retorna a view, passando o grupo específico, seus membros e os usuários que podem ser adicionados
        return view('groups.members', [
            'group' => $group,
            'members' => $group->members,
            'users' => $users
        ]);
    }
    
    public function addMember(Request $request, Group $group)
    {
        // Verifica se o ID do usuário logado é o mesmo que o ID do dono do grupo// IMPLEMENTAR APOS FAZER A TELA DE LOGIN
        //if (auth()->user()->id !== $group->owner_id) {
            // Se não for o dono,  Acesso Negado.
           // abort(403, 'Apenas o dono do grupo pode adicionar membros.');
        //}

        // Valida se o user_id foi enviado e se ele existe na tabela users
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Usa o método attach() para criar o registro na tabela pivô 'group_user'.
        $group->members()->attach($request->user_id);

        // Redireciona de volta para a página de gerenciamento com uma mensagem de sucesso.
        return redirect()->route('groups.members', $group->id)
                        ->with('sucesso', 'Membro adicionado com sucesso!');
    }

        public function removeMember(Request $request, Group $group, User $user)
    {
        // ETAPA DE SEGURANÇA (Temporariamente desativada)
        // if (auth()->user()->id !== $group->owner_id) {
        //     abort(403, 'Apenas o dono do grupo pode remover membros.');
        // }

        // LÓGICA PRINCIPAL:
        // Usa o método detach() para remover o registro da tabela pivô 'group_user'.
        // detach() é o oposto de attach().
        if ($group->owner_id == $user->id) {
            return redirect()->route('groups.members', $group->id)
                            ->with('erro', 'O dono do grupo não pode ser removido.');
        }

        $group->members()->detach($user->id);

        // Redireciona de volta para a página de gerenciamento com uma mensagem de sucesso.
        return redirect()->route('groups.members', $group->id)
                        ->with('sucesso', 'Membro removido com sucesso!');
    }
}