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

// Affiche la modale de transfert
document.getElementById('showTransferModal').addEventListener('click', function() {
    $('#transferModal').modal('show');
});
