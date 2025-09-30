@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Check-in</h1>

    @if(session('sucesso'))
        <div class="alert alert-success">{{ session('sucesso') }}</div>
    @endif
    @if(session('erro'))
        <div class="alert alert-danger">{{ session('erro') }}</div>
    @endif

    <form action="{{ route('checkins.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">Usuário</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Selecione um usuário</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Fazer Check-in</button>
    </form>
</div>
@endsection
