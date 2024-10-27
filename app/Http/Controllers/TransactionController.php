<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {

        
        $comptes = Compte::all();
        $transactions = Transaction::with('compte')->latest()->paginate(10);
        return view('transactions.index', compact('transactions', 'comptes'));
        

        
    }

    public function deposit(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'numero_compte' => 'required|exists:comptes,numero_compte',
            'montant' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Récupérer le compte correspondant au numéro de compte
        $compte = Compte::where('numero_compte', $request->numero_compte)->first();

        // Vérifiez si le compte est bloqué
        if ($compte->est_bloque) { // Remplacez est_bloque par le champ approprié
            return redirect()->back()->with('error', 'Votre compte est bloqué. Vous ne pouvez pas effectuer de dépôt.');
        }

        // Effectuer le dépôt
        $transaction = new Transaction();
        $transaction->compte_id = $compte->id;
        $transaction->type = 'depot';
        $transaction->montant = $request->montant;
        $transaction->description = $request->description;
        $transaction->save();

        // Optionnel : Mettez à jour le solde du compte ici si nécessaire

        return redirect()->route('transactions.index')->with('success', 'Dépôt effectué avec succès.');
    }


    public function search(Request $request)
{
    $query = Transaction::query()
        ->with('compte')
        ->join('comptes', 'transactions.compte_id', '=', 'comptes.id')
        ->where('comptes.status', '=', 'completed');

    if ($request->numero_compte) {
        $query->where('comptes.numero_compte', $request->numero_compte);
    }

    if ($request->date_debut) {
        $query->whereDate('transactions.created_at', '>=', $request->date_debut);
    }

    if ($request->date_fin) {
        $query->whereDate('transactions.created_at', '<=', $request->date_fin);
    }

    $transactions = $query->select('transactions.*')->latest()->paginate(10);
    
    return view('transactions.index', compact('transactions'));
}

public function cancel($id)
{
    // Récupérez la transaction par ID
    $transaction = Transaction::find($id);

    // Vérifiez si la transaction existe
    if (!$transaction) {
        return redirect()->back()->with('error', 'Transaction non trouvée.');
    }

    // Vérifiez si la transaction est déjà annulée
    if ($transaction->status === Transaction::STATUS_CANCELLED) {
        return redirect()->back()->with('error', 'La transaction est déjà annulée.');
    }

    // Récupérer le compte associé à la transaction
    $compte = $transaction->compte; // Assurez-vous que la relation 'compte' existe

    // Ajustez le solde du compte
    if ($compte) {
        $compte->solde += $transaction->montant; // Récupérez l'argent
        $compte->save(); // Enregistrez les modifications du compte
    }

    // Annuler la transaction
    $transaction->status = Transaction::STATUS_CANCELLED; // Utilisez la constante définie
    $transaction->save();

    return redirect()->back()->with('success', 'Transaction annulée et argent récupéré avec succès.');
}



}