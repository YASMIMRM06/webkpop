<!DOCTYPE html> 
<html lang="pt-BR"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>KPOP FanBase Manager - @yield('title')</title> 
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> 
    <link 
href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Noto+Sans+KR:wght@400;700&display=swap" 
rel="stylesheet"> 
</head> 
<body class="font-sans"> 
    <!-- Navbar --> 
    <nav class="navbar bg-gradient-to-r from-kpop-purple to-kpop-black"> 
        <div class="container mx-auto flex justify-between items-center"> 
            <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-kpop-yellow font-korean">KPOP FanBase</a> 
            <div class="hidden md:flex space-x-4"> 
                @auth 
                    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a> 
                    <a href="{{ route('groups.index') }}" class="nav-link">Grupos</a> 
                    <a href="{{ route('events.index') }}" class="nav-link">Eventos</a> 
                    @if(auth()->user()->isAdmin()) 
                        <a href="{{ route('users.index') }}" class="nav-link">Usuários</a> 
                    @endif 
                    <a href="{{ route('users.profile') }}" class="nav-link">Meu Perfil</a> 
                    <form action="{{ route('logout') }}" method="POST"> 
                        @csrf 
                        <button type="submit" class="nav-link">Sair</button> 
                    </form> 
                @else 
                    <a href="{{ route('login') }}" class="nav-link">Login</a> 
                    <a href="{{ route('register') }}" class="nav-link">Registrar</a> 
                @endauth 
            </div> 
        </div> 
    </nav> 
    <!-- Conteúdo --> 
    <main class="container mx-auto py-8 px-4"> 
        @if(session('success')) 
            <div class="alert-success mb-6"> 
                {{ session('success') }} 
            </div> 
        @endif 
        @if(session('error')) 
            <div class="alert-error mb-6"> 
                {{ session('error') }} 
            </div> 
        @endif    
        @yield('content') 
    </main> 
    <!-- Footer --> 
    <footer class="bg-kpop-black text-white py-6"> 
        <div class="container mx-auto text-center"> 
            <p>(c) 2023 KPOP FanBase Manager - Todos os direitos reservados</p> 
        </div> 
    </footer> 
    <script src="{{ asset('js/app.js') }}"></script> 
</body> 
</html>