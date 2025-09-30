@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Lista de Grupos</h1>
        <a href="{{ route('groups.create') }}" class="btn btn-primary">Novo Grupo</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Dono do Grupo</th>
                <th width="220px">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
                <tr>
                    <td>{{ $group->id }}</td>
                    <td>{{ $group->name }}</td>
                    <td>{{ $group->owner->name ?? 'Usuário não encontrado' }}</td>
                    <td>
                        <a href="{{ route('groups.members', $group->id) }}" class="btn btn-primary btn-sm">Membros</a>
                        <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este grupo?')">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection