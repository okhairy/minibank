@extends('layouts.admin')

@section('title', 'Nouveau client')

@section('header', 'Créer un nouveau client')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" for="photo">Photo</label>
                <input type="file" name="photo" id="photo" class="w-full" required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1" for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1" for="telephone">Téléphone</label>
                    <input type="tel" name="telephone" id="telephone" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="date_naissance">Date de naissance</label>
                    <input type="date" name="date_naissance" id="date_naissance" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" for="adresse">Adresse</label>
                <textarea name="adresse" id="adresse" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" for="num_carte_identite">Numéro de carte d'identité</label>
                <input type="text" name="num_carte_identite" id="num_carte_identite" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Créer le client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection