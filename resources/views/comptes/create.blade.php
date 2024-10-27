@extends('layouts.admin')

@section('title', 'Ajouter un Compte')

@section('header', 'Ajouter un Compte')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="w-full">
                <div class="px-8 py-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">Ajouter un Compte</h2>
                    <p class="mt-1 text-sm text-gray-600">Veuillez remplir tous les champs pour créer un nouveau compte.</p>
                </div>

                <div class="px-8 py-6">
                    <form action="{{ route('comptes.store') }}" method="POST" id="accountForm">
                        @csrf

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

                        <!-- Champ Numéro de Compte -->
                        <div class="mb-6">
                            <label for="numero_compte" class="block text-sm font-medium text-gray-700 mb-1">
                                Numéro de Compte <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                name="numero_compte" 
                                id="numero_compte" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 cursor-not-allowed" 
                                value="{{ $numero_compte }}" 
                                readonly>
                        </div>

                        <!-- Champ Status -->
                        <input type="hidden" name="status" value="actif">

                        <!-- Nom et Prénom -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="nom" 
                                       id="nom" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                       placeholder="Entrez le nom"
                                       required>
                                <span class="text-red-500 text-sm hidden" id="nomError">Veuillez entrer un nom valide.</span>
                            </div>
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="prenom" 
                                       id="prenom" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                       placeholder="Entrez le prénom"
                                       required>
                                <span class="text-red-500 text-sm hidden" id="prenomError">Veuillez entrer un prénom valide.</span>
                            </div>
                        </div>

                        <!-- Téléphone et Date de naissance -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="numero_telephone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Téléphone <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       name="numero_telephone" 
                                       id="numero_telephone" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                       placeholder="Entrez le téléphone"
                                       required>
                                <span class="text-red-500 text-sm hidden" id="telephoneError">Veuillez entrer un numéro de téléphone valide.</span>
                            </div>
                            <div>
                                <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-1">
                                    Date de Naissance <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="date_naissance" 
                                       id="date_naissance" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                       required>
                                <span class="text-red-500 text-sm hidden" id="dateNaissanceError">Veuillez entrer une date valide.</span>
                            </div>
                        </div>

                        <!-- Adresse et Numéro de carte d'identité -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">
                                    Adresse <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="adresse" 
                                       id="adresse" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                       placeholder="Entrez l'adresse"
                                       required>
                                <span class="text-red-500 text-sm hidden" id="adresseError">Veuillez entrer une adresse valide.</span>
                            </div>
                            <div>
                                <label for="numero_carte_identite" class="block text-sm font-medium text-gray-700 mb-1">
                                    Numéro de Carte d'Identité <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="numero_carte_identite" 
                                       id="numero_carte_identite" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                       placeholder="Entrez le numéro de carte d'identité"
                                       required>
                                <span class="text-red-500 text-sm hidden" id="numeroIdentiteError">Veuillez entrer un numéro valide.</span>
                            </div>
                        </div>

                        <!-- Rôle -->
                        <div class="mb-6">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                Rôle <span class="text-red-500">*</span>
                            </label>
                            <select name="role" 
                                    id="role" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" 
                                    required>
                                <option value="client">Client</option>
                                <option value="distributeur">Distributeur</option>
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
                            <button type="submit" 
                                    class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg" 
                                    id="submitButton">
                                Ajouter Compte
                            </button>
                            <a href="{{ route('comptes.index') }}" class="w-full sm:w-auto px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg">
                                Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('accountForm');
    const submitButton = document.getElementById('submitButton');

    // Vérifiez si le formulaire est valide
    form.addEventListener('input', () => {
        submitButton.disabled = !form.checkValidity();
    });

    // Validation dynamique des champs
    const inputs = ['nom', 'prenom', 'numero_telephone', 'adresse', 'numero_carte_identite'];

    inputs.forEach(input => {
        const element = document.getElementById(input);
        const errorSpan = document.getElementById(`${input}Error`);
        
        element.addEventListener('input', () => {
            errorSpan.classList.toggle('hidden', element.value.trim() !== '');
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const numeroCompteInput = document.getElementById('numero_compte');

    // Fonction pour générer le numéro de compte
    function generateAccountNumber(role) {
        const randomNumber = String(Math.floor(Math.random() * 1000000)).padStart(6, '0');
        return role === 'distributeur' ? 'DIS-' + randomNumber : 'CLI-' + randomNumber;
    }

    // Écoute l'événement de changement sur le champ de sélection du rôle
    roleSelect.addEventListener('change', function() {
        const selectedRole = roleSelect.value;
        const newAccountNumber = generateAccountNumber(selectedRole);
        numeroCompteInput.value = newAccountNumber; // Met à jour le champ de numéro de compte
    });
});
</script>
@endsection