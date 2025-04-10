<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Demande; // Importez le modèle Demande
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $ouvriersCount = User::count();
        $clientsCount = Client::count();
        $demandesCount = Demande::count(); // Comptez les demandes

        $professionCounts = User::select('profession', DB::raw('COUNT(*) as count'))
            ->groupBy('profession')
            ->get();

        // Préparer les données pour le graphique
        $labels = $professionCounts->pluck('profession');
        $data = $professionCounts->pluck('count');

    // Passer les données à la vue
    return view('dashboard', compact('ouvriersCount', 'clientsCount', 'demandesCount', 'labels', 'data'));
}

}
