<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel Administrativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Bem-vindo ao Painel de Administração!") }}
                    <p>Como administrador, você tem acesso total às funcionalidades de gerenciamento do sistema.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                        {{-- Card: Gerenciar Usuários --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Gerenciar Usuários</h3>
                            <p class="text-gray-600">Visualize, edite e remova usuários.</p>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2">Acessar</a>
                        </div>

                        {{-- Card: Gerenciar Músicas --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Gerenciar Músicas</h3>
                            <p class="text-gray-600">Adicione, edite ou exclua músicas.</p>
                            <div class="flex flex-col sm:flex-row gap-2 mt-2">
                                <a href="{{ route('musicas.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Ver Músicas</a>
                                <a href="{{ route('musicas.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">Adicionar Nova</a>
                            </div>
                        </div>

                        {{-- Card: Gerenciar Grupos --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Gerenciar Grupos</h3>
                            <p class="text-gray-600">Controle os grupos de KPOP (bandas).</p>
                            <a href="#" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2">Acessar</a>
                        </div>
                        {{-- Adicione mais cards para outras funcionalidades de admin (Eventos, Trocas, etc.) --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>