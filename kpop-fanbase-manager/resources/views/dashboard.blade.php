<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard do Usuário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Você está logado como um usuário comum!") }}
                    <p>Bem-vindo(a) ao KPOP FanBase Manager, {{ Auth::user()->name }}!</p>
                    <p>Aqui você poderá ver seus eventos, avaliações e trocas.</p>

                    {{-- Conteúdo dinâmico para o dashboard do usuário --}}
                    {{-- Adaptação dos cards do seu PDF --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                        {{-- Card de Próximos Eventos --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Próximos Eventos</h3>
                            {{-- Aqui você pode iterar sobre uma variável $events que viria do seu DashboardController --}}
                            {{-- Exemplo adaptado do seu PDF: --}}
                            @php
                                // Mock de dados para demonstração. Em um projeto real, você buscaria isso do DB.
                                $mockEvents = [
                                    (object)['id' => 1, 'name' => 'KCON LA 2025', 'event_date' => \Carbon\Carbon::parse('2025-08-15 18:00:00'), 'location' => 'Los Angeles'],
                                    (object)['id' => 2, 'name' => 'Show BTS - SP', 'event_date' => \Carbon\Carbon::parse('2025-09-20 20:00:00'), 'location' => 'São Paulo'],
                                ];
                                $events = collect($mockEvents); // Converte para uma coleção para simular eloquent
                            @endphp
                            @if($events->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($events as $event)
                                        <li>
                                            <a href="#" class="font-semibold text-blue-600 hover:underline">
                                                {{ $event->name }}
                                            </a>
                                            <p class="text-sm text-gray-600">{{ $event->event_date->format('d/m/Y H:i') }} - {{ $event->location }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-600">Nenhum evento próximo.</p>
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 mt-2 block">Ver Todos Eventos</a>
                            @endif
                        </div>

                        {{-- Card de Minhas Avaliações --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Minhas Avaliações</h3>
                            {{-- Mock de dados para demonstração --}}
                            @php
                                $mockRatings = [
                                    (object)['music' => (object)['title' => 'DDU-DU DDU-DU'], 'score' => 5, 'comment' => 'Melhor música do BLACKPINK!'],
                                    (object)['music' => (object)['title' => 'Gods'], 'score' => 4, 'comment' => 'Hino do LOL!'],
                                ];
                                $ratings = collect($mockRatings);
                            @endphp
                            @if($ratings->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($ratings as $rating)
                                        <li>
                                            <p class="text-sm">Você avaliou <span class="font-semibold">{{ $rating->music->title }}</span> com:</p>
                                            <div class="star-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $rating->score)
                                                        <span class="text-yellow-400">★</span>
                                                    @else
                                                        <span class="text-gray-300">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $rating->comment }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-600">Nenhuma avaliação recente.</p>
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 mt-2 block">Ver Minhas Avaliações</a>
                            @endif
                        </div>

                        {{-- Card de Minhas Trocas --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Minhas Trocas</h3>
                            {{-- Mock de dados para demonstração --}}
                            @php
                                $mockTrocas = [
                                    (object)['outro_usuario' => (object)['name' => 'Maria Fã'], 'data_troca' => \Carbon\Carbon::parse('2025-05-10'), 'status' => 'Concluída'],
                                    (object)['outro_usuario' => (object)['name' => 'João Colecionador'], 'data_troca' => \Carbon\Carbon::parse('2025-06-01'), 'status' => 'Em andamento'],
                                ];
                                $trocas = collect($mockTrocas);
                            @endphp
                            @if($trocas->count() > 0)
                                <ul class="space-y-3">
                                    @foreach($trocas as $troca)
                                        <li>
                                            <p class="text-sm">Troca com <span class="font-semibold">{{ $troca->outro_usuario->name }}</span> em {{ $troca->data_troca->format('d/m/Y') }}</p>
                                            <p class="text-xs text-gray-500">Status: {{ $troca->status }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-600">Nenhuma troca em andamento.</p>
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 mt-2 block">Ver Minhas Trocas</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>