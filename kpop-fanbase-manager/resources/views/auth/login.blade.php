@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl my-10">
    <div class="card-header">
        <h2 class="text-2xl font-bold text-center">Login KPOP FanBase</h2>
    </div>
    <div class="p-8">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-bold mb-2">E-mail</label>
                <input id="email" type="email" class="input-field @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-2">Senha</label>
                <input id="password" type="password" class="input-field @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="text-gray-700">Lembrar-me</label>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="btn-primary">
                    Entrar
                </button>

                @if (Route::has('password.request'))
                    <a class="text-kpop-blue hover:underline" href="{{ route('password.request') }}">
                        Esqueceu sua senha?
                    </a>
                @endif
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-gray-600">Não tem uma conta? <a href="{{ route('register') }}" class="text-kpop-pink hover:underline">Registre-se</a></p>
        </div>
    </div>
</div>
@endsection