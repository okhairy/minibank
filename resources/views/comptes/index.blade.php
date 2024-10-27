@extends('layouts.admin')

@section('title', 'Liste des Comptes')

@section('header', 'Liste des Comptes')

@section('content')

<!-- Afficher le message de succès si présent -->
@if (session('success'))
    <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <strong>{{ session('success') }}</strong>
    </div>
@endif

<!-- Afficher le message d'erreur si présent -->
@if (session('error'))
    <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <strong>{{ session('error') }}</strong>
    </div>
@endif

<!-- Conteneur pour les boutons d'action -->
<div class="flex justify-between mb-4">
    <a href="{{ route('dashboard') }}" class="btn btn-custom"><i class="fas fa-arrow-left mr-2"></i>Retour vers le Dashboard</a>
    <a href="{{ route('comptes.create') }}" class="btn btn-custom"><i class="fas fa-plus mr-2"></i> Ajouter Compte</a>
</div>

<!-- Modale de confirmation -->
<div id="confirm-modal" class="modal fixed inset-0 bg-gray-800 bg-opacity-75 hidden justify-center items-center">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4 text-center">Confirmation</h3>
        <p class="text-center">Êtes-vous sûr de vouloir bloquer ce compte ?</p>
        <form id="block-form" method="POST" style="display: inline;">
            @csrf
            <input type="hidden" id="account-id" name="account_id">
            <div class="flex justify-center mt-4">
                <button type="submit" class="btn btn-danger mx-2">Oui, bloquer</button>
                <button type="button" id="cancel-btn" class="btn btn-secondary">Annuler</button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg w-full p-4 mt-6">
    <h3 class="text-lg font-semibold mb-4">Liste des Comptes</h3>
    <table class="custom-table">
        <thead>
            <tr>
                <th class="px-6 py-3 border-b text-left">Numéro de Compte</th>
                <th class="px-6 py-3 border-b text-left">Nom</th>
                <th class="px-6 py-3 border-b text-left">Prénom</th>
                <th class="px-6 py-3 border-b text-left">Téléphone</th>
                <th class="px-6 py-3 border-b text-left">Adresse</th>
                <th class="px-6 py-3 border-b text-left">Rôle</th>
                <th class="px-6 py-3 border-b text-left">Statut</th>
                <th class="px-6 py-3 border-b text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comptes as $compte)
            <tr class="{{ $compte->bloque ? 'blocked-row' : '' }}">
                <td class="px-6 py-4">{{ $compte->numero_compte }}</td>
                <td class="px-6 py-4">{{ $compte->nom }}</td>
                <td class="px-6 py-4">{{ $compte->prenom }}</td>
                <td class="px-6 py-4">{{ $compte->numero_telephone }}</td>
                <td class="px-6 py-4">{{ $compte->adresse }}</td>
                <td class="px-6 py-4">{{ $compte->role }}</td>
                <td class="px-6 py-4 {{ $compte->bloque ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">
                    {{ $compte->status }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">                        
                        <a href="{{ route('modifier.compte', $compte->id) }}" class="icon-button text-blue-500 hover:text-blue-700" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('bloquer.compte', $compte->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="button" class="icon-button {{ $compte->bloque ? 'text-gray-400 cursor-not-allowed' : 'text-red-500 hover:text-red-700' }}" title="Bloquer" 
                                @if ($compte->bloque) disabled @endif
                                onclick="{{ $compte->bloque ? 'return false;' : 'openModal(this, \'' . $compte->id . '\')' }}">
                                <i class="fas fa-ban {{ $compte->bloque ? 'opacity-50' : '' }}"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    /* Style de base pour le bouton */
    .btn-custom {
        background-color: #3498db;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-custom:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
    }

    .btn-custom:active {
        background-color: #1f5f7a;
        transform: translateY(0);
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table th,
    .custom-table td {
        border: 1px solid #dee2e6;
        padding: 12px;
        text-align: left;
    }

    .custom-table thead {
        background-color: #f8f9fa;
    }

    .custom-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .custom-table tbody tr:hover {
        background-color: #e9ecef;
    }

    /* Nouvelle classe pour les lignes de comptes bloqués */
    .blocked-row {
        background-color: #ffebee !important; /* Rouge très clair */
        color: #c62828; /* Rouge foncé pour le texte */
    }

    .blocked-row:hover {
        background-color: #ffcdd2 !important; /* Rouge légèrement plus foncé au survol */
    }

    .icon-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s, transform 0.2s;
    }

    .icon-button:hover {
        background-color: rgba(0, 0, 0, 0.2);
        transform: scale(1.1);
    }

    .modal {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal.hidden {
        display: none;
    }

    .btn-danger {
        background-color: #e74c3c;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-danger:hover {
        background-color: #c0392b;
        transform: scale(1.05);
    }

    .btn-secondary {
        background-color: #bdc3c7;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-secondary:hover {
        background-color: #95a5a6;
        transform: scale(1.05);
    }
</style>

<script>
    // Script pour faire disparaître le message après 5 secondes
    window.addEventListener('DOMContentLoaded', (event) => {
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');

        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
        }

        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 5000);
        }
    });

    // Fonctions pour ouvrir/fermer la modale
    function openModal(button, accountId) {
        const modal = document.getElementById('confirm-modal');
        modal.classList.remove('hidden');

        const form = button.closest('form');
        document.getElementById('block-form').action = form.action;
        document.getElementById('account-id').value = accountId;

        document.getElementById('cancel-btn').onclick = function() {
            modal.classList.add('hidden');
        };
    }
</script>

@endsection