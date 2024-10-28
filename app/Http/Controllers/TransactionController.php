<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Compte;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function index()
    {
        $user = auth()->user(); // Récupérer l'utilisateur authentifié
        $comptes = Compte::all();
        $transactions = Transaction::with('compte')->latest()->paginate(10);
        return view('transactions.index', compact('transactions', 'comptes', 'user'));
    }

    // Controller: DepositController.php
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

    // Vérifier si le compte est bloqué
    if ($compte->est_bloque) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte est bloqué. Vous ne pouvez pas effectuer de dépôt.'
            ]);
        }
        return redirect()->back()->with('error', 'Votre compte est bloqué. Vous ne pouvez pas effectuer de dépôt.');
    }

    try {
        // Commencer une transaction
        DB::transaction(function () use ($request, $compte) {
            // Créer une nouvelle transaction
            $transaction = new Transaction();
            $transaction->compte_id = $compte->id;
            $transaction->type = 'depot';
            $transaction->montant = $request->montant;
            $transaction->description = $request->description;
            $transaction->save();

            // Mettre à jour le solde du compte
            $compte->solde += $request->montant;
            $compte->save();

            // Récupérer l'utilisateur associé à ce compte
            $user = User::find($compte->user_id);
            if ($user) {
                // Mettre à jour le solde de l'utilisateur
                $user->solde += $request->montant;
                $user->save();
            }
        });

        // Réponse AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Dépôt effectué avec succès',
                'nouveau_solde' => $compte->solde,
                'nouveau_solde_formate' => number_format($compte->solde, 2, ',', ' ') . ' €'
            ]);
        }

        // Redirection avec message de succès
        return redirect()->route('transactions.index')
            ->with('success', 'Dépôt effectué avec succès')
            ->with('nouveau_solde', $compte->solde);
    } catch (\Exception $e) {
        // Gestion des erreurs
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du dépôt.'
            ], 500);
        }
        return redirect()->back()->with('error', 'Une erreur est survenue lors du dépôt.');
    }
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
        $compte = $transaction->compte;

        // Ajustez le solde du compte dans une transaction atomique
        DB::transaction(function () use ($transaction, $compte) {
            if ($compte) {
                $compte->solde += $transaction->montant; // Récupérez l'argent
                $compte->save(); // Enregistrez les modifications du compte
            }

            // Annuler la transaction
            $transaction->status = Transaction::STATUS_CANCELLED; // Utilisez la constante définie
            $transaction->save();
        });

        return redirect()->back()->with('success', 'Transaction annulée et argent récupéré avec succès.');
    }
}