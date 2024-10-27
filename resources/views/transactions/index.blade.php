@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Affichage des messages de succès -->
    @if (session('success'))
        <div id="success-message" class="bg-green-200 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Affichage du solde -->
    

    <!-- Historique des Transactions -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4">Historique des transactions</h2>
        <div class="mb-4">
        <a href="{{ route('deposit.page') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            <i class="fas fa-plus mr-2"></i> Effectuer un dépôt
        </a>
        </div>
        <div class="overflow-x-auto">
            <table class="custom-table">
                <thead>
                    <tr>
                        @foreach(['Date', 'Nom & Prénom', 'N° Compte', 'Type', 'Montant', 'Action'] as $header)
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            {{ $transaction->compte->nom }} {{ $transaction->compte->prenom }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            {{ $transaction->compte->numero_compte }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $transaction->type === 'depot' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            {{ number_format($transaction->montant, 2) }} Fcfa
                        </td>
                        <td class="px-6 py-4 border-b border-gray-200">
                            @if ($transaction->status === \App\Models\Transaction::STATUS_CANCELLED)
                                <span class="text-red-500 font-bold">Annulée</span>
                            @else
                                <form action="{{ route('transactions.cancel', $transaction->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">
                                        Annuler
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $transactions->links() }} <!-- Liens de pagination -->
            </div>
        </div>
    </div>

</div>

<style>
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
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            // Masquer le message après 5 secondes (5000 ms)
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 1000);
        }
    });
</script>
@endsection

@endsection