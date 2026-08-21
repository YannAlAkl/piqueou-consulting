@extends('layouts.app')

@section('header')
    <h2 class="text-xl font-semibold text-gray-800">Espace analyste</h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900">Bonjour {{ Auth::user()->name }}</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Vous êtes connecté en tant qu'analyste. Les questionnaires qui vous sont assignés
                    apparaîtront ici.
                </p>

                <p class="mt-6 text-sm text-gray-500">
                    Questionnaires assignés : {{ $total ?? 0 }}
                </p>
                
            </div>
        </div>
    </div>
@endsection
