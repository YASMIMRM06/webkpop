@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Card Grupos -->
    <div class="card kpop-effect">
        <div class="card-header">
            <h3 class="text-xl font-bold">Grupos Favoritos</h3>
        </div>
        <div class="p-4">
            @if($groups->count() > 0)
                <ul class="space-y-2">
                    @foreach($groups as $group)
                        <li class="flex items-center space-x-3">
                            <img src="{{ $group->photo ? asset('storage/'.$group->photo) : asset('images/default-group.png') }}" alt="{{ $group->name }}" class="w-12 h-12 rounded-full object-cover">
                            <a href="{{ route('groups.show', $group) }}" class="text-kpop-purple hover:underline">{{ $group->name }}</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">Você ainda não segue nenhum grupo.</p>
                <a href="{{ route('groups.index') }}" class="btn-primary inline-block mt-4">Explorar Grupos</a>
            @endif
        </div>
    </div>

    <!-- Card Eventos -->
    <div class="card kpop-effect">
        <div class="card-header">
            <h3 class="text-xl font-bold">Próximos Eventos</h3>
        </div>
        <div class="p-4">
            @if($events->count() > 0)
                <ul class="space-y-3">
                    @foreach($events as $event)
                        <li>
                            <a href="{{ route('events.show', $event) }}" class="font-semibold text-kpop-blue hover:underline">{{ $event->name }}</a>
                            <p class="text-sm text-gray-600">{{ $event->event_date->format('d/m/Y H:i') }} - {{ $event->location }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">Nenhum evento próximo.</p>
                <a href="{{ route('events.index') }}" class="btn-primary inline-block mt-4">Ver Todos Eventos</a>
            @endif
        </div>
    </div>

    <!-- Card Atividades -->
    <div class="card kpop-effect">
        <div class="card-header">
            <h3 class="text-xl font-bold">Minhas Atividades</h3>
        </div>
        <div class="p-4">
            @if($ratings->count() > 0)
                <ul class="space-y-3">
                    @foreach($ratings as $rating)
                        <li>
                            <p class="text-sm">Você avaliou <span class="font-semibold">{{ $rating->music->title }}</span> com:</p>
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">Nenhuma atividade recente.</p>
            @endif
        </div>
    </div>
</div>
@endsection