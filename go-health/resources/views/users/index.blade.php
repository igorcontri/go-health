@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Lista de Usuários</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Novo Usuário</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th width="250px">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="d-flex gap-1">
                        {{-- Botão para "logar" como o usuário --}}
                        @php
                            $sessionUser = session('user');
                        @endphp
                        @if(!$sessionUser || $sessionUser->id !== $user->id)
                            <form action="{{ route('users.loginAs', $user->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    Entrar
                                </button>
                            </form>
                        @else
                            <span class="badge bg-info text-dark">Logado</span>
                        @endif

                        {{-- Editar --}}
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Editar</a>

                        {{-- Excluir --}}
                        @if(!$sessionUser || $sessionUser->id !== $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Tem certeza que deseja excluir?')">
                                    Excluir
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
