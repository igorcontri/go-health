@extends('layouts.app')

@section('content')
    <h1>Editar Usuário</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST" onsubmit="disableSubmit(this)">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Nova Senha</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Deixe em branco para não alterar">
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Idade</label>
            <input type="number" class="form-control" id="age" name="age" value="{{ $user->age }}">
        </div>
        <div class="mb-3">
            <label for="weight" class="form-label">Peso (kg)</label>
            <input type="text" class="form-control" id="weight" name="weight" value="{{ $user->weight }}" placeholder="Ex: 70.5">
        </div>
        <div class="mb-3">
            <label for="height" class="form-label">Altura (m)</label>
            <input type="text" class="form-control" id="height" name="height" value="{{ $user->height }}" placeholder="Ex: 1.75">
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        
    </form>
@endsection
@push('scripts')
<script>
    function disableSubmit(form) {
        let button = form.querySelector('button[type="submit"]');
        button.disabled = true;
        button.innerText = "Salvando...";
    }
</script>
@endpush