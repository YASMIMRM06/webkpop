@extends('layouts.app')

@section('title', $event->name)

@section('content')
<div class="mb-8">
    <div class="relative h-64 overflow-hidden rounded-lg">
        <div class="absolute inset-0 bg-gradient-to-r from-kpop-purple to-kpop-pink opacity-75"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <h1 class="text-4xl font-bold text-white text-center">{{ $event->name }}</h1>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-2">
        <div class="card mb-6">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-kpop-purple mb-4">Detalhes do Evento</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-1">Data e Hora</h3>
                        <p class="text-gray-600">{{ $event->event_date->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-1">Localização</h3>
                        <p class="text-gray-600">{{ $event->location }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-1">Capacidade</h3>
                        <p class="text-gray-600">{{ $event->confirmed_participants_count }} / {{ $event->capacity }} participantes</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-1">Status</h3>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold text-white bg-{{ 
                            $event->status == 'scheduled' ? 'green' : 
                            ($event->status == 'canceled' ? 'red' : 'blue')
                        }}-500">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                </div>
                
                <h3 class="font-semibold text-gray-700 mb-1">Descrição</h3>
                <p class="text-gray-600 mb-6">{{ $event->description }}</p>
                
                <div class="flex space-x-3">
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->id === $event->user_id)
                            <a href="{{ route('events.edit', $event) }}" class="btn-secondary">Editar Evento</a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este evento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Excluir</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-kpop-pink mb-4">Organizador</h2>
                <div class="flex items-center space-x-4">
                    <img src="{{ $event->creator->profile_picture ? asset('storage/'.$event->creator->profile_picture) : asset('images/default-user.png') }}" alt="{{ $event->creator->name }}" class="w-16 h-16 rounded-full object-cover">
                    <div>
                        <h3 class="font-bold text-lg">{{ $event->creator->name }}</h3>
                        <p class="text-gray-600">{{ $event->creator->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <div class="card sticky top-4">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-kpop-black mb-4">Participantes</h2>
                
                @auth
                    @if($isParticipating)
                        <form action="{{ route('events.cancel', $event) }}" method="POST" class="mb-6">
                            @csrf
                            <button type="submit" class="w-full btn-secondary">Cancelar Participação</button>
                        </form>
                    @elseif(!$event->isFull() && $event->status === 'scheduled')
                        <form action="{{ route('events.participate', $event) }}" method="POST" class="mb-6">
                            @csrf
                            <button type="submit" class="w-full btn-primary">Participar do Evento</button>
                        </form>
                    @endif
                @else
                    <div class="bg-gray-100 p-4 rounded-lg mb-6 text-center">
                        <p class="mb-2">Faça login para participar deste evento</p>
                        <a href="{{ route('login') }}" class="btn-primary inline-block">Login</a>
                    </div>
                @endauth
                
                @if($event->participants->count() > 0)
                    <div class="space-y-3">
                        @foreach($event->participants as $participant)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $participant->profile_picture ? asset('storage/'.$participant->profile_picture) : asset('images/default-user.png') }}" alt="{{ $participant->name }}" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <h4 class="font-medium">{{ $participant->name }}</h4>
                                    <span class="text-xs text-gray-500">{{ $participant->pivot->status }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-center">Nenhum participante ainda.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection