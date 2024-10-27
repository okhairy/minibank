<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body transfer-modal-body">
                <div class="row">
                    <div class="col-md-6 transfer-info">
                        <img src="{{ asset('minibank.png') }}" alt="Logo" class="img-fluid mb-3">
                        <h3>Transfert d’argent</h3>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('home.transfer') }}" method="POST" id="transferForm">
                            @csrf

                            <!-- Messages de succès et d'erreurs -->
                            @if(session('success'))
                                <div class="alert alert-success text-center">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger text-center">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Champ N° de compte destinataire -->
                            <div class="form-group">
                                <label for="account_number">N° compte destinataire</label>
                                <input type="text" name="account_number" id="account_number" class="form-control" required>
                            </div>

                            <!-- Champ Montant -->
                            <div class="form-group">
                                <label for="amount">Montant</label>
                                <input type="number" name="amount" id="amount" class="form-control" min="1" required>
                            </div>

                            <!-- Champ Mot de passe -->
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <!-- Boutons de validation et annulation -->
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary" id="submitButton">Valider</button>
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