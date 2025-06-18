@extends('layouts.app')

@section('title', $music->title)

@section('content')
<div class="flex flex-col md:flex-row gap-8">
    <!-- Informações da música -->
    <div class="md:w-2/3">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="card-header flex justify-between items-center">
                <h1 class="text-2xl font-bold">{{ $music->title }}</h1>
                <span class="bg-kpop-yellow text-kpop-black px-3 py-1 rounded-full font-bold">
                    {{ number_format($music->average_rating, 1) }} <i class="fas fa-star"></i>
                </span>
            </div>
            
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <img src="{{ $group->photo ?? 'https://via.placeholder.com/80' }}" alt="{{ $group->name }}" class="w-16 h-16 rounded-full mr-4">
                    <div>
                        <h2 class="text-xl font-bold text-kpop-purple">{{ $group->name }}</h2>
                        <p class="text-gray-600">Lançamento: {{ $music->release_date->format('d/m/Y') }}</p>
                        <p class="text-gray-600">Duração: {{ $music->duration }}</p>
                    </div>
                </div>

                @if($music->youtube_link)
                    <div class="mb-6 aspect-w-16 aspect-h-9">
                        <iframe class="w-full h-96" src="https://www.youtube.com/embed/{{ getYouTubeId($music->youtube_link) }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                @endif

                <h3 class="text-xl font-bold mb-4 text-kpop-pink">Avaliações</h3>
                
                @auth
                    @if(!$userRating)
                        <form action="{{ route('ratings.store', $music) }}" method="POST" class="mb-6">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Sua avaliação</label>
                                <div class="flex space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" onclick="setRating({{ $i }})" class="text-2xl">
                                            <i class="far fa-star rating-star" data-value="{{ $i }}"></i>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating-value" value="0" required>
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="block text-gray-700 mb-2">Comentário (opcional)</label>
                                <textarea name="comment" id="comment" rows="3" class="form-input"></textarea>
                            </div>
                            <button type="submit" class="btn-primary">Enviar Avaliação</button>
                        </form>
                    @endif
                @endauth

                <div class="space-y-4">
                    @forelse($music->ratings as $rating)
                        <div class="border-b border-gray-200 pb-4 last:border-0">
                            <div class="flex justify-between items-center mb-2">
                                <div class="font-bold">{{ $rating->user->name }}</div>
                                <div class="flex items-center text-kpop-yellow">
                                    @for($i = 0; $i < $rating->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($rating->comment)
                                <p class="text-gray-700">{{ $rating->comment }}</p>
                            @endif
                            <div class="text-sm text-gray-500 mt-2">{{ $rating->created_at->format('d/m/Y H:i') }}</div>
                            
                            @auth
                                @if(Auth::id() == $rating->user_id || Auth::user()->isAdmin())
                                    <div class="mt-2 flex space-x-2">
                                        @if(Auth::id() == $rating->user_id)
                                            <button onclick="editRating({{ $rating->id }})" class="text-sm text-kpop-blue hover:underline">Editar</button>
                                        @endif
                                        <form action="{{ route('ratings.destroy', $rating) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-500 hover:underline">Excluir</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    @empty
                        <p class="text-gray-500">Nenhuma avaliação ainda. Seja o primeiro a avaliar!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Outras músicas do grupo -->
    <div class="md:w-1/3">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="card-header">
                <h2 class="text-xl font-bold">Mais de {{ $group->name }}</h2>
            </div>
            <div class="p-4">
                @foreach($group->musics->where('id', '!=', $music->id)->take(5) as $otherMusic)
                    <a href="{{ route('groups.musics.show', [$group, $otherMusic]) }}" class="block p-3 hover:bg-pink-50 rounded-lg transition mb-2">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ $otherMusic->title }}</span>
                            <span class="text-sm text-kpop-yellow font-bold">
                                {{ number_format($otherMusic->average_rating, 1) }} <i class="fas fa-star"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
                <a href="{{ route('groups.musics.index', $group) }}" class="btn-secondary mt-4 inline-block w-full text-center">
                    Ver Todas as Músicas
                </a>
            </div>
        </div>
    </div>
</div>

@auth
    @if($userRating)
        <!-- Modal para edição de avaliação -->
        <div id="editRatingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-xl font-bold mb-4 text-kpop-pink">Editar Avaliação</h3>
                <form action="{{ route('ratings.update', $userRating) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Sua avaliação</label>
                        <div class="flex space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setEditRating({{ $i }})" class="text-2xl">
                                    <i class="far fa-star edit-rating-star" data-value="{{ $i }}"></i>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="edit-rating-value" value="{{ $userRating->rating }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit-comment" class="block text-gray-700 mb-2">Comentário (opcional)</label>
                        <textarea name="comment" id="edit-comment" rows="3" class="form-input">{{ $userRating->comment }}</textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('editRatingModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-lg">Cancelar</button>
                        <button type="submit" class="btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endauth

<script>
    function setRating(value) {
        document.getElementById('rating-value').value = value;
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach(star => {
            if (star.dataset.value <= value) {
                star.classList.remove('far');
                star.classList.add('fas', 'text-kpop-yellow');
            } else {
                star.classList.remove('fas', 'text-kpop-yellow');
                star.classList.add('far');
            }
        });
    }

    function editRating(id) {
        const modal = document.getElementById('editRatingModal');
        modal.classList.remove('hidden');
    }

    function setEditRating(value) {
        document.getElementById('edit-rating-value').value = value;
        const stars = document.querySelectorAll('.edit-rating-star');
        stars.forEach(star => {
            if (star.dataset.value <= value) {
                star.classList.remove('far');
                star.classList.add('fas', 'text-kpop-yellow');
            } else {
                star.classList.remove('fas', 'text-kpop-yellow');
                star.classList.add('far');
            }
        });
    }

    // Inicializar estrelas da avaliação existente
    @auth
        @if($userRating)
            document.addEventListener('DOMContentLoaded', function() {
                setEditRating({{ $userRating->rating }});
            });
        @endif
    @endauth
</script>
@endsection