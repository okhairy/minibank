@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('header', 'Tableau de bord')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Statistiques -->
    <div class="bg-white rounded-lg shadow-lg transition-transform transform hover:scale-105 p-4">
        <h3 class="text-lg font-semibold mb-2">Total Clients</h3>
        <p class="text-3xl font-bold">{{ $totalClients }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-lg transition-transform transform hover:scale-105 p-4">
        <h3 class="text-lg font-semibold mb-2">Total Distributeurs</h3>
        <p class="text-3xl font-bold">{{ $totalDistributeurs }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-lg transition-transform transform hover:scale-105 p-4">
        <h3 class="text-lg font-semibold mb-2">Transactions</h3>
        <p class="text-3xl font-bold">{{ $transactionsJour }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-lg transition-transform transform hover:scale-105 p-4 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold mb-2">Mon Solde</h3>
            <p id="solde" class="text-3xl font-bold" style="color: #40AEC9;">{{ number_format($soldeAgent, 0, ' ', ' ') }} FCFA</p>
        </div>
        <button id="toggleSolde" type="button" class="text-gray-600 hover:text-gray-800">
            <i id="eyeIcon" class="fas fa-eye"></i>
        </button>
    </div>
</div>
<br>

<!-- Comptes Clients et Distributeurs -->
<div class="bg-white rounded-lg shadow-lg w-full">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold">Nouveaux Comptes</h3>
        <a href="{{ route('comptes.index') }}" style="background-color: #40AEC9; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; text-decoration: none;" class="hover:bg-gray-700 flex items-center">
            <i class="fas fa-users mr-2"></i> Voir tous les comptes
        </a>
    </div>
    <div class="p-4">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b text-left">Numéro de Compte</th>
                    <th class="px-6 py-3 border-b text-left">Nom</th>
                    <th class="px-6 py-3 border-b text-left">Prénom</th>
                    <th class="px-6 py-3 border-b text-left">Téléphone</th>
                    <th class="px-6 py-3 border-b text-left">Adresse</th>
                    <th class="px-6 py-3 border-b text-left">Rôle</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comptes as $compte)
                <tr>
                    <td class="px-6 py-4">{{ $compte->numero_compte }}</td>
                    <td class="px-6 py-4">{{ $compte->nom }}</td>
                    <td class="px-6 py-4">{{ $compte->prenom }}</td>
                    <td class="px-6 py-4">{{ $compte->numero_telephone }}</td>
                    <td class="px-6 py-4">{{ $compte->adresse }}</td>
                    <td class="px-6 py-4">{{ $compte->role }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

<style>
    .btn-custom {
        background-color: #40AEC9; /* Couleur personnalisée */
        color: white; /* Couleur du texte */
        padding: 0.5rem 1rem; /* Padding */
        border-radius: 0.25rem; /* Arrondi */
        transition: background-color 0.3s ease; /* Transition pour l'effet hover */
    }

    .btn-custom:hover {
        background-color: #1C627B; /* Couleur au survol */
    }
</style>

<!-- Liens vers Bootstrap CSS et JS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('toggleSolde');
        const soldeElement = document.getElementById('solde');
        const eyeIcon = document.getElementById('eyeIcon');

        let isVisible = false;

        toggleButton.addEventListener('click', function () {
            isVisible = !isVisible;
            const montant = '{{ number_format($soldeAgent, 0, " ", " ") }} FCFA';
            const asterisques = '*'.repeat(montant.length - 1);

            if (isVisible) {
                soldeElement.textContent = montant;
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                soldeElement.textContent = asterisques;
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    });
</script>
@endsection