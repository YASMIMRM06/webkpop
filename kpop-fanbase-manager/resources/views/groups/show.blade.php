@extends('layouts.app') 
@section('title', $group->name) 
@section('content') 
<div class="flex flex-col md:flex-row gap-8 mb-8"> 
    <div class="md:w-1/3"> 
        <div class="card"> 
            <div class="h-64 overflow-hidden"> 
                <img src="{{ $group->photo ? asset('storage/'.$group->photo) : 
asset('images/default-group.png') }}" alt="{{ $group->name }}" class="w-full h-full object-cover"> 
            </div> 
            <div class="p-4"> 
                <h1 class="text-2xl font-bold text-kpop-purple mb-2">{{ $group->name }}</h1> 
                <p class="text-gray-600 mb-2"><span class="font-semibold">Empresa:</span> {{ 
$group->company }}</p> 
                <p class="text-gray-600 mb-2"><span class="font-semibold">Formado em:</span> {{ 
$group->formation_date->format('d/m/Y') }}</p> 
                <p class="text-gray-600 mb-4"><span class="font-semibold">Descrição:</span> {{ 
$group->description ?? 'Nenhuma descrição disponível.' }}</p>                
                @auth 
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager()) 
                        <div class="flex space-x-2 mt-4"> 
                            <a href="{{ route('groups.edit', $group) }}" 
class="btn-secondary">Editar</a> 
                            <form action="{{ route('groups.destroy', $group) }}" method="POST" 
onsubmit="return confirm('Tem certeza que deseja excluir este grupo?');"> 
                                @csrf 
                                @method('DELETE') 
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white 
font-bold py-2 px-4 rounded-full transition duration-300">Excluir</button> 
                            </form> 
                        </div> 
                    @endif 
                @endauth 
            </div> 
        </div> 
    </div> 
    <div class="md:w-2/3"> 
        <div class="flex justify-between items-center mb-6"> 
            <h2 class="text-2xl font-bold text-kpop-pink">Músicas</h2> 
            @auth 
                @if(auth()->user()->isAdmin() || auth()->user()->isManager()) 
                    <a href="{{ route('groups.musics.create', $group) }}" 
class="btn-primary">Adicionar Música</a> 
                @endif 
            @endauth 
        </div> 
        @if($group->musics->count() > 0) 
            <div class="bg-white rounded-lg shadow overflow-hidden"> 
                <table class="min-w-full divide-y divide-gray-200"> 
                    <thead class="bg-kpop-black text-white"> 
                        <tr> 
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase 
tracking-wider">Título</th> 
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase 
tracking-wider">Duração</th> 
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase 
tracking-wider">Lançamento</th> 
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase 
tracking-wider">Avaliação</th> 
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase 
tracking-wider">Ações</th> 
                        </tr> 
                    </thead> 
                    <tbody class="bg-white divide-y divide-gray-200"> 
                        @foreach($group->musics as $music) 
                            <tr class="hover:bg-gray-50"> 
                                <td class="px-6 py-4 whitespace-nowrap"> 
                                    <div class="flex items-center"> 
                                        <div class="flex-shrink-0 h-10 w-10"> 
                                            <img class="h-10 w-10 rounded-full" 
src="https://img.youtube.com/vi/{{ $music->youtube_id }}/default.jpg" alt=""> 
                                        </div> 
                                        <div class="ml-4"> 
                                            <div class="text-sm font-medium text-gray-900">{{ 
$music->title }}</div> 
                                        </div> 
                                    </div> 
                                </td> 
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ 
$music->duration }}</td> 
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ 
$music->release_date->format('d/m/Y') }}</td> 
                                <td class="px-6 py-4 whitespace-nowrap"> 
                                    <div class="star-rating"> 
                                        @for($i = 1; $i <= 5; $i++) 
                                            @if($i <= round($music->average_rating)) 
                                                ★ 
                                            @else 
                                                ☆ 
                                            @endif 
                                        @endfor 
                                        <span class="text-sm text-gray-600 ml-1">({{ 
$music->ratings_count }})</span> 
                                    </div> 
                                </td> 
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium"> 
                                    <a href="{{ route('groups.musics.show', [$group, $music]) }}" 
class="text-kpop-blue hover:text-kpop-purple mr-3">Ver</a> 
                                    @auth 
                                        @if(auth()->user()->isAdmin() || 
auth()->user()->isManager()) 
                                            <a href="{{ route('groups.musics.edit', [$group, 
$music]) }}" class="text-kpop-yellow hover:text-kpop-pink mr-3">Editar</a> 
                                            <form action="{{ route('groups.musics.destroy', [$group, 
$music]) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir 
esta música?');"> 
                                                @csrf 
                                                @method('DELETE') 
                                                <button type="submit" class="text-red-500 
hover:text-red-700">Excluir</button> 
                                            </form> 
                                        @endif 
                                    @endauth 
                                </td> 
                            </tr> 
                        @endforeach 
                    </tbody> 
                </table> 
            </div> 
        @else 
            <div class="bg-white rounded-lg shadow p-6 text-center"> 
                <p class="text-gray-600">Nenhuma música cadastrada para este grupo.</p> 
                @auth 
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager()) 
                        <a href="{{ route('groups.musics.create', $group) }}" class="btn-primary 
inline-block mt-4">Adicionar Primeira Música</a> 
                    @endif 
                @endauth 
            </div> 
        @endif 
    </div> 
</div> 
@endsection