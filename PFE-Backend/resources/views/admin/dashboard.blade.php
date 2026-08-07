@extends('layouts.app')

@section('header')
    <div class="flex flex-col gap-1">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">Administration</h2>
        <p class="text-sm text-gray-600">Accès rapide aux listes clients et analystes.</p>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Administration</h1>
                        <p class="mt-2 text-sm text-gray-600">Sélectionnez la liste à gérer.</p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('admin.analyst.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-left transition hover:border-indigo-500 hover:bg-white">
                            <p class="text-sm text-slate-500">Analystes</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900">Voir la liste des analystes</p>
                        </a>
                        <a href="{{ route('admin.client.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-left transition hover:border-indigo-500 hover:bg-white">
                            <p class="text-sm text-slate-500">Clients</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900">Voir la liste des clients</p>
                        </a>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                <div class="rounded-3xl bg-white p-5 ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Clients</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $stats['clients'] }}</p>
                </div>
                <div class="rounded-3xl bg-white p-5 ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Analystes</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $stats['analysts'] }}</p>
                </div>
                <div class="rounded-3xl bg-white p-5 ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">En attente</p>
                    <p class="mt-3 text-3xl font-semibold text-amber-700">{{ $stats['pending'] }}</p>
                </div>
                <div class="rounded-3xl bg-white p-5 ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Actifs</p>
                    <p class="mt-3 text-3xl font-semibold text-emerald-700">{{ $stats['active'] }}</p>
                </div>
                <div class="rounded-3xl bg-white p-5 ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Inactifs</p>
                    <p class="mt-3 text-3xl font-semibold text-rose-700">{{ $stats['inactive'] }}</p>
                </div>
            </section>
        </div>
    </div>
@endsection
