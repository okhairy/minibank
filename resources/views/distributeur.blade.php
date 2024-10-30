<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Transactions - Distributeur</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    
</head>
<body>

<div class="sidebar">
    <h2 class="text-white text-center">Menu</h2>
    <a href="#" id="dashboard-link">
        <i class="fas fa-home"></i> Tableau de bord
    </a>
    <a href="#" id="crediter-link">
        <i class="fas fa-plus-circle"></i> Créditer un compte
    </a>
    <a href="#" id="retirer-link">
        <i class="fas fa-minus-circle"></i> Retirer des fonds
    </a>
    <a href="#" id="transactions-link">
        <i class="fas fa-list"></i> Transactions récentes
    </a>
    <a href="#" id="profil-link">
        <i class="fas fa-user"></i> Profil du distributeur
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i>
            Se déconnecter
        </a>

</div>

<div class="content">
    <div class="container mt-5">
        <h1>Bienvenue, Distributeur</h1>

        <!-- Afficher le solde du distributeur -->
        <div class="alert alert-info">
         <div class="d-flex justify-content-between align-items-center">
         <div class="solde-display d-flex align-items-center">
            <span>Solde du distributeur :</span>
            <span id="solde-display" class="ml-2">{{ $distributeur->solde }} FCFA</span>
            <button class="btn btn-secondary btn-sm ml-2" id="btn-toggle-solde">
                <i class="fas fa-eye-slash"></i>
            </button>
        </div>
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
       

        <!-- Formulaire pour créditer le compte d'un client (caché par défaut) -->
        <div class="card mt-4" id="form-crediter" style="display: none;">
            <div class="card-header">
                <h3>Créditer un compte client</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('/crediter') }}" method="POST">
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
                    <button type="submit" class="btn btn-primary" onclick="$('#modal-crediter').modal('show');">Créditer</button>
                </form>
            </div>
        </div>

        <!-- Formulaire pour retirer des fonds du compte d'un client (caché par défaut) -->
        <div class="card mt-4" id="form-retirer" style="display: none;">
            <div class="card-header">
                <h3>Retirer des fonds pour un client</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('/retirer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="distributeur_id" value="{{ auth()->id() }}">
                    <div class="form-group">
                        <label for="numero_compte_retirer">Numéro de compte du client :</label>
                        <input type="text" id="numero_compte_retirer" name="numero_compte" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="montant_retirer">Montant :</label>
                        <input type="number" id="montant_retirer" name="montant" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="$('#modal-retirer').modal('show');">Retirer</button>
                </form>
            </div>
        </div>

        <!-- Profil du distributeur (caché par défaut) -->
        <div class="card mt-4" id="profil-distributeur" style="display: none;">
            <div class="card-header">
                <h3>Profil du distributeur</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('/profil/update') }}" method="POST">
                    @csrf
                    <!-- Champ pour l'identifiant du distributeur (non modifiable) -->
                    <div class="form-group">
                        <label for="distributeur_id">ID du Distributeur :</label>
                        <input type="text" id="distributeur_id" class="form-control" value="{{ $distributeur->id }}" disabled>
                    </div>
                    <!-- Champ pour le nom du distributeur -->
                    <div class="form-group">
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom" class="form-control" value="{{ $distributeur->nom }}">
                    </div>
                    <div class="form-group">
    <label for="date_naissance">Date de naissance:</label>
    <input type="date" id="date_naissance" name="date_naissance" class="form-control" value="{{ $distributeur->date_naissance }}" required>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dateNaissanceInput = document.getElementById("date_naissance");
        const today = new Date();
        const minDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

        // Définir la date maximale pour le champ date (aujourd'hui)
        dateNaissanceInput.setAttribute("max", today.toISOString().split("T")[0]);

        // Vérification lors de la soumission du formulaire
        dateNaissanceInput.addEventListener("change", function() {
            const selectedDate = new Date(this.value);
            if (selectedDate > minDate) {
                alert("Vous devez avoir au moins 18 ans.");
                this.value = ""; // Réinitialiser le champ
            }
        });
    });
</script>
                    <!-- Champ pour l'email du distributeur -->
                    <div class="form-group">
                        <label for="prenom">prenom :</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" value="{{ $distributeur->prenom }}">
                    </div>
                    <div class="form-group">
                        <label for="email">email :</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $distributeur->email }}">
                    </div>
                    <!-- Champ pour le solde (non modifiable) -->
                    <div class="form-group">
                        <label for="solde">Solde actuel :</label>
                        <input type="text" id="solde" class="form-control" value="{{ $distributeur->solde }} FCFA" disabled>
                    </div>
                    <!-- Bouton pour soumettre les modifications -->
                    <button type="submit" class="btn btn-primary" onclick="$('#modal-mettre-a-jour').modal('show');">Mettre à jour</button>
                </form>
            </div>
        </div>

        <!-- Liste des transactions récentes (cachée par défaut) -->
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
                                <form action="{{ url('/transaction/annuler/' . $transaction->id) }}" method="POST" onsubmit="$('#modal-annuler').modal('show');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Annuler</button>
                                    </form>
                                    @else
                                        <span class="text-danger">Annulée</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    
    <!-- Modal pour Créditer un compte -->
<div class="modal fade" id="modal-crediter" tabindex="-1" role="dialog" aria-labelledby="crediterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crediterModalLabel">Créditer un compte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Le compte a été crédité avec succès.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour Retirer des fonds -->
<div class="modal fade" id="modal-retirer" tabindex="-1" role="dialog" aria-labelledby="retirerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="retirerModalLabel">Retirer des fonds</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Les fonds ont été retirés avec succès.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour Annuler une transaction -->
<div class="modal fade" id="modal-annuler" tabindex="-1" role="dialog" aria-labelledby="annulerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="annulerModalLabel">Annuler une transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>La transaction a été annulée avec succès.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour Mettre à jour le profil -->
<div class="modal fade" id="modal-mettre-a-jour" tabindex="-1" role="dialog" aria-labelledby="mettreAJourModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mettreAJourModalLabel">Mettre à jour le profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Le profil a été mis à jour avec succès.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    @if (session('crediter_message'))
        $('#modal-crediter').modal('show');
    @endif
    @if (session('retirer_message'))
        $('#modal-retirer').modal('show');
    @endif
    @if (session('annuler_message'))
        $('#modal-annuler').modal('show');
    @endif
    @if (session('mettre_a_jour_message'))
        $('#modal-mettre-a-jour').modal('show');
    @endif
});
</script>

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
    // Gérer les clics sur les liens de la barre latérale
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

    // Gérer les clics sur les boutons de contrôle
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

    document.getElementById('btn-profil').addEventListener('click', function() {
        document.getElementById('profil-distributeur').style.display = 'block';
        document.getElementById('form-retirer').style.display = 'none';
        document.getElementById('form-crediter').style.display = 'none';
        document.getElementById('liste-transactions').style.display = 'none';
    });
</script>

</body>
</html>