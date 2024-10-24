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
    <style>
        /* Styles de la barre latérale */
        .sidebar {
            height: 100vh;
            width: 300px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1C627B;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575d63;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .btn-sidebar {
            margin: 10px 0;
            width: 100%;
        }

        /* Réduire la hauteur des boutons pour le style compact */
        .btn {
            padding: 10px;
        }
    </style>
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
    <a href="{{ url('/logout') }}">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
    </a>
</div>

<div class="content">
    <div class="container mt-5">
        <h1>Bienvenue, Distributeur</h1>

        <!-- Afficher le solde du distributeur -->
        <div class="alert alert-info">
            <strong>Solde du distributeur :</strong> {{ $distributeur->solde }} FCFA
        </div>

        <!-- Section pour les boutons de crédit, retrait et transactions (masqués dans la sidebar) -->
        <div class="d-flex justify-content-center mb-4">
            <!-- Boutons de contrôle principaux -->
            <button class="btn btn-success mx-2 btn-sidebar" id="btn-crediter">
                <i class="fas fa-plus-circle"></i> Créditer
            </button>
            <button class="btn btn-danger mx-2 btn-sidebar" id="btn-retirer">
                <i class="fas fa-minus-circle"></i> Retirer
            </button>
            <button class="btn btn-info mx-2 btn-sidebar" id="btn-transactions">
                <i class="fas fa-list"></i> Transactions
            </button>
           
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
                    <button type="submit" class="btn btn-primary">Créditer</button>
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
                    <button type="submit" class="btn btn-primary">Retirer</button>
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
                    <!-- Champ pour l'email du distributeur -->
                    <div class="form-group">
                        <label for="email">prenom :</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $distributeur->email }}">
                    </div>
                    <!-- Champ pour le solde (non modifiable) -->
                    <div class="form-group">
                        <label for="solde">Solde actuel :</label>
                        <input type="text" id="solde" class="form-control" value="{{ $distributeur->solde }} FCFA" disabled>
                    </div>
                    <!-- Bouton pour soumettre les modifications -->
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
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
                                        <form action="{{ url('/transaction/annuler/' . $transaction->id) }}" method="POST">
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
</div>

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
