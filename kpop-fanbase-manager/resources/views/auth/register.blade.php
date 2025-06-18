@extends('layouts.app')

@section('title', 'Registrar')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl my-10">
    <div class="card-header">
        <h2 class="text-2xl font-bold text-center">Junte-se à KPOP FanBase</h2>
    </div>
    <div class="p-8">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-bold mb-2">Nome</label>
                <input id="name" type="text" class="input-field @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-bold mb-2">E-mail</label>
                <input id="email" type="email" class="input-field @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="birth_date" class="block text-gray-700 font-bold mb-2">Data de Nascimento (opcional)</label>
                <input id="birth_date" type="date" class="input-field @error('birth_date') border-red-500 @enderror" name="birth_date" value="{{ old('birth_date') }}">
                @error('birth_date')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-2">Senha</label>
                <input id="password" type="password" class="input-field @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password-confirm" class="block text-gray-700 font-bold mb-2">Confirmar Senha</label>
                <input id="password-confirm" type="password" class="input-field" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="flex items-center justify-center">
                <button type="submit" class="btn-primary">
                    Registrar
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-gray-600">Já tem uma conta? <a href="{{ route('login') }}" class="text-kpop-pink hover:underline">Faça login</a></p>
        </div>
    </div>
</div>
@endsection
