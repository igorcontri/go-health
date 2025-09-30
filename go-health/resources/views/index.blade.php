@extends('layouts.app')

@section('content')
<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Bem-vindo ao Go-Health</h1>
        <p class="col-md-8 fs-4">Sistema de gerenciamento. Use o menu acima para navegar entre as seções de Usuários e Grupos.</p>
    </div>
</div>

<div class="container">
    <h2>Fazer Check-in</h2>
    <p>Selecione um usuário para registrar o check-in de hoje:</p>

    <ul class="list-group">
        @foreach($users as $user)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $user->name }}
                <form action="{{ route('checkins.store') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <button type="submit" class="btn btn-success btn-sm">
                        Check-in
                    </button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
@endsection
