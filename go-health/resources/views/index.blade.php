@extends('layouts.app')

@section('content')
<div class="container">
    @php $user = session('user'); @endphp

    @if($user)
        <div class="p-5 mb-4 bg-light rounded-3">
            <h1 class="display-5 fw-bold">Bem-vindo, {{ $user->name }}</h1>
            <p>Registre seu check-in do dia ou veja sua sequência atual.</p>
        </div>

        <form action="{{ route('checkins.store') }}" method="POST" class="mb-3">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <button type="submit" class="btn btn-success btn-lg">Fazer Check-in</button>
        </form>

        @php
            $streak = $user->streaks()->whereNull('group_id')->first();
            $currentStreak = $streak->current_streak ?? 0;
            $longestStreak = $streak->longest_streak ?? 0;
        @endphp
        <div class="alert alert-info">
            <strong>Sua sequência atual:</strong> {{ $currentStreak }} dia(s)<br>
            <strong>Maior sequência:</strong> {{ $longestStreak }} dia(s)
        </div>

        <form action="{{ route('users.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary">Sair da Sessão</button>
        </form>

    @else
        <div class="p-5 mb-4 bg-light rounded-3">
            <h1 class="display-5 fw-bold">Bem-vindo ao Go-Health</h1>
            <p>Selecione seu usuário para iniciar a sessão ou crie um novo usuário.</p>
        </div>

        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Criar Novo Usuário</a>

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
