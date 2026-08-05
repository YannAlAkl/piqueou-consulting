<?php
$user = Auth::user();
$isAdmin = $user && $user->hasRole('admin');
$currentRoute = request()->route() ? request()->route()->getName() : '';
?>

<nav class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" class="block h-9 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="{{ $currentRoute === 'dashboard' ? 'active' : '' }}">Tableau de bord</a>
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="{{ str_starts_with($currentRoute, 'admin.dashboard') ? 'active' : '' }}">Administration</a>
                        <a href="{{ route('admin.analyst.index') }}" class="{{ str_starts_with($currentRoute, 'admin.analyst') ? 'active' : '' }}">Analystes</a>
                        <a href="{{ route('admin.client.index') }}" class="{{ str_starts_with($currentRoute, 'admin.client') ? 'active' : '' }}">Clients</a>
                    @endif
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative">
                    <button type="button" onclick="document.getElementById('user-menu').classList.toggle('hidden')"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ $user->name ?? '' }}</div>
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <div id="user-menu" class="hidden absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
