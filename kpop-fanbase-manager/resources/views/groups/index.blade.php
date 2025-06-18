@extends('layouts.app')

@section('title', 'Grupos KPOP')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-kpop-purple">Grupos KPOP</h1>
    @auth
        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
            <a href="{{ route('groups.create') }}" class="btn-primary">Adicionar Grupo</a>
        @endif
    @endauth
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($groups as $group)
        <div class="card kpop-effect">
            <div class="relative h-48 overflow-hidden">
                <img src="{{ $group->photo ? asset('storage/'.$group->photo) : asset('images/default-group.png') }}" alt="{{ $group->name }}" class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                    <h3 class="text-xl font-bold text-white">{{ $group->name }}</h3>
                </div>
            </div>
            <div class="p-4">
                <p class="text-gray-600 mb-2"><span class="font-semibold">Empresa:</span> {{ $group->company }}</p>
                <p class="text-gray-600 mb-4"><span class="font-semibold">Formado em:</span> {{ $group->formation_date->format('d/m/Y') }}</p>
                
                <div class="flex justify-between items-center">
                    <a href="{{ route('groups.show', $group) }}" class="btn-secondary">Ver Detalhes</a>
                    <span class="text-sm text-gray-500">{{ $group->musics_count }} músicas</span>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $groups->links() }}
</div>
@endsection