<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MusicController; // Exemplo do seu controller
use App\Http\Controllers\AdminController; // Exemplo de um controller para admin
use Illuminate\Support\Facades\Route;

// Rota inicial do projeto
Route::get('/', function () {
    return view('welcome');
});

// Rota do Dashboard padrão (para usuários comuns)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Suas rotas para perfis de usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Exemplos de rotas para funcionalidades de usuário comum (ex: Avaliações, Participação em Eventos)
    Route::get('/minhas-avaliacoes', [AvaliacaoController::class, 'index'])->name('avaliacoes.index');
    // ...
});

// Rotas para funcionalidades de ADMINISTRAÇÃO
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    // ... adicione outras rotas de administração aqui (gerenciamento de grupos, eventos, etc.)

    // Exemplo de rotas para gerenciamento de músicas (CRUD completo)
    Route::resource('musicas', MusicController::class)->except(['index', 'show']); // Exceto index e show, que podem ser públicas
});

// Rotas públicas (ou para todos os usuários logados, não apenas admin)
Route::get('/musicas', [MusicController::class, 'index'])->name('musicas.index');
Route::get('/musicas/{music}', [MusicController::class, 'show'])->name('musicas.show');


// Rotas de autenticação do Breeze
require __DIR__.'/auth.php';