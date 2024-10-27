<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    /**
     * Où rediriger les utilisateurs après la connexion.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Crée une nouvelle instance de contrôleur.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout'); // Correctement appelé dans le constructeur
    }

    /**
     * Affiche le formulaire de connexion.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Gère une requête d'authentification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Valider les données d'entrée
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tenter de s'authentifier
        if (Auth::attempt($request->only('email', 'password'))) {
            // Authentification réussie, rediriger
            return redirect()->intended($this->redirectTo);
        }

        // Authentification échouée, rediriger avec un message d'erreur
        return back()->withErrors([
            'email' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
        ]);
    }

    /**
     * Déconnexion de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/'); // Rediriger vers la page d'accueil après la déconnexion
    }
}
