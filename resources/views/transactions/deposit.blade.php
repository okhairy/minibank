@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Effectuer un dépôt</h2>
        
        @include('partials.flash-messages')

        <form action="{{ route('transactions.deposit') }}" method="POST" id="depositForm">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="numero_compte">
                    Numéro de compte
                </label>
                <input type="text" name="numero_compte" id="numero_compte" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                <p id="numero_compte_error" class="text-red-500 text-xs italic hidden"></p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="montant">
                    Montant
                </label>
                <input type="number" step="0.01" name="montant" id="montant" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                <p id="montant_error" class="text-red-500 text-xs italic hidden"></p>
            </div>

            <div class="flex justify-between items-center mt-4">
                <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Effectuer le dépôt
                </button>

                <a href="{{ route('transactions.index') }}" 
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    &larr; Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const numeroCompteInput = document.getElementById('numero_compte');
        const montantInput = document.getElementById('montant');
        const numeroCompteError = document.getElementById('numero_compte_error');
        const montantError = document.getElementById('montant_error');

        numeroCompteInput.addEventListener('input', function() {
            if (numeroCompteInput.value.trim() === '' || !/^\d+$/.test(numeroCompteInput.value.trim())) {
                numeroCompteError.textContent = 'Numéro de compte invalide.';
                numeroCompteError.classList.remove('hidden');
            } else {
                numeroCompteError.classList.add('hidden');
            }
        });

        montantInput.addEventListener('input', function() {
            const montantValue = parseFloat(montantInput.value);
            if (isNaN(montantValue) || montantValue < 500) {
                montantError.textContent = 'Le montant doit être supérieur ou égal à 500.';
                montantError.classList.remove('hidden');
            } else {
                montantError.classList.add('hidden');
            }
        });
    });
</script>
@endsection
@endsection