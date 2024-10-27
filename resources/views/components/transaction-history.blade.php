<div class="card transaction-history my-custom-margin">
    <div class="card-body">
        <ul class="list-group list-group-flush">
            @foreach($transactions as $transaction)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="transaction-type">{{ $transaction->type }}</strong>
                        <div>
                            <small class="text-muted">{{ $transaction->created_at->format('d M Y à H:i') }}</small>
                        </div>
                    </div>
                    <span class="transaction-amount {{ in_array($transaction->type, ['Dépôt', 'Réception', 'Annulé']) ? 'positive' : 'negative' }}">
                        {{ in_array($transaction->type, ['Dépôt', 'Réception', 'Annulé']) ? number_format($transaction->montant) . ' F' : '-' . number_format($transaction->montant) . ' F' }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
