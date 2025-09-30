@extends('layouts.app')

@section('content')
    <h1>Editar Grupo</h1>

    <form action="{{ route('groups.update', $group->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        {{-- Campos de Nome, Descrição e Dono (continuam iguais) --}}
        <div class="mb-3">
            <label for="name" class="form-label">Nome do Grupo</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $group->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $group->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="owner_id" class="form-label">Dono do Grupo</label>
            <select class="form-select" id="owner_id" name="owner_id" required>
                <option value="">Selecione um usuário</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @if($user->id == $group->owner_id) selected @endif>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- >> INÍCIO DAS ALTERAÇÕES PARA IMAGENS << --}}

        {{-- Campo para a FOTO do Grupo --}}
        <div class="mb-3">
            <label for="group_photo" class="form-label">Nova Foto do Grupo</label>
            <input type="file" class="form-control" id="group_photo" name="group_photo">
            <small class="form-text text-muted">Deixe em branco para não alterar a foto atual.</small>
        </div>

        @if ($group->group_photo)
            <div class="mb-3">
                <p>Foto Atual:</p>
                <img src="{{ asset('storage/' . $group->group_photo) }}" alt="Foto de {{ $group->name }}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
            </div>
        @endif

        {{-- Campo para o BANNER do Grupo --}}
        <div class="mb-3">
            <label for="group_banner" class="form-label">Novo Banner do Grupo</label>
            <input type="file" class="form-control" id="group_banner" name="group_banner">
            <small class="form-text text-muted">Deixe em branco para não alterar o banner atual.</small>
        </div>

        @if ($group->group_banner)
            <div class="mb-3">
                <p>Banner Atual:</p>
                <img src="{{ asset('storage/' . $group->group_banner) }}" alt="Banner de {{ $group->name }}" style="max-width: 400px;" class="img-fluid">
            </div>
        @endif

        {{-- >> FIM DAS ALTERAÇÕES << --}}

        <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
@endsection