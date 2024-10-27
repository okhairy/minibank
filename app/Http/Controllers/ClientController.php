<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:clients',
            'photo' => 'required|image|max:1024',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'num_carte_identite' => 'required|string|unique:clients'
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('clients', 'public');
            $validated['photo'] = $path;
        }

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:clients,telephone,' . $client->id,
            'photo' => 'nullable|image|max:1024',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'num_carte_identite' => 'required|string|unique:clients,num_carte_identite,' . $client->id
        ]);

        if ($request->hasFile('photo')) {
            if ($client->photo) {
                Storage::disk('public')->delete($client->photo);
            }
            $path = $request->file('photo')->store('clients', 'public');
            $validated['photo'] = $path;
        }

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès');
    }

    public function toggleStatus(Client $client)
    {
        $client->update([
            'status' => $client->status === 'actif' ? 'bloque' : 'actif'
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Statut du client mis à jour avec succès');
    }
}
