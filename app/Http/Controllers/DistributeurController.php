<?
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Distributeur;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DistributeurController extends Controller
{
    public function crediterCompte(Request $request)
    {
        // Validation des données
        $request->validate([
            'numero_compte' => 'required|string',
            'montant' => 'required|numeric',
            'distributeur_id' => 'required|integer',
        ]);
    
        // Logique pour créditer le compte du client
        // Trouver le client et mettre à jour son solde
        $client = Client::where('numero_compte', $request->numero_compte)->first();
        if (!$client) {
            return response()->json(['error' => 'Client introuvable.'], 404);
        }
    
        // Ajouter le montant au solde du client
        $client->solde += $request->montant;
        $client->save();
    
        // Calculer la commission du distributeur
        $commission = $request->montant * 0.01;
    
        // Mettre à jour le solde du distributeur
        $distributeur = Distributeur::find($request->distributeur_id);
        $distributeur->solde += $commission;
        $distributeur->save();
    
        //return response()->json(['vous avez reçu un depot de :' => $request->montant]);
        return redirect()->back()->with('transaction_message', 'Le compte a été crédité avec succès.');
    }

    public function retirerCompte(Request $request)
    {
        // Validation des données
        $request->validate([
            'numero_compte' => 'required|string',
            'montant' => 'required|numeric',
            'distributeur_id' => 'required|integer',
        ]);
    
        // Logique pour retirer des fonds du compte du client
        $client = Client::where('numero_compte', $request->numero_compte)->first();
        if (!$client) {
            return response()->json(['error' => 'Client introuvable.'], 404);
        }
    
        // Vérifiez si le client a suffisamment de fonds
        if ($client->solde < $request->montant) {
            return response()->json(['error' => 'Fonds insuffisants.'], 400);
        }
    
        // Retirer le montant du solde du client
        $client->solde -= $request->montant;
        $client->save();
    
        // Calculer la commission du distributeur
        $commission = $request->montant * 0.01;
    
        // Mettre à jour le solde du distributeur
        $distributeur = Distributeur::find($request->distributeur_id);
        $distributeur->solde += $commission;
        $distributeur->save();
    
        //return response()->json(['vous avez retirer:' => $request->montant]);
        return redirect()->back()->with('transaction_message', 'Le retrait a été effectué avec succès.');
    }
    public function annulerTransaction($id)
    {
        // Trouver la transaction
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['error' => 'Transaction introuvable.'], 404);
        }
    
        // Récupérer le distributeur
        $distributeur = Distributeur::find($transaction->distributeur_id);
    
        // Récupérer le montant et calculer la commission
        $montant = $transaction->montant;
        $commission = $montant * 0.01;
    
        // Mettre à jour le solde du distributeur
        if ($distributeur->solde >= $commission) {
            $distributeur->solde -= $commission;
            $distributeur->save();
        } else {
            return response()->json(['error' => 'Solde insuffisant pour annuler la transaction.'], 400);
        }
    
        // Logique pour annuler la transaction (par exemple, marquer comme annulée)
        $transaction->annule = true; 
        $transaction->save();
    
        //return response()->json(['message' => 'Transaction annulée avec succès et commission débitée.']);
        return redirect()->back()->with('transaction_message', 'La transaction a été annulée.');
    }
      //fonction pour mettre a jour le profil du distruiteur
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
    
        // Vérifiez si le distributeur est trouvé
        if (!$distributeur) {
            return redirect()->back()->with('error', 'Distributeur introuvable.');
        }
    
        // Récupérer les transactions récentes à partir de la relation
        $transactions = $distributeur->transactions()->latest()->get();
    
        // Retourner la vue 'distributeur' avec les données nécessaires
        return view('distributeur', compact('transactions', 'distributeur'));
    }

}
