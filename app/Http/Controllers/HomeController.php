<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user(); // Supposons que l'utilisateur est déjà authentifié
    
            // Récupérer les transactions de l'utilisateur
            $transactions = Transactions::with('user')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
    
            // Calculer les totaux
            $totalDepot = Transactions::where('user_id', $user->id)
                ->where('type', 'Dépôt')
                ->sum('montant');
    
            $totalRetrait = Transactions::where('user_id', $user->id)
                ->where('type', 'Envoi')
                ->sum('montant');
    
            // Récupérer le solde et le numéro de compte
            $balance = $user->balance;
            $accountNumber = $user->account_number;
            $qrCodeUrl = QrCode::format('png')->size(300)->generate($accountNumber);
    
            // Retourner la vue avec les données
            return view('home', compact('transactions', 'totalDepot', 'totalRetrait', 'balance', 'qrCodeUrl'));
    
        } catch (Exception $e) {
            // Gérer les exceptions et retourner une erreur
            return back()->withErrors(['error' => 'Erreur lors du chargement de la page.']);
        }
    }

    public function transfer(Request $request)
    {
        // Enregistrer toutes les entrées de la requête
        Log::info('Données de la requête:', $request->all());

        // Validation
        $request->validate([
            'account_number' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'password' => 'required|string',
        ]);

        // Vérifier si le mot de passe est correct
        $user = auth()->user();
        if (!password_verify($request->password, $user->password)) {
            Log::warning('Mot de passe incorrect pour l\'utilisateur:', [$user->email]);
            return back()->withErrors(['password' => 'Le mot de passe est incorrect.']);
        }

        // Vérifier que l'utilisateur a suffisamment de fonds
        $amount = $request->amount;
        if ($user->balance < $amount) {
            Log::warning('Solde insuffisant pour l\'utilisateur:', [$user->email]);
            return back()->withErrors(['amount' => 'Solde insuffisant.']);
        }

        // Trouver le destinataire par son numéro de compte
        $recipient = User::where('account_number', $request->account_number)->first();
        if (!$recipient) {
            Log::warning('Compte destinataire non trouvé:', [$request->account_number]);
            return back()->withErrors(['account_number' => 'Compte destinataire non trouvé.']);
        }

        // Effectuer le transfert
        $user->balance -= $amount; // Débiter le compte de l'utilisateur
        $recipient->balance += $amount; // Créditer le compte destinataire

        // Sauvegarder les changements
        $user->save();
        $recipient->save();

        // Enregistrer la transaction
        Transactions::create([
            'user_id' => $user->id,
            'type' => 'Envoi', // Type de transaction
            'montant' => $amount,
            'fee' => 0.00, // Vous pouvez ajouter des frais si nécessaire
            'destinataire' => $recipient->account_number,
            'sender_name' => $user->name, // Assurez-vous que le champ 'name' existe dans votre modèle User
        ]);

        // Log la réussite du transfert
        Log::info('Transfert effectué:', [
            'from' => $user->email,
            'to' => $recipient->email,
            'amount' => $amount,
        ]);

        // Rediriger avec un message de succès
        return redirect()->route('home')->with('message', 'Transfert effectué avec succès.');
    }
}
