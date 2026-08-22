<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace client') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
    @php
       $user = Auth::user();
       $isAnalyst = $user && $user->hasRole('analyst');
    @endphp
</head>
<body class="client-page">

    <nav class="client-nav">
        <div class="client-container">
            <div class="client-nav-row">

                <div class="flex items-center gap-8">
                    <a href="{{ route('client.dashboard') }}" class="client-brand">Piqueou Conseil</a>

                    <div class="client-nav-links">
                        <a href="{{ route('client.dashboard') }}"
                           class="client-nav-link {{ request()->routeIs('client.dashboard') ? 'client-nav-link-active' : '' }}">
                            Accueil
                        </a>

                        @if($isAnalyst) disabled or remove r
                        <a href="{{ route('client.questionnaire.index') }}"
                           class="client-nav-link {{ request()->routeIs('client.questionnaire.*') ? 'client-nav-link-active' : '' }}">
                            Mes questionnaires
                        </a>
                        @endif
                    </div>
                </div>

                <div class="client-user">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="client-logout">Déconnexion</button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <header class="client-header">
        <div class="client-container">
            <h1 class="client-title">@yield('title', 'Espace client')</h1>
            <p class="client-subtitle">@yield('subtitle')</p>
        </div>
    </header>

    <main class="client-container">
        <div class="client-content">

            @if (session('success'))
                <div class="client-alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="client-alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')

        </div>
    </main>

</body>
</html>
