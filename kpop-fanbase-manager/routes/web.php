<?php 
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\GroupController; 
use App\Http\Controllers\MusicController; 
use App\Http\Controllers\EventController; 
use App\Http\Controllers\RatingController; 
use App\Http\Controllers\PermissionController; 
use Illuminate\Foundation\Auth\EmailVerificationRequest; 
// Rotas de Autenticação 
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); 
Route::post('/login', [AuthController::class, 'login']); 
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); 
// Rotas de Registro 
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register'); 
Route::post('/register', [AuthController::class, 'register']); 
// Rotas de Redefinição de Senha 
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request'); 
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email'); 
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset'); 
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update'); 
// Rotas de Verificação de Email 
Route::get('/email/verify', function () { 
    return view('auth.verify-email'); 
})->middleware('auth')->name('verification.notice'); 
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) { 
    $request->fulfill(); 
    return redirect('/'); 
})->middleware(['auth', 'signed'])->name('verification.verify'); 
Route::post('/email/verification-notification', function (Request $request) { 
    $request->user()->sendEmailVerificationNotification(); 
    return back()->with('message', 'Link de verificação enviado!'); 
})->middleware(['auth', 'throttle:6,1'])->name('verification.send'); 
// Rotas Autenticadas 
Route::middleware(['auth', 'verified'])->group(function () { 
    // Dashboard 
    Route::get('/', function () { 
        return view('dashboard'); 
    })->name('dashboard'); 
    // Perfil do Usuário 
    Route::get('/profile', [UserController::class, 'editProfile'])->name('users.profile'); 
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('users.profile.update'); 
    // Recursos de Usuário 
    Route::resource('users', UserController::class)->except(['create', 'store'])->middleware('admin'); 
    // Recursos de Grupo 
    Route::resource('groups', GroupController::class); 
    // Recursos de Música do Grupo 
    Route::prefix('groups/{group}')->group(function () { 
        Route::resource('musics', MusicController::class)->shallow(); 
    }); 
    // Recursos de Evento 
    Route::resource('events', EventController::class); 
    Route::post('events/{event}/participate', [EventController::class, 'participate'])->name('events.participate'); 
    Route::post('events/{event}/cancel', [EventController::class, 'cancelParticipation'])->name('events.cancel'); 
    // Recursos de Avaliação 
    Route::post('musics/{music}/ratings', [RatingController::class, 'store'])->name('ratings.store'); 
    Route::put('ratings/{rating}', [RatingController::class, 'update'])->name('ratings.update'); 
    Route::delete('ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy'); 
    // Recursos de Permissão (apenas admin) 
    Route::middleware('admin')->group(function () { 
        Route::resource('permissions', PermissionController::class); 
        Route::get('users/{user}/permissions', [PermissionController::class, 'userPermissions'])->name('users.permissions'); 
        Route::put('users/{user}/permissions', [PermissionController::class, 
'updateUserPermissions'])->name('users.permissions.update'); 
    }); 
});