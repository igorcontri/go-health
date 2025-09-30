@extends('layouts.app')

@section('content')
    <h1>Novo Usuário</h1>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Idade</label>
            <input type="number" class="form-control" id="age" name="age">
        </div>
        <div class="mb-3">
            <label for="weight" class="form-label">Peso (kg)</label>
            <input type="text" class="form-control" id="weight" name="weight" placeholder="Ex: 70.5">
        </div>
        <div class="mb-3">
            <label for="height" class="form-label">Altura (m)</label>
            <input type="text" class="form-control" id="height" name="height" placeholder="Ex: 1.75">
        </div>

        
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        
    </form>
@endsection