<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response; // Assurez-vous d'importer cette classe si vous l'utilisez

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est authentifié et s'il a le rôle d'admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        
        // Redirige vers la page d'accueil avec un message d'erreur si non autorisé
        return redirect('/')->with('error', 'Accès non autorisé. Vous devez être un administrateur.');
    }
}
