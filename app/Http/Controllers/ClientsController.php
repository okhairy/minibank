<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Clients; // Assurez-vous que vous utilisez le bon modèle
use App\Models\Transactionn; // Importez le modèle Transactionn
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class ClientsController extends Controller
{
    public function dashboard()
    {
        $client = Auth::user(); // Supposons que le client est connecté

        // Récupérer les transactions récentes
        $transactions = $client->transactionns()->latest()->take(5)->get(); // Remplacez transactions par transactionns

        // Récupérer le solde
        $solde = $client->balance; // Assurez-vous que la propriété 'balance' existe dans votre modèle Client

        // Vérifiez que le numéro de compte n'est pas null
        $accountNumber = $client->numero_compte;

        $qrCodeUrl = null; // Initialiser la variable QR code
        if ($accountNumber) {
            $qrCode = QrCode::size(100)->generate($accountNumber);
            $qrCodeUrl = base64_encode($qrCode);
        }

        return view('clients.dashboard', compact('client', 'transactions', 'qrCodeUrl', 'solde'));
    }

    public function index()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter.']);
            }

            // Récupérer les transactions et les totaux
            $transactions = Transactionn::with('user')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $totalDepot = Transactionn::where('user_id', $user->id)
                ->where('type', 'Dépôt')
                ->sum('montant');

            $totalRetrait = Transactionn::where('user_id', $user->id)
                ->where('type', 'Envoi')
                ->sum('montant');

            // Récupérer le solde
            $balance = $user->balance; // Assurez-vous que la propriété 'balance' existe dans votre modèle User
            $accountNumber = $user->account_number;
            $qrCodeUrl = QrCode::format('png')->size(300)->generate($accountNumber);

            return view('clients.dashboard', compact('transactions', 'totalDepot', 'totalRetrait', 'balance', 'qrCodeUrl'));
        } catch (Exception $e) {
            Log::error('Erreur lors du chargement de la page: ' . $e->getMessage());
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
        Transactionn::create([
            'user_id' => $user->id,
            'type' => 'Envoi', // Type de transaction
            'montant' => $amount,
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
        return redirect()->route('clients.dashboard')->with('message', 'Transfert effectué avec succès.');
    }
}
