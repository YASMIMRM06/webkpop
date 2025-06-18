@extends('layouts.app')

@section('title', $music->title)

@section('content')
<div class="flex flex-col md:flex-row gap-8 mb-8">
    <div class="md:w-1/3">
        <div class="card">
            <div class="aspect-w-16 aspect-h-9">
                <iframe class="w-full h-64" src="https://www.youtube.com/embed/{{ $music->youtube_id }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div class="p-4">
                <h1 class="text-2xl font-bold text-kpop-pink mb-2">{{ $music->title }}</h1>
                <p class="text-gray-600 mb-2"><span class="font-semibold">Grupo:</span> <a href="{{ route('groups.show', $music->group) }}" class="text-kpop-purple hover:underline">{{ $music->group->name }}</a></p>
                <p class="text-gray-600 mb-2"><span class="font-semibold">Duração:</span> {{ $music->duration }}</p>
                <p class="text-gray-600 mb-4"><span class="font-semibold">Lançamento:</span> {{ $music->release_date->format('d/m/Y') }}</p>
                
                <div class="flex items-center mb-4">
                    <div class="star-rating text-2xl mr-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($music->average_rating))
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <span class="text-gray-600">({{ $music->ratings_count }} avaliações)</span>
                </div>
                
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                        <div class="flex space-x-2 mt-4">
                            <a href="{{ route('groups.musics.edit', [$music->group, $music]) }}" class="btn-secondary">Editar</a>
                            <form action="{{ route('groups.musics.destroy', [$music->group, $music]) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta música?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Excluir</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="md:w-2/3">
        <div class="card mb-6">
            <div class="p-4">
                <h2 class="text-xl font-bold text-kpop-purple mb-4">Avaliações</h2>
                
                @auth
                    @if(!$userRating)
                        <form action="{{ route('ratings.store', $music) }}" method="POST" class="mb-6">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2">Sua Avaliação</label>
                                <div class="star-rating text-2xl">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="cursor-pointer" onclick="document.getElementById('rating').value = {{ $i }}; updateStars({{ $i }})">☆</span>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating" value="0">
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="block text-gray-700 font-bold mb-2">Comentário (opcional)</label>
                                <textarea id="comment" name="comment" rows="3" class="input-field">{{ old('comment') }}</textarea>
                            </div>
                            <button type="submit" class="btn-primary">Enviar Avaliação</button>
                        </form>
                    @else
                        <div class="bg-kpop-yellow bg-opacity-10 p-4 rounded-lg mb-6">
                            <h3 class="font-bold text-lg mb-2">Sua Avaliação</h3>
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $userRating->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            @if($userRating->comment)
                                <p class="mt-2">{{ $userRating->comment }}</p>
                            @endif
                            <div class="mt-3 flex space-x-2">
                                <form action="{{ route('ratings.update', $userRating) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="rating" value="{{ $userRating->rating }}">
                                    <input type="hidden" name="comment" value="{{ $userRating->comment }}">
                                    <button type="submit" class="text-sm text-kpop-blue hover:underline">Editar</button>
                                </form>
                                <span>•</span>
                                <form action="{{ route('ratings.destroy', $userRating) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir sua avaliação?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:underline">Excluir</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-100 p-4 rounded-lg mb-6">
                        <p class="text-center">Faça <a href="{{ route('login') }}" class="text-kpop-pink hover:underline">login</a> para avaliar esta música.</p>
                    </div>
                @endauth
                
                @if($music->ratings->count() > 0)
                    <div class="space-y-4">
                        @foreach($music->ratings as $rating)
                            <div class="border-b border-gray-200 pb-4">
                                <div class="flex justify-between">
                                    <h4 class="font-semibold">{{ $rating->user->name }}</h4>
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
                                    <p class="mt-1">{{ $rating->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-center">Nenhuma avaliação ainda. Seja o primeiro a avaliar!</p>
                @endif
            </div>
        </div>
    </div>
</div>

@auth
    @if($userRating)
        <script>
            function updateStars(count) {
                const stars = document.querySelectorAll('.star-rating span');
                stars.forEach((star, index) => {
                    if (index < count) {
                        star.textContent = '★';
                    } else {
                        star.textContent = '☆';
                    }
                });
            }
            
            // Inicializa as estrelas com a avaliação do usuário
            document.addEventListener('DOMContentLoaded', function() {
                updateStars({{ $userRating->rating }});
                document.getElementById('rating').value = {{ $userRating->rating }};
            });
        </script>
    @endif
@endauth
@endsection