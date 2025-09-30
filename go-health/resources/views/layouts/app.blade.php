<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Go-Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Go-Health</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('groups.index') }}">Groups</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
            </ul>

            <ul class="navbar-nav ms-auto">
                @php $sessionUser = Session::get('user'); @endphp
                @if($sessionUser)
                    <li class="nav-item"><span class="nav-link">Olá, {{ $sessionUser->name }}</span></li>
                    <li class="nav-item">
                        <form action="{{ route('users.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Sair</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Entrar</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('sucesso'))
    <script>
        Swal.fire({
            title: 'Sucesso!',
            text: '{{ session('sucesso') }}',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    </script>
@endif

@if(session('erro'))
    <script>
        Swal.fire({
            title: 'Erro!',
            text: '{{ session('erro') }}',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    </script>
@endif

@stack('scripts')
</body>
</html>
