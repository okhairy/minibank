@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Carte principale -->
            <div class="card" style="border-radius: 15px; position: fixed; top: 40px; left: 50%; transform: translateX(-50%); z-index: 1000; width: 80%; max-width: 850px;">
                <div class="card-header text-center" style="background-color: #1C627B; color: white; padding: 50px; border-radius: 15px 15px 0 0; position: relative;">
                    <!-- Bouton Paramètres -->
                    <button id="settingsButton" style="background: none; border: none; color: white; position: absolute; top: 15px; left: 15px;">
                        <i class="fa fa-cog" style="font-size: 24px;"></i>
                    </button>

                    <!-- Menu déroulant des options de paramètres -->
                    <div id="settingsMenu" class="dropdown-menu" style="display: none; position: absolute; top: 45px; left: 15px; z-index: 1100; background-color: #1C627B;">
                        <a class="dropdown-item" href="/modifier">Modifier</a>
                        <a class="dropdown-item" href="/deconnexion">Déconnexion</a>
                    </div>

                    <h2>
                        <span id="solde" style="font-size: 26px">{{ number_format($balance) }} F</span>
                        <button id="toggleSolde" style="background: none; border: none; color: white;">
                            <i id="soldeIcon" class="fa fa-eye"></i>
                        </button>
                    </h2>
                    <div class="text-center" style="padding: 20px;">
                        <div style="background-color: #40AEC9; border-radius: 15px; padding: 30px; display: inline-block; width: 265px;">
                            <img src="data:image/png;base64,{{ base64_encode($qrCodeUrl) }}" alt="QR Code" style="border-radius: 10px; width: 200px; height: 100px; border: 10px solid #FFFFFF;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Espace pour le padding en haut du body afin que le contenu ne soit pas masqué -->
<div style="margin-top: 350px;">
    <!-- Carte avec icônes d'actions fixée -->
    <div class="d-flex justify-content-center" style="position: fixed; top: 370px; left: 50%; transform: translateX(-50%); z-index: 1000;">
        <div class="card text-center border rounded" style="background-color: #f2f2f2; padding: 15px; border-color: #40AEC9; width: 600px; height: auto; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
            <div class="row">
                <div class="col">
                    <i class="fa fa-exchange-alt" id="showTransferModal" style="font-size: 24px; cursor: pointer;"></i>
                </div>
                <div class="col">
                    <i class="fa fa-history" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des transactions -->
    <div class="card" style="padding: 10px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); margin-top: 12px; max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($transactions as $transaction)
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="border: none;">
                        <div class="d-flex flex-column">
                            <span>
                                <strong style="color: blue;">{{ $transaction->type }}</strong>
                                @if($transaction->status !== 'Annulé' && $transaction->type === 'Transfert')
                                    <!-- Afficher transfert de l'utilisateur -->
                                @elseif($transaction->status !== 'Annulé' && $transaction->type === 'Réception')
                                    <!-- Afficher réception de l'utilisateur -->
                                @endif
                            </span>
                            <small class="text-muted">{{ $transaction->created_at->format('d M Y à H:i') }}</small>
                        </div>
                        <span style="color: {{ in_array($transaction->type, ['Dépôt', 'Réception', 'Annulé']) ? 'blue' : 'red' }};">
                            {{ in_array($transaction->type, ['Dépôt', 'Réception', 'Annulé']) ? number_format($transaction->montant) . ' F' : '-' . number_format($transaction->montant) . ' F' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Modale d'erreur avec Bootstrap -->
    @if ($errors->any())
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="errorModalLabel"><i class="fa fa-exclamation-triangle"></i> Erreur de Validation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Message de succès après une transaction réussie -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
</div>

<!-- Modal de transfert -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 d-flex flex-column align-items-center justify-content-center" 
                        style="background-color: #1C627B; color: white; border-top-right-radius: 20px; border-bottom-right-radius: 20px; border-right: 4px solid #40AEC9;">
                        <img src="{{ asset('minibank.png') }}" alt="Logo" class="img-fluid mb-3" style="max-width: 150px;">
                        <h3 class="text-center">Transfert d’argent</h3>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('home.transfer') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="account_number">N° compte destinataire</label>
                                <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Abcx125" required>
                            </div>
                            <div class="form-group">
                                <label for="amount">Montant</label>
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="500.000" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Valider</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclusion des bibliothèques JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $('#errorModal').modal('show');
        
        $('#settingsButton').on('click', function () {
            $('#settingsMenu').toggle();
        });

        $('#toggleSolde').on('click', function () {
            $('#solde').toggleClass('hidden');
            $('#soldeIcon').toggleClass('fa-eye fa-eye-slash');
        });

        $('#showTransferModal').on('click', function () {
            $('#transferModal').modal('show');
        });
    });
</script>
@endsection
