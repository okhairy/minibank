<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Models\Distributeur; // Assurez-vous d'utiliser le bon modèle
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function showLoginForm()
{
    if (Auth::check()) {
        // Si l'utilisateur est déjà connecté, redirigez-le vers le tableau de bord
        return redirect()->intended('dashboard');
    }

    return view('auth.login');
}
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Utilisation du guard pour les distributeurs
        if (Auth::guard('distributeur')->attempt($credentials)) {
            // Authentification réussie
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Les informations d\'identification fournies sont incorrectes.',
        ]);
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/'); // Rediriger vers la page d'accueil ou une autre page
    }
    
}