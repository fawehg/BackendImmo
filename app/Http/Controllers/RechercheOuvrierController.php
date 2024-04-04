<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RechercheOuvrierController extends Controller
{
    public function rechercherOuvriers(Request $request)
    {
        // Récupérer les critères de recherche depuis la requête
        $profession = $request->input('profession');
        $specialties = $request->input('specialties');
        $date = $request->input('date');
        $time = $request->input('time');

        // Filtrer les utilisateurs par profession et spécialités
        $query = User::where('profession', $profession)
                     ->whereJsonContains('specialties', $specialties);

        // Filtrage supplémentaire pour la disponibilité en fonction de la date et de l'heure
        $query->whereExists(function ($query) use ($date, $time) {
            $query->select(DB::raw(1))
                  ->from('users')
                  ->whereColumn('users.id', 'demandes.user_id')
                  ->where('joursDisponibilite', 'like', '%' . date('l', strtotime($date)) . '%')
                  ->whereTime('heureDebut', '<=', $time)
                  ->whereTime('heureFin', '>=', $time);
        });

        // Exécuter la requête et récupérer les utilisateurs correspondants
        $utilisateurs = $query->get();

        // Retourner les utilisateurs trouvés en réponse
        return response()->json($utilisateurs);
    }
}
