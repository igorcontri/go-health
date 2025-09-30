@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Check-in</h1>

    {{-- Mensagens de sucesso ou erro --}}
    @if(session('sucesso'))
        <div class="alert alert-success">{{ session('sucesso') }}</div>
    @endif
    @if(session('erro'))
        <div class="alert alert-danger">{{ session('erro') }}</div>
    @endif

    {{-- Mostrar nome do usuário logado --}}
    <p><strong>Usuário:</strong> {{ $user->name }}</p>

    {{-- Mostrar streak atual --}}
    @php
        $streak = $user->streaks()->whereNull('group_id')->first();
        $currentStreak = $streak->current_streak ?? 0;
        $longestStreak = $streak->longest_streak ?? 0;
    @endphp
    <p><strong>Sequência atual:</strong> {{ $currentStreak }} dia(s)</p>
    <p><strong>Maior sequência:</strong> {{ $longestStreak }} dia(s)</p>

    {{-- Formulário de check-in --}}
    <form action="{{ route('checkins.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success btn-lg">Fazer Check-in</button>
    </form>

    {{-- Botão para encerrar sessão --}}
    <form action="{{ route('users.logout') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-secondary">Sair da Sessão</button>
    </form>
</div>
@endsection
