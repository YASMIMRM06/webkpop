@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex flex-col md:flex-row gap-8 mb-8">
        <div class="md:w-1/3">
            <div class="card">
                <div class="p-4 text-center">
                    <div class="relative mx-auto w-32 h-32 mb-4">
                        <img src="{{ auth()->user()->profile_picture ? asset('storage/'.auth()->user()->profile_picture) : asset('images/default-user.png') }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover border-4 border-kpop-pink">
                        <form id="profile-picture-form" action="{{ route('users.profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" name="profile_picture" id="profile-picture-input" accept="image/*">
                        </form>
                        <button onclick="document.getElementById('profile-picture-input').click()" class="absolute bottom-0 right-0 bg-kpop-blue text-white rounded-full p-2 hover:bg-kpop-purple transition duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <h2 class="text-2xl font-bold text-kpop-purple">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-600 mb-2">{{ auth()->user()->email }}</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold text-white bg-{{ 
                        auth()->user()->type == 'admin' ? 'purple' : 
                        (auth()->user()->type == 'manager' ? 'blue' : 'pink')
                    }}-500 mb-4">
                        {{ ucfirst(auth()->user()->type) }}
                    </span>
                    @if(auth()->user()->birth_date)
                        <p class="text-gray-600">Nascimento: {{ auth()->user()->birth_date->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="md:w-2/3">
            <div class="card">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-kpop-pink mb-6">Editar Perfil</h2>
                    
                    <form action="{{ route('users.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-gray-700 font-bold mb-2">Nome</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="input-field @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-gray-700 font-bold mb-2">E-mail</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="input-field @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="birth_date" class="block text-gray-700 font-bold mb-2">Data de Nascimento</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', auth()->user()->birth_date ? auth()->user()->birth_date->format('Y-m-d') : '') }}" class="input-field @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="bio" class="block text-gray-700 font-bold mb-2">Bio</label>
                            <textarea id="bio" name="bio" rows="3" class="input-field @error('bio') border-red-500 @enderror">{{ old('bio', auth()->user()->extendedProfile->bio ?? '') }}</textarea>
                            @error('bio')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="social_networks" class="block text-gray-700 font-bold mb-2">Redes Sociais (separadas por vírgula)</label>
                            <input type="text" id="social_networks" name="social_networks" value="{{ old('social_networks', auth()->user()->extendedProfile->social_networks ?? '') }}" class="input-field @error('social_networks') border-red-500 @enderror">
                            @error('social_networks')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn-primary">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Seções adicionais para eventos e avaliações do usuário -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="card">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-kpop-purple mb-4">Meus Eventos</h2>
                @if(auth()->user()->participatingEvents->count() > 0)
                    <ul class="space-y-3">
                        @foreach(auth()->user()->participatingEvents as $event)
                            <li class="border-b border-gray-200 pb-3">
                                <a href="{{ route('events.show', $event) }}" class="font-semibold text-kpop-blue hover:underline">{{ $event->name }}</a>
                                <p class="text-sm text-gray-600">{{ $event->event_date->format('d/m/Y') }} - {{ $event->location }}</p>
                                <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full bg-{{ 
                                    $event->pivot->status == 'confirmed' ? 'green' : 
                                    ($event->pivot->status == 'waiting' ? 'yellow' : 'red')
                                }}-100 text-{{ 
                                    $event->pivot->status == 'confirmed' ? 'green' : 
                                    ($event->pivot->status == 'waiting' ? 'yellow' : 'red')
                                }}-800">
                                    {{ ucfirst($event->pivot->status) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600">Você não está participando de nenhum evento.</p>
                    <a href="{{ route('events.index') }}" class="btn-primary inline-block mt-4">Explorar Eventos</a>
                @endif
            </div>
        </div>
        
        <div class="card">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-kpop-pink mb-4">Minhas Avaliações</h2>
                @if(auth()->user()->ratings->count() > 0)
                    <ul class="space-y-3">
                        @foreach(auth()->user()->ratings as $rating)
                            <li class="border-b border-gray-200 pb-3">
                                <div class="flex justify-between">
                                    <a href="{{ route('groups.musics.show', [$rating->music->group, $rating->music]) }}" class="font-semibold text-kpop-purple hover:underline">{{ $rating->music->title }}</a>
                                    <span class="text-sm text-gray-500">{{ $rating->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->rating)
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                                @if($rating->comment)
                                    <p class="mt-1 text-sm">{{ $rating->comment }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600">Você ainda não avaliou nenhuma música.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('profile-picture-input').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            document.getElementById('profile-picture-form').submit();
        }
    });
</script>
@endsection