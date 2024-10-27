@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <!-- Carte principale du solde -->
            <div class="card main-card" >
                <div class="card-header header-custom">
                    <button id="settingsButton" class="settings-icon">
                        <i class="fa fa-cog"></i>
                    </button>
                    <h1 style="text-align: center;font-size: 36px;">
                        <span id="solde">{{ number_format($balance) }} F</span>
                        <button id="toggleSolde" class="toggle-icon">
                            <i id="soldeIcon" class="fa fa-eye"></i>
                        </button>
                    </h1>

                    <div class="text-center qr-code-container">
                        <div class="qr-code-box">
                            <img src="data:image/png;base64,{{ base64_encode($qrCodeUrl) }}" alt="QR Code" class="qr-code-img" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Espacement -->
            <div class="spacing"></div>

            <!-- Carte avec icônes d'actions -->
            <div class="d-flex justify-content-center action-icons-container">
                <div class="card action-card">
                    <div class="row">
                        <div class="col">
                            <i class="fa fa-exchange-alt" id="showTransferModal"></i>
                        </div>
                        <div class="col">
                            <i class="fa fa-history"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des transactions -->
            @include('components.transaction-history', ['transactions' => $transactions])

            <!-- Modales d'erreur et de succès -->
            @includeWhen($errors->any(), 'components.error-modal')
            @includeWhen(session('success'), 'components.success-message')
        </div> 
    </div>
</div>

<!-- Modale de transfert -->
@include('components.transfer-modal')
<!-- Inclusion des bibliothèques JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    // JavaScript pour masquer/afficher le solde
    document.getElementById('toggleSolde').addEventListener('click', function() {
        const solde = document.getElementById('solde');
        const soldeIcon = document.getElementById('soldeIcon');

        if (solde.style.display === 'none') {
            solde.style.display = 'block';
            soldeIcon.className = 'fa fa-eye';
        } else {
            solde.style.display = 'none';
            soldeIcon.className = 'fa fa-eye-slash';
        }
    });

    // Afficher la modale d'erreur si elle existe
    @if ($errors->any())
        $(document).ready(function() {
            $('#errorModal').modal('show');
        });
    @endif

    // Afficher la modale de transfert
    document.getElementById('showTransferModal').addEventListener('click', function() {
        $('#transferModal').modal('show');
    });
</script>
@endsection
