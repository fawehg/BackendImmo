<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appartement;  // Remplacé par Appartement
use App\Models\Ferme;        // Remplacé par Ferme
use App\Models\Maison;       // Remplacé par Maison
use App\Models\Bureau;       // Ajouté Bureau
use App\Models\EtageVilla;   // Ajouté EtageVilla
use App\Models\Terrain;      // Ajouté Terrain
use App\Models\Villa;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Compter les appartements, fermes, maisons, bureaux, étages de villas, et terrains
        $appartementsCount = Appartement::count();  // Nombre d'appartements
        $fermesCount = Ferme::count();               // Nombre de fermes
        $maisonsCount = Maison::count();             // Nombre de maisons
        $bureauxCount = Bureau::count();             // Nombre de bureaux
        $etagesVillaCount = EtageVilla::count();     // Nombre d'étages de villas
        $terrainsCount = Terrain::count();           // Nombre de terrains
        $villasCount = Villa::count();

        // Compter les appartements par ville
        $appartementParVille = Appartement::select('ville_id', DB::raw('COUNT(*) as count'))
            ->groupBy('ville_id')
            ->with('ville:id,nom') // récupérer les noms des villes
            ->get()
            ->map(function ($item) {
                return [
                    'ville' => $item->ville->nom ?? 'Inconnue',
                    'count' => $item->count
                ];
            });
    
        // Compter les fermes par ville
        $fermeParVille = Ferme::select('ville_id', DB::raw('COUNT(*) as count'))
            ->groupBy('ville_id')
            ->with('ville:id,nom')
            ->get()
            ->map(function ($item) {
                return [
                    'ville' => $item->ville->nom ?? 'Inconnue',
                    'count' => $item->count
                ];
            });
    
        // Compter les maisons par ville
        $maisonParVille = Maison::select('ville_id', DB::raw('COUNT(*) as count'))
            ->groupBy('ville_id')
            ->with('ville:id,nom')
            ->get()
            ->map(function ($item) {
                return [
                    'ville' => $item->ville->nom ?? 'Inconnue',
                    'count' => $item->count
                ];
            });

        // Compter les bureaux par ville
        $bureauParVille = Bureau::select('ville_id', DB::raw('COUNT(*) as count'))
            ->groupBy('ville_id')
            ->with('ville:id,nom')
            ->get()
            ->map(function ($item) {
                return [
                    'ville' => $item->ville->nom ?? 'Inconnue',
                    'count' => $item->count
                ];
            });

        // Compter les étages de villas par ville
        $etageVillaParVille = EtageVilla::select('ville_id', DB::raw('COUNT(*) as count'))
            ->groupBy('ville_id')
            ->with('ville:id,nom')
            ->get()
            ->map(function ($item) {
                return [
                    'ville' => $item->ville->nom ?? 'Inconnue',
                    'count' => $item->count
                ];
            });

        // Compter les terrains par ville
        $terrainParVille = Terrain::select('ville_id', DB::raw('COUNT(*) as count'))
            ->groupBy('ville_id')
            ->with('ville:id,nom')
            ->get()
            ->map(function ($item) {
                return [
                    'ville' => $item->ville->nom ?? 'Inconnue',
                    'count' => $item->count
                ];
            });
            $villaParVille = Villa::select('ville_id', DB::raw('COUNT(*) as count'))
    ->groupBy('ville_id')
    ->with('ville:id,nom')
    ->get()
    ->map(function ($item) {
        return [
            'ville' => $item->ville->nom ?? 'Inconnue',
            'count' => $item->count
        ];
    });

    
        // Créer les labels (noms des villes)
        $labels = $appartementParVille->pluck('ville')->toArray();
    
        // Créer les données pour les graphiques
        $dataAppartements = $appartementParVille->pluck('count')->toArray();
        $dataFermes = $fermeParVille->pluck('count')->toArray();
        $dataMaisons = $maisonParVille->pluck('count')->toArray();
        $dataBureaux = $bureauParVille->pluck('count')->toArray();
        $dataEtagesVilla = $etageVillaParVille->pluck('count')->toArray();
        $dataTerrains = $terrainParVille->pluck('count')->toArray();
        $dataVillas = $villaParVille->pluck('count')->toArray();

        // Passer les données à la vue
        return view('dashboard', compact(
            'appartementsCount',
            'fermesCount',
            'maisonsCount',
            'bureauxCount',
            'etagesVillaCount',
            'terrainsCount',
            'villasCount', // 👈 ajouté ici

            'appartementParVille',
            'fermeParVille',
            'maisonParVille',
            'bureauParVille',
            'etageVillaParVille',
            'terrainParVille',
            'villaParVille', // 👈 ajouté ici

            'labels', 
            'dataAppartements',
            'dataFermes',
            'dataMaisons',
            'dataBureaux',
            'dataEtagesVilla',
            'dataTerrains',
            'dataVillas' // 👈 ajouté ici

        ));
    }
}
