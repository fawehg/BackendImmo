<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appartement;
use App\Models\Ferme;
use App\Models\Maison;
use App\Models\Bureau;
use App\Models\EtageVilla;
use App\Models\Terrain;
use App\Models\Villa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    // Constantes pour les types de transaction
    const VENTE_ID = 1;
    const LOCATION_ESTIVALE_ID = 2;
    const LOCATION_ANNUELLE_ID = 3;

    public function index()
    {
        // Count properties
        $appartementsCount = Appartement::count();
        $fermesCount = Ferme::count();
        $maisonsCount = Maison::count();
        $bureauxCount = Bureau::count();
        $etagesVillaCount = EtageVilla::count();
        $terrainsCount = Terrain::count();
        $villasCount = Villa::count();

        // Properties by city
        $appartementParVille = $this->getPropertiesByCity(Appartement::class);
        $fermeParVille = $this->getPropertiesByCity(Ferme::class);
        $maisonParVille = $this->getPropertiesByCity(Maison::class);
        $bureauParVille = $this->getPropertiesByCity(Bureau::class);
        $etageVillaParVille = $this->getPropertiesByCity(EtageVilla::class);
        $terrainParVille = $this->getPropertiesByCity(Terrain::class);
        $villaParVille = $this->getPropertiesByCity(Villa::class);

        // Transactions data - Inclure tous les types
        $transactionsData = [
            'Vente' => [
                'Appartement' => Appartement::where('type_transaction_id', self::VENTE_ID)->count(),
                'Ferme' => Ferme::where('type_id', self::VENTE_ID)->count(),
                'Maison' => Maison::where('type_transaction_id', self::VENTE_ID)->count(),
                'Bureau' => Bureau::where('type_id', self::VENTE_ID)->count(),
                'EtageVilla' => EtageVilla::where('type_id', self::VENTE_ID)->count(),
                'Terrain' => Terrain::where('type_id', self::VENTE_ID)->count(),
                'Villa' => Villa::where('type_id', self::VENTE_ID)->count(),
            ],
            'Location Annuelle' => [
                'Appartement' => Appartement::where('type_transaction_id', self::LOCATION_ANNUELLE_ID)->count(),
                'Ferme' => Ferme::where('type_id', self::LOCATION_ANNUELLE_ID)->count(),
                'Maison' => Maison::where('type_transaction_id', self::LOCATION_ANNUELLE_ID)->count(),
                'Bureau' => Bureau::where('type_id', self::LOCATION_ANNUELLE_ID)->count(),
                'EtageVilla' => EtageVilla::where('type_id', self::LOCATION_ANNUELLE_ID)->count(),
                'Terrain' => Terrain::where('type_id', self::LOCATION_ANNUELLE_ID)->count(),
                'Villa' => Villa::where('type_id', self::LOCATION_ANNUELLE_ID)->count(),
            ],
            'Location Estivale' => [
                'Appartement' => Appartement::where('type_transaction_id', self::LOCATION_ESTIVALE_ID)->count(),
                'Ferme' => Ferme::where('type_id', self::LOCATION_ESTIVALE_ID)->count(),
                'Maison' => Maison::where('type_transaction_id', self::LOCATION_ESTIVALE_ID)->count(),
                'Bureau' => Bureau::where('type_id', self::LOCATION_ESTIVALE_ID)->count(),
                'EtageVilla' => EtageVilla::where('type_id', self::LOCATION_ESTIVALE_ID)->count(),
                'Terrain' => Terrain::where('type_id', self::LOCATION_ESTIVALE_ID)->count(),
                'Villa' => Villa::where('type_id', self::LOCATION_ESTIVALE_ID)->count(),
            ],
        ];

        // Log pour déboguer transactionsData
        foreach (['Vente', 'Location Annuelle', 'Location Estivale'] as $transactionType) {
            foreach (['Appartement', 'Ferme', 'Maison', 'Bureau', 'EtageVilla', 'Terrain', 'Villa'] as $propertyType) {
                Log::info("Transaction Data: {$transactionType} - {$propertyType}", [
                    'count' => $transactionsData[$transactionType][$propertyType]
                ]);
            }
        }
        Log::info('transactionsData Complete', $transactionsData);

        // Prepare chart data
        $labels = $appartementParVille->pluck('ville')->merge(
            $fermeParVille->pluck('ville')
        )->merge(
            $maisonParVille->pluck('ville')
        )->merge(
            $bureauParVille->pluck('ville')
        )->merge(
            $etageVillaParVille->pluck('ville')
        )->merge(
            $terrainParVille->pluck('ville')
        )->merge(
            $villaParVille->pluck('ville')
        )->unique()->values()->toArray();

        $dataAppartements = $appartementParVille->pluck('count')->toArray();
        $dataFermes = $fermeParVille->pluck('count')->toArray();
        $dataMaisons = $maisonParVille->pluck('count')->toArray();
        $dataBureaux = $bureauParVille->pluck('count')->toArray();
        $dataEtagesVilla = $etageVillaParVille->pluck('count')->toArray();
        $dataTerrains = $terrainParVille->pluck('count')->toArray();
        $dataVillas = $villaParVille->pluck('count')->toArray();

        return view('dashboard', compact(
            'appartementsCount', 'fermesCount', 'maisonsCount', 'bureauxCount',
            'etagesVillaCount', 'terrainsCount', 'villasCount',
            'appartementParVille', 'fermeParVille', 'maisonParVille',
            'bureauParVille', 'etageVillaParVille', 'terrainParVille', 'villaParVille',
            'labels', 'dataAppartements', 'dataFermes', 'dataMaisons',
            'dataBureaux', 'dataEtagesVilla', 'dataTerrains', 'dataVillas',
            'transactionsData'
        ));
    }

    private function getPropertiesByCity($model)
    {
        return $model::select('ville_id', DB::raw('COUNT(*) as count'))
            ->groupBy('ville_id')
            ->with('ville:id,nom')
            ->get()
            ->map(function ($item) {
                return [
                    'ville' => $item->ville->nom ?? 'Inconnue',
                    'count' => $item->count
                ];
            });
    }
}