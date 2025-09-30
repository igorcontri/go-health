@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Gerenciar Membros do Grupo: {{ $group->name }}</h1>
        <a href="{{ route('groups.index') }}" class="btn btn-secondary">Voltar para a Lista</a>
    </div>

    <div class="row">
        {{-- COLUNA PARA ADICIONAR NOVOS MEMBROS --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Adicionar Novo Membro
                </div>
                <div class="card-body">
                    <form action="{{ route('groups.members.add', $group->id) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <select name="user_id" class="form-select" required>
                                <option value="">Selecione um usuário para adicionar</option>
                                @forelse ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @empty
                                    <option value="" disabled>Nenhum usuário disponível para adicionar</option>
                                @endforelse
                            </select>
                            <button class="btn btn-primary" type="submit">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- COLUNA PARA LISTAR MEMBROS ATUAIS --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Membros Atuais ({{ $members->count() }})
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($members as $member)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                {{ $member->name }}
                                @if($member->id == $group->owner_id)
                                    <span class="badge bg-success ms-2">Dono</span>
                                @endif
                            </span>

                            @if($member->id != $group->owner_id)
                                <form action="{{ route('groups.members.remove', ['group' => $group->id, 'user' => $member->id]) }}" method="POST" class="form-remove-member">
                                    @csrf
                                    @method('DELETE')
                                    {{-- O botão agora é 'type="button"' e chama nossa função Javascript --}}
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmRemove(this)">Remover</button>
                                </form>
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Ainda não há membros neste grupo.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection

{{-- Adicionamos o script de confirmação no final da página --}}
@push('scripts')
<script>
    function confirmRemove(button) {
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Esta ação não poderá ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, remover!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Se o usuário confirmar, envia o formulário
                button.closest('.form-remove-member').submit();
            }
        })
    }
</script>
@endpush