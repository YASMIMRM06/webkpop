<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está logado e se ele tem a role 'admin'
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            // Se não for admin, redireciona ou retorna um erro 403 (Forbidden)
            abort(403, 'Acesso não autorizado. Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}