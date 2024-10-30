<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Distributeur;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DistributeurController extends Controller
{
    public function getTransactions()
{
    $distributeur = Distributeur::find(auth()->id());
    $transactions = Transaction::where('distributeur_id', $distributeur->id)->latest()->get();

    return response()->json($transactions);
}
    public function crediterCompte(Request $request)
    {
        // Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'numero_compte' => 'required|string',
            'montant' => 'required|numeric|min:0',
            'distributeur_id' => 'required|integer|exists:distributeurs,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $distributeur = Distributeur::find($request->distributeur_id);
        $client = Client::where('numero_compte', $request->numero_compte)->first();

        if ($client && !$client->bloque) {
            DB::transaction(function () use ($client, $distributeur, $request) {
                $montant = $request->montant;

                // Ajouter le montant au solde du distributeur
                $distributeur->solde += $montant;
                $distributeur->save();

                // Enregistrer la transaction
                Transaction::create([
                    'distributeur_id' => $distributeur->id,
                    'client_id' => $client->id,
                    'montant' => $montant,
                    'type' => 'depot',
                ]);
            });

            return response()->json([
                'status' => 'success',
                'modal' => 'crediter',
                'message' => 'Compte crédité avec succès !',
            ]);
        }
    
        return response()->json([
            'status' => 'error',
            'message' => 'Compte non trouvé ou bloqué !'
        ], 404);
    
    }
  
    public function retirerCompte(Request $request)
    {
        // Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'numero_compte' => 'required|string',
            'montant' => 'required|numeric|min:0',
            'distributeur_id' => 'required|integer|exists:distributeurs,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $distributeur = Distributeur::find($request->distributeur_id);
        $client = Client::where('numero_compte', $request->numero_compte)->first();

        if ($client && !$client->bloque && $client->solde >= $request->montant) {
            DB::transaction(function () use ($client, $distributeur, $request) {
                $montant = $request->montant;

                // Retirer le montant du solde du client
                $client->solde -= $montant;
                $client->save();

                // Diminuer le solde du distributeur
                $distributeur->solde -= $montant;
                $distributeur->save();

                // Enregistrer la transaction
                Transaction::create([
                    'distributeur_id' => $distributeur->id,
                    'client_id' => $client->id,
                    'montant' => $montant,
                    'type' => 'retrait',
                ]);
            });

            return response()->json([
                'message' => 'Retrait effectué avec succès !',
                'nouveau_solde_distributeur' => $distributeur->solde,
                'nouveau_solde_client' => $client->solde,
            ]);
        }

        return response()->json(['message' => 'Compte non trouvé, bloqué ou solde insuffisant !'], 404);
    }

    public function annulerTransaction($id)
    {
        $transaction = Transaction::find($id);

        if ($transaction && !$transaction->annule) {
            DB::transaction(function () use ($transaction) {
                // Annuler la transaction
                $transaction->annule = true;
                $transaction->save();

                $client = $transaction->client;
                $distributeur = $transaction->distributeur;
                $montant = $transaction->montant;

                // Reverser ou débiter les montants en fonction du type de transaction
                if ($transaction->type == 'depot') {
                    $client->solde -= $montant;
                } elseif ($transaction->type == 'retrait') {
                    $client->solde += $montant;
                }

                $client->save();
                $distributeur->save();
            });

            return response()->json(['message' => 'Transaction annulée avec succès !']);
        }

        return response()->json(['message' => 'Transaction introuvable ou déjà annulée !'], 404);
    }
    public function update(Request $request)
    {
        //
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
        ]);
    
        // Récupérer le distributeur
        $distributeur = Distributeur::find(auth()->id());
    
        // Mettre à jour les informations
        $distributeur->nom = $request->nom;
        $distributeur->prenom = $request->prenom;
        $distributeur->date_naissance = $request->date_naissance;
        $distributeur->save();
    
        // Redirection avec message de succès
        return redirect()->back()->with('success', 'Informations mises à jour avec succès.');
    }

    public function index()
    {
        // Récupérer le distributeur actuel
        $distributeur = Distributeur::find(auth()->id());
        // Récupérer les transactions récentes
        $transactions = Transaction::where('distributeur_id', $distributeur->id)->latest()->get();

        // Retourner la vue 'distributeur' avec les données nécessaires
        return view('distributeur', compact('transactions', 'distributeur'));
    }
    public function store(Request $request)
{
    // Validation
    $request->validate([
        'date_naissance' => 'required|date|before:' . now()->subYears(18)->toDateString(),
    ]);

    // Logique pour enregistrer le distributeur ou d'autres actions
}


}