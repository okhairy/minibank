<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs.
     */
    public function index()
    {
        // Récupérer tous les utilisateurs
        $users = User::all();
        
        // Retourner la vue avec les données des utilisateurs
        return view('users.index', compact('users'));
    }
}
