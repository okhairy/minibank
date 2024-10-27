@extends('layouts.admin')

@section('title', 'Modifier le Compte')

@section('header', 'Modifier le Compte')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Modifier le Compte</h2>
                <p class="mt-1 text-sm text-gray-600">Mettez à jour les informations du compte.</p>
            </div>

            <div class="px-8 py-6">
                <form action="{{ route('comptes.update', $compte->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Message d'erreur global -->
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <strong class="font-bold">Erreur!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Champ Numéro de Compte (readonly) -->
                    <div class="mb-6">
                        <label for="numero_compte" class="block text-sm font-medium text-gray-700 mb-1">
                            Numéro de Compte
                        </label>
                        <input type="text" 
                               name="numero_compte" 
                               id="numero_compte" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 cursor-not-allowed" 
                               value="{{ $compte->numero_compte }}" 
                               readonly>
                    </div>

                    <!-- Nom et Prénom -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom
                            </label>
                            <input type="text" 
                                   name="nom" 
                                   id="nom" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                   value="{{ old('nom', $compte->nom) }}" 
                                   required>
                        </div>
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">
                                Prénom
                            </label>
                            <input type="text" 
                                   name="prenom" 
                                   id="prenom" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                   value="{{ old('prenom', $compte->prenom) }}" 
                                   required>
                        </div>
                    </div>

                    <!-- Téléphone et Date de naissance -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="numero_telephone" class="block text-sm font-medium text-gray-700 mb-1">
                                Téléphone
                            </label>
                            <input type="tel" 
                                   name="numero_telephone" 
                                   id="numero_telephone" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                   value="{{ old('numero_telephone', $compte->numero_telephone) }}" 
                                   required>
                        </div>
                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-1">
                                Date de Naissance
                            </label>
                            <input type="date" 
                                   name="date_naissance" 
                                   id="date_naissance" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                   value="{{ old('date_naissance', $compte->date_naissance) }}" 
                                   required>
                        </div>
                    </div>

                    <!-- Adresse et Numéro de carte d'identité -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">
                                Adresse
                            </label>
                            <input type="text" 
                                   name="adresse" 
                                   id="adresse" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                   value="{{ old('adresse', $compte->adresse) }}" 
                                   required>
                        </div>
                        <div>
                            <label for="numero_carte_identite" class="block text-sm font-medium text-gray-700 mb-1">
                                Numéro de Carte d'Identité
                            </label>
                            <input type="text" 
                                   name="numero_carte_identite" 
                                   id="numero_carte_identite" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                   value="{{ old('numero_carte_identite', $compte->numero_carte_identite) }}" 
                                   required>
                        </div>
                    </div>

                    <!-- Rôle -->
                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                            Rôle
                        </label>
                        <select name="role" id="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="client" {{ old('role', $compte->role) == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="distributeur" {{ old('role', $compte->role) == 'distributeur' ? 'selected' : '' }}>Distributeur</option>
                        </select>
                    </div>

                    <!-- Statut -->
                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Statut
                        </label>
                        <input type="text" 
                               name="status" 
                               id="status" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 cursor-not-allowed" 
                               value="actif" 
                               readonly>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                            Enregistrer les Modifications
                        </button>
                        <a href="{{ route('comptes.index') }}" class="w-full sm:w-auto px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection