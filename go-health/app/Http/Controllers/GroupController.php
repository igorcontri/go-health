<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        // 1. Validação dos dados (incluindo as imagens)
        $request->validate([
            'name' => 'required|string|max:45',
            'description' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
            'group_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // foto, max 2MB
            'group_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // banner, max 5MB
        ]);

        // 2. Pega todos os dados, exceto os arquivos, por enquanto
        $data = $request->except(['group_photo', 'group_banner']);

        // 3. Processa a foto do grupo, se ela foi enviada
        if ($request->hasFile('group_photo')) {
            // Salva o arquivo na pasta 'storage/app/public/group_photos' e pega o caminho
            $path = $request->file('group_photo')->store('group_photos', 'public');
            $data['group_photo'] = $path; // Adiciona o caminho ao array de dados
        }

        // 4. Processa o banner do grupo, se ele foi enviado
        if ($request->hasFile('group_banner')) {
            $path = $request->file('group_banner')->store('group_banners', 'public');
            $data['group_banner'] = $path;
        }

        try {
            // 5. Cria o grupo com todos os dados (texto + caminhos das imagens)
            $group = Group::create($data);

            // 6. Adiciona o dono como membro
            $group->members()->attach($group->owner_id);

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
        // Podemos implementar a página de visualização de um grupo 
    }

    public function edit(Group $group)
    {
        $users = User::all();
        return view('groups.edit', compact('group', 'users'));
    }

    public function update(Request $request, Group $group)
    {
        // 1. Validação
        $request->validate([
            'name' => 'required|string|max:45',
            'description' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
            'group_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'group_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // 2. Pega os dados de texto
        $data = $request->except(['group_photo', 'group_banner']);

        // 3. Processa a nova FOTO, se enviada
        if ($request->hasFile('group_photo')) {
            // Apaga a foto antiga para não ocupar espaço
            if ($group->group_photo) {
                Storage::disk('public')->delete($group->group_photo);
            }
            // Salva a nova foto e atualiza o caminho
            $path = $request->file('group_photo')->store('group_photos', 'public');
            $data['group_photo'] = $path;
        }

        // 4. Processa o novo BANNER, se enviado
        if ($request->hasFile('group_banner')) {
            if ($group->group_banner) {
                Storage::disk('public')->delete($group->group_banner);
            }
            $path = $request->file('group_banner')->store('group_banners', 'public');
            $data['group_banner'] = $path;
        }

        try {
            // 5. Atualiza o grupo com os novos dados
            $group->update($data);
            return redirect()->route('groups.index')
                             ->with('sucesso', 'Grupo atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("ERRO AO ATUALIZAR GRUPO: " . $e->getMessage());
            return redirect()->route('groups.index')
                             ->with('erro', 'Erro ao atualizar o grupo.');
        }
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);

        // Remove vínculos com usuários
        $group->members()->detach();

        // Opcional: se quiser excluir streaks ligados a esse grupo
        $group->streaks()->delete();

        // Agora pode excluir o grupo
        $group->delete();

        return redirect()->route('groups.index')->with('sucesso', 'Grupo excluído com sucesso!');
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

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $group->members()->syncWithoutDetaching([$request->user_id]);

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