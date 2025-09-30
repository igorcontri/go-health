@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $user = session('user');
    @endphp

    @if($user)
        {{-- Usuário logado: mostrar check-in --}}
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Bem-vindo, {{ $user->name }}</h1>
                <p class="col-md-8 fs-4">Registre seu check-in do dia ou veja sua sequência atual.</p>
            </div>
        </div>

        {{-- Botão para logout --}}
        <div class="mb-3">
            <form action="{{ route('users.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary">Sair da Sessão</button>
            </form>
        </div>

        {{-- Check-in --}}
        <h2>Fazer Check-in</h2>
        <ul class="list-group mb-4">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $user->name }}
                <form action="{{ route('checkins.store') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <button type="submit" class="btn btn-success btn-sm">Check-in</button>
                </form>
            </li>
        </ul>

        {{-- Exibir streak do usuário --}}
        @php
            $streak = \App\Models\Streak::where('user_id', $user->id)
                        ->whereNull('group_id')
                        ->first();
        @endphp

        @if($streak)
            <div class="alert alert-info">
                <strong>Sua sequência atual:</strong> {{ $streak->current_streak }} dias<br>
                <strong>Maior sequência:</strong> {{ $streak->longest_streak }} dias
            </div>
        @else
            <div class="alert alert-info">
                Você ainda não iniciou sua sequência de check-ins.
            </div>
        @endif

    @else
        {{-- Nenhum usuário na sessão: mostrar lista para selecionar --}}
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Bem-vindo ao Go-Health</h1>
                <p class="col-md-8 fs-4">Selecione seu usuário para iniciar a sessão ou crie um novo usuário.</p>
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('users.create') }}" class="btn btn-primary">Criar Novo Usuário</a>
        </div>

        <h2>Selecionar Usuário</h2>
        <ul class="list-group">
            @foreach($users as $userOption)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $userOption->name }}
                    <form action="{{ route('users.loginAs', $userOption->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Entrar</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
