<?php 

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Compte;
use App\Models\Transfert;
use App\Models\Distributeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Compter le nombre total de comptes clients non bloqués
        $totalClients = Compte::where('role', 'client')
            ->where('status', '!=', 'bloqué') // Exclure les comptes bloqués
            ->count();

        // Compter le nombre total de comptes distributeurs non bloqués
        $totalDistributeurs = Compte::where('role', 'distributeur')
            ->where('status', '!=', 'bloqué') // Exclure les comptes bloqués
            ->count();
                
        // Compter le nombre de transactions du jour
        $transactionsJour = Transfert::whereDate('created_at', today())->count();

        // Calculer le volume total des transactions complètes du jour
        $volumeJour = Transfert::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('montant');

        // Récupérer les dernières transactions sans expéditeurs et destinataires
        $dernieresTransactions = Transfert::latest()
            ->take(10)
            ->get();

        // Récupérer l'utilisateur connecté
        $agent = Auth::user(); // Utiliser Auth pour obtenir l'utilisateur
        $soldeAgent = $agent ? $agent->solde : 0; // Vérifiez si l'agent est authentifié avant d'accéder à son solde

        // Récupérer seulement les 3 derniers comptes
        $comptes = Compte::latest()->take(3)->get(); // Réduire à 3 comptes

        return view('dashboard.index', compact(
            'totalClients',
            'totalDistributeurs',
            'transactionsJour',
            'volumeJour',
            'dernieresTransactions',
            'soldeAgent', // Ajoutez le solde de l'agent à la vue
            'comptes' // Ajoutez les comptes à la vue
        ));
    }

    public function redirectToDashboard()
    {
        return redirect()->route('dashboard.index');
    }
}