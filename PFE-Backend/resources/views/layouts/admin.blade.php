<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administration') - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-page">

    <nav class="admin-nav">
        <div class="admin-container">
            <div class="admin-nav-row">

                <div class="flex items-center gap-8">
                    <a href="{{ route('admin.dashboard') }}" class="admin-brand">Piqueou Admin</a>

                    <div class="admin-nav-links">
                        <a href="{{ route('admin.dashboard') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'admin-nav-link-active' : '' }}">
                            Tableau de bord
                        </a>
                        <a href="{{ route('admin.analyst.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.analyst.*') ? 'admin-nav-link-active' : '' }}">
                            Analystes
                        </a>
                        <a href="{{ route('admin.client.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.client.*') ? 'admin-nav-link-active' : '' }}">
                            Clients
                        </a>
                        <a href="{{ route('admin.submission.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.submission.*') ? 'admin-nav-link-active' : '' }}">
                            Questionnaires envoyés
                        </a>
                    </div>
                </div>

                <div class="admin-user">
                    <span>{{ Auth::user()->name }}</span>
                    <!-- Ajout de "inline-flex items-center m-0" pour empêcher le formulaire de créer un saut de ligne -->
                    <form method="POST" action="{{ route('logout') }}" class="inline-flex items-center m-0">
                        @csrf
                        <button type="submit" class="admin-logout">Déconnexion</button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <header class="admin-header">
        <div class="admin-container">
            <div class="admin-header-row">
                <div>
                    <h1 class="admin-title">@yield('title', 'Administration')</h1>
                    <p class="admin-subtitle">@yield('subtitle')</p>
                </div>
                <div>
                    @yield('actions')
                </div>
            </div>
        </div>
    </header>

    <main class="admin-container">
        <div class="admin-content">

            @if (session('success'))
                <div class="admin-alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="admin-alert-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="admin-alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </div>
    </main>

</body>
</html>
