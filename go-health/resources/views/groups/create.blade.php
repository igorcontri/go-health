@extends('layouts.app')

@section('content')
    <h1>Novo Grupo</h1>

    <form action="{{ route('groups.store') }}" method="POST" onsubmit="disableSubmit(this)">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome do Grupo</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="owner_id" class="form-label">Dono do Grupo</label>
            <select class="form-select" id="owner_id" name="owner_id" required>
                <option value="">Selecione um usuário</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="group_photo" class="form-label">URL da Foto do Grupo</label>
            <input type="text" class="form-control" id="group_photo" name="group_photo">
        </div>
        <div class="mb-3">
            <label for="group_banner" class="form-label">URL do Banner do Grupo</label>
            <input type="text" class="form-control" id="group_banner" name="group_banner">
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancelar</a>
        
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