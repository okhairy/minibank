<?php

namespace App\Http\Controllers;

use App\Models\Distributeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DistributeurController extends Controller
{
    public function index()
    {
        $distributeurs = Distributeur::latest()->paginate(10);
        return view('distributeurs.index', compact('distributeurs'));
    }

    public function create()
    {
        return view('distributeurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:distributeurs',
            'photo' => 'required|image|max:1024',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'num_carte_identite' => 'required|string|unique:distributeurs'
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('distributeurs', 'public');
            $validated['photo'] = $path;
        }

        Distributeur::create($validated);

        return redirect()->route('distributeurs.index')
            ->with('success', 'Distributeur créé avec succès');
    }

    public function edit(Distributeur $distributeur)
    {
        return view('distributeurs.edit', compact('distributeur'));
    }

    public function update(Request $request, Distributeur $distributeur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:distributeurs,telephone,' . $distributeur->id,
            'photo' => 'nullable|image|max:1024',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'num_carte_identite' => 'required|string|unique:distributeurs,num_carte_identite,' . $distributeur->id
        ]);

        if ($request->hasFile('photo')) {
            if ($distributeur->photo) {
                Storage::disk('public')->delete($distributeur->photo);
            }
            $path = $request->file('photo')->store('distributeurs', 'public');
            $validated['photo'] = $path;
        }

        $distributeur->update($validated);

        return redirect()->route('distributeurs.index')
            ->with('success', 'Distributeur mis à jour avec succès');
    }

    public function credit(Request $request, Distributeur $distributeur)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0'
        ]);

        $distributeur->increment('solde', $request->montant);

        return redirect()->route('distributeurs.index')
            ->with('success', 'Compte crédité avec succès');
    }

    public function toggleStatus(Distributeur $distributeur)
    {
        $distributeur->update([
            'status' => $distributeur->status === 'actif' ? 'bloque' : 'actif'
        ]);

        return redirect()->route('distributeurs.index')
            ->with('success', 'Statut du distributeur mis à jour avec succès');
    }
}
