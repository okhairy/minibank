<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Transactions - Distributeur</title>
 <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
   
</head>
<body>

            <div class="sidebar">
                <h2 class="text-white text-center">Money-transfert</h2>
                <a href="#" id="dashboard-link"><i class="fas fa-home"></i> Tableau de bord</a>
                <a href="#" id="crediter-link"><i class="fas fa-plus-circle"></i> Créditer un compte</a>
                <a href="#" id="retirer-link"><i class="fas fa-minus-circle"></i> Retirer des fonds</a>
                <a href="#" id="transactions-link"><i class="fas fa-list"></i> Transactions récentes</a>
                <a href="#" id="profil-link"><i class="fas fa-user"></i> Profil du distributeur</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
             </form>
            </div>

    <div class="content">
                    <div class="container mt-5">
                        <h1>Bienvenue, Distributeur</h1>
                        <div class="alert alert-info">
            <div class="d-flex justify-content-between align-items-center">
                <div class="solde-display">
                    <span>Solde du distributeur :</span>
                    <span id="solde-display">{{ $distributeur->solde }} FCFA</span>
                </div>
                <button class="btn btn-secondary btn-sm" id="btn-toggle-solde">
                    <i class="fas fa-eye-slash"></i>
                </button>
            </div>
        </div>
        <div class="qr-container mt-3">
            <div class="qr-code">
                <div id="qrcode"></div>
            </div>
            <div class="qr-controls mt-2">
                <button id="btn-refresh"><i class="fas fa-sync-alt"></i></button>
                <button id="btn-download"><i class="fas fa-download"></i></button>
                <button id="btn-share"><i class="fas fa-share-alt"></i></button>
            </div>
        </div>
               
     </div>
               
            </div>
        </div>
            <div class="d-flex justify-content-center mb-4">
                <!-- Boutons précédents -->
            </div>

            <!-- Formulaire pour créditer le compte d'un client -->
            <div class="card mt-4" id="form-crediter" style="display: none;">
                <div class="card-header">
                    <h3>Créditer un compte client</h3>
                </div>
                <div class="card-body">
                            <form action="{{ url('/crediter') }}" method="POST" onsubmit="return handleFormSubmit(event, 'créditer');">
                @csrf
                <input type="hidden" name="distributeur_id" value="{{ auth()->id() }}">
                <div class="form-group">
                    <label for="numero_compte">Numéro de compte du client :</label>
                    <input type="text" id="numero_compte" name="numero_compte" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="montant">Montant :</label>
                    <input type="number" id="montant" name="montant" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Créditer</button>
            </form>
                </div>
            </div>

        <!-- Formulaire pour retirer des fonds du compte d'un client -->
        <div class="card mt-4" id="form-retirer" style="display: none;">
            <div class="card-header">
                <h3>Retirer des fonds pour un client</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('/retirer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="distributeur_id" value="{{ auth()->id() }}">
                                        <div class="form-group">
                        <label for="numero_compte">Numéro de compte du client :</label>
                        <input type="text" id="numero_compte" name="numero_compte" class="form-control" required>
                        
                    </div>
                    <div id="qr-reader" style="width: 300px; display: none;"></div>
                    <div class="form-group">
                        <label for="montant_retirer">Montant :</label>
                        <input type="number" id="montant_retirer" name="montant" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Retirer</button>
                </form>
            </div>
        </div>

        <!-- Profil du distributeur -->
        <div class="card mt-4" id="profil-distributeur" style="display: none;">
            <div class="card-header">
                <h3>Profil du distributeur</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('/profil/update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="distributeur_id">ID du Distributeur :</label>
                        <input type="text" id="distributeur_id" class="form-control" value="{{ $distributeur->id }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom" class="form-control" value="{{ $distributeur->nom }}" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" value="{{ $distributeur->prenom }}" required>
                    </div>
                    <div class="form-group">
                        <label for="date_naissance">Date de naissance :</label>
                        <input type="date" id="date_naissance" name="date_naissance" class="form-control" value="{{ $distributeur->date_naissance }}" required>
                    </div>
                    <div class="form-group">
                        <label for="solde">Solde actuel :</label>
                        <input type="text" id="solde" class="form-control" value="{{ $distributeur->solde }} FCFA" disabled>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>

        <!-- Liste des transactions récentes -->
        <div class="card mt-4" id="liste-transactions" style="display: none;">
            <div class="card-header">
                <h3>Transactions récentes</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Annuler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->client->numero_compte }}</td>
                            <td>{{ $transaction->montant }}</td>
                            <td>{{ ucfirst($transaction->type) }}</td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if(!$transaction->annule)
                                <form action="{{ route('transaction.annuler', $transaction->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette transaction?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i> Annuler
                                    </button>
                                </form>
                                @else
                                <span class="text-danger"><i class="fas fa-ban"></i> Annulée</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="transaction-modal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">Notification de Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="transaction-message">Votre message ici.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
       

        document.getElementById('btn-toggle-solde').addEventListener('click', function() {
        const soldeDisplay = document.getElementById('solde-display');
        const buttonIcon = this.querySelector('i');
        if (soldeDisplay.textContent.includes('FCFA')) {
            soldeDisplay.textContent = '*********';
            buttonIcon.classList.remove('fa-eye-slash');
            buttonIcon.classList.add('fa-eye');
        } else {
            soldeDisplay.textContent = '{{ $distributeur->solde }} FCFA';
            buttonIcon.classList.remove('fa-eye');
            buttonIcon.classList.add('fa-eye-slash');
        }
     });

   </script>
     <script>
        // Générer le code QR
                var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "{{ $distributeur->id }}",
            width: 256,
            height: 256
        });
        </script>
        <script>
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('transaction_message'))
            // Mettre à jour le contenu du message
            document.getElementById('transaction-message').textContent = "{{ session('transaction_message') }}";
            // Afficher le modal
            $('#transaction-modal').modal('show');
        @endif
    });
</script>

    
<script>
    
    document.getElementById('crediter-link').addEventListener('click', function() {
        document.getElementById('form-crediter').style.display = 'block';
        document.getElementById('form-retirer').style.display = 'none';
        document.getElementById('liste-transactions').style.display = 'none';
        document.getElementById('profil-distributeur').style.display = 'none';
    });

    document.getElementById('retirer-link').addEventListener('click', function() {
        document.getElementById('form-retirer').style.display = 'block';
        document.getElementById('form-crediter').style.display = 'none';
        document.getElementById('liste-transactions').style.display = 'none';
        document.getElementById('profil-distributeur').style.display = 'none';
    });

    document.getElementById('transactions-link').addEventListener('click', function() {
        document.getElementById('liste-transactions').style.display = 'block';
        document.getElementById('form-retirer').style.display = 'none';
        document.getElementById('form-crediter').style.display = 'none';
        document.getElementById('profil-distributeur').style.display = 'none';
    });

    document.getElementById('profil-link').addEventListener('click', function() {
        document.getElementById('profil-distributeur').style.display = 'block';
        document.getElementById('form-retirer').style.display = 'none';
        document.getElementById('form-crediter').style.display = 'none';
        document.getElementById('liste-transactions').style.display = 'none';
    });

    document.getElementById('btn-crediter').addEventListener('click', function() {
        document.getElementById('form-crediter').style.display = 'block';
        document.getElementById('form-retirer').style.display = 'none';
        document.getElementById('liste-transactions').style.display = 'none';
        document.getElementById('profil-distributeur').style.display = 'none';
    });

    document.getElementById('btn-retirer').addEventListener('click', function() {
        document.getElementById('form-retirer').style.display = 'block';
        document.getElementById('form-crediter').style.display = 'none';
        document.getElementById('liste-transactions').style.display = 'none';
        document.getElementById('profil-distributeur').style.display = 'none';
    });

    document.getElementById('btn-transactions').addEventListener('click', function() {
        document.getElementById('liste-transactions').style.display = 'block';
        document.getElementById('form-retirer').style.display = 'none';
        document.getElementById('form-crediter').style.display = 'none';
        document.getElementById('profil-distributeur').style.display = 'none';
    });
    
</script>

</body>
</html>