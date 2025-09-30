@extends('layouts.app')

@section('content')
    <h1>Editar Grupo</h1>

    <form action="{{ route('groups.update', $group->id) }}" method="POST">
        @csrf
        @method('PUT')
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
        <div class="mb-3">
            <label for="group_photo" class="form-label">URL da Foto do Grupo</label>
            <input type="text" class="form-control" id="group_photo" name="group_photo" value="{{ $group->group_photo }}">
        </div>
        <div class="mb-3">
            <label for="group_banner" class="form-label">URL do Banner do Grupo</label>
            <input type="text" class="form-control" id="group_banner" name="group_banner" value="{{ $group->group_banner }}">
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancelar</a>
        
    </form>
@endsection