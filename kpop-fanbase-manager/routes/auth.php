<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Rota inicial do projeto
Route::get('/', function () {
    return view('welcome');
});

// --- Rotas Protegidas (Exigem Autenticação) ---
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard padrão para usuários comuns
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rotas de perfil (geradas pelo Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Exemplos de rotas para funcionalidades de usuário comum (você vai expandir isso)
    // Ex: Route::get('/minhas-avaliacoes', [AvaliacaoController::class, 'index'])->name('avaliacoes.index');
    // Ex: Route::get('/meus-eventos', [EventoController::class, 'userEvents'])->name('eventos.meus');
});

// --- Rotas de ADMINISTRAÇÃO (Exigem Autenticação E Role 'admin') ---
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard exclusivo para administradores
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Gerenciamento de usuários por admin
    Route::get('/admin/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    // Outras rotas de administração (gerenciamento de grupos, eventos, etc.)
    // Ex: Route::resource('admin/grupos', GrupoController::class);

    // Gerenciamento completo de Músicas (CRUD) por administradores
    // Usamos 'except' para não gerar as rotas 'index' e 'show' aqui, pois elas são públicas/acessíveis a todos.
    Route::resource('musicas', MusicController::class)->except(['index', 'show']);
});

// --- Rotas Públicas ou Acessíveis a Todos os Usuários (Logados ou Não) ---
// Listagem e visualização de detalhes de músicas (qualquer um pode ver)
Route::get('/musicas', [MusicController::class, 'index'])->name('musicas.index');
Route::get('/musicas/{musica}', [MusicController::class, 'show'])->name('musicas.show');


// --- Rotas de Autenticação do Laravel Breeze ---
// Este comando inclui todas as rotas de login, registro, logout, redefinição de senha, etc.
require __DIR__.'/auth.php';