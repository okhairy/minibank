<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'cin' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string|max:255',
            'role' => 'required|string|in:Distributeur,Client',
            'photo' => 'nullable|image|max:2048', // Taille maximale de 2MB
        ]);
        
        // Gestion de la photo ou de l'avatar par défaut
        $photoPath = $request->file('photo') 
            ? $request->file('photo')->store('photos', 'public') 
            : 'default-avatar.png'; // Avatar par défaut
    
        // Création de l'utilisateur avec la photo ou l'avatar par défaut
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone,
            'cin' => $request->cin,
            'date_naissance' => $request->date_naissance,
            'adresse' => $request->adresse,
            'role' => $request->role,
            'photo' => $photoPath, // On utilise la photo ou l'avatar par défaut ici
        ]);
    
        // Événement d'inscription
        event(new Registered($user));
    
        // Connexion automatique de l'utilisateur
        Auth::login($user);
    
        return redirect()->route('dashboard')->with('success', 'Inscription réussie.');
    }
    

}
