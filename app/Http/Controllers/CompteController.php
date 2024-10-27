<?php 

namespace App\Http\Controllers;

use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompteController extends Controller
{
    public function index()
    {
        $comptes = Compte::all();
        $comptes = Compte::paginate(10); // 10 comptes par page
        $comptes = Compte::orderBy('created_at', 'desc')->get();
        $comptesBloques = Compte::where('status', 'bloqué')->get(); // Comptes bloqués
        return view('comptes.index', compact('comptes'));
    }

    public function create()
    {
        // Générer un numéro de compte temporaire pour l'afficher dans le formulaire
        $tempRole = 'client'; // Rôle par défaut
        $numero_compte = self::generateAccountNumber($tempRole);

        return view('comptes.create', compact('numero_compte'));
    }

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'numero_compte' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'numero_telephone' => 'required|string|max:20',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string|max:255',
            'numero_carte_identite' => 'required|string|max:50',
            'role' => 'required|string|max:50',
            'status' => 'required|string|in:actif,inactif', // Ajoutez cette ligne pour valider le statut
            // Retrait du champ 'photo'
        ]);

        // Création du compte
        $compte = new Compte();
        $compte->numero_compte = $request->numero_compte;
        $compte->nom = $request->nom;
        $compte->prenom = $request->prenom;
        $compte->numero_telephone = $request->numero_telephone;
        $compte->date_naissance = $request->date_naissance;
        $compte->adresse = $request->adresse;
        $compte->numero_carte_identite = $request->numero_carte_identite;
        $compte->role = $request->role;
        $compte->status = $request->status; // Assurez-vous d'assigner le statut

        // Suppression de la gestion de la photo
        // $compte->photo = 'user.webp';  // Plus besoin de définir une image par défaut

        $compte->save();

        return redirect()->route('comptes.index')->with('success', 'Compte créé avec succès.');
    }

    public function show($id)
    {
        $compte = Compte::findOrFail($id);
        return view('comptes.show', compact('compte'));
    }

    public function comptes_bloques()
    {
        // Récupérer tous les comptes bloqués
        $comptesBloques = Compte::where('status', 'bloqué')->get();

        return view('comptes.bloques', compact('comptesBloques'));
    }

    public function edit($id)
    {
        $compte = Compte::findOrFail($id);
        return view('comptes.edit', compact('compte'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'numero_telephone' => 'required|string|max:15',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'numero_carte_identite' => 'required|string|max:20',
            'role' => 'required|in:client,distributeur',
            // Retrait du champ 'photo'
        ]);

        $compte = Compte::findOrFail($id);
        $compte->fill($request->all());

        // Suppression de la gestion de la photo
        // Si une nouvelle photo est téléchargée, la sauvegarder

        $compte->save();

        return redirect()->route('comptes.index')->with('success', 'Compte mis à jour avec succès.');
    }


    public function bloquer(Request $request, $id)
    {
        // Trouver le compte par son ID
        $compte = Compte::find($id);

        if ($compte) {
            // Mettre à jour le statut du compte
            $compte->status = 'bloqué'; // Assurez-vous que cela correspond à votre logique de statut
            $compte->save();

            // Retournez avec un message de succès
            return redirect()->route('comptes.index')->with('success', 'Le compte a été bloqué avec succès.');
        }

        // Si le compte n'existe pas, retournez une erreur
        return redirect()->route('comptes.index')->with('error', 'Le compte n\'a pas pu être trouvé.');
    }


    public function destroy($id)
    {
        $compte = Compte::findOrFail($id);
        // Changer le statut en inactif
        $compte->status = 'inactif';
        $compte->save();

        return redirect()->route('comptes.index')->with('success', 'Compte bloqué avec succès.');
    }

    // Méthode pour générer un numéro de compte unique
    protected static function generateAccountNumber($role)
    {
        // Générer six chiffres aléatoires
        $randomNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        return ($role === 'distributeur') ? 'DIS-' . $randomNumber : 'CLI-' . $randomNumber; // Format selon le rôle
    }
}