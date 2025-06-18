@extends('layouts.app') 
@section('title', 'Eventos KPOP') 
@section('content') 
<div class="mb-8 flex justify-between items-center"> 
    <h1 class="text-3xl font-bold text-kpop-purple">Eventos KPOP</h1> 
    @auth 
        @if(auth()->user()->isAdmin() || auth()->user()->isManager()) 
            <a href="{{ route('events.create') }}" class="btn-primary">Criar Evento</a> 
        @endif 
    @endauth 
</div> 
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> 
    @foreach($events as $event) 
        <div class="card kpop-effect"> 
            <div class="relative h-48 overflow-hidden"> 
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black opacity-50"></div> 
                <div class="absolute bottom-0 left-0 p-4 text-white"> 
                    <span class="inline-block px-2 py-1 bg-{{  
                        $event->status == 'scheduled' ? 'green' :  
                        ($event->status == 'canceled' ? 'red' : 'blue') 
                    }}-500 text-white text-xs font-bold rounded-full mb-2"> 
                        {{ ucfirst($event->status) }} 
                    </span> 
                    <h3 class="text-xl font-bold">{{ $event->name }}</h3> 
                </div> 
            </div> 
            <div class="p-4"> 
                <div class="flex items-center text-gray-600 mb-2"> 
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" 
xmlns="http://www.w3.org/2000/svg"> 
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 
1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path> 
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 
0z"></path> 
                    </svg> 
                    <span>{{ $event->location }}</span> 
                </div> 
                <div class="flex items-center text-gray-600 mb-4"> 
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" 
xmlns="http://www.w3.org/2000/svg"> 
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 
0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path> 
                    </svg> 
                    <span>{{ $event->event_date->format('d/m/Y H:i') }}</span> 
                </div>             
                <div class="flex justify-between items-center"> 
                    <a href="{{ route('events.show', $event) }}" class="btn-secondary">Detalhes</a> 
                    <span class="text-sm text-gray-500">{{ $event->confirmed_participants_count }}/{{ $event->capacity }} 
participantes</span> 
                </div> 
            </div> 
        </div> 
    @endforeach 
</div> 
<div class="mt-6"> 
    {{ $events->links() }} 
</div> 
@endsection