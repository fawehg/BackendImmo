<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maison;
use App\Models\Villa;
use App\Models\Appartement;
use App\Models\Bureau;
use App\Models\Ferme;
use App\Models\EtageVilla;
use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PropertyApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = [
            'maisons' => Maison::with(['vendeur', 'ville', 'delegation']),
            'villas' => Villa::with(['vendeur', 'ville', 'delegation']),
            'appartements' => Appartement::with(['vendeur', 'ville', 'delegation']),
            'bureaux' => Bureau::with(['vendeur', 'ville', 'delegation']),
            'fermes' => Ferme::with(['vendeur', 'ville', 'delegation']),
            'etage_villa' => EtageVilla::with(['vendeur', 'ville', 'delegation']),
            'terrains' => Terrain::with(['vendeur', 'ville', 'delegation']),
        ];

        $propertyTypes = [];
        foreach ($query as $type => $model) {
            if ($status !== 'all') {
                $propertyTypes[$type] = $model->where('status', $status)->get();
            } else {
                $propertyTypes[$type] = $model->get();
            }
        }

        if ($request->ajax()) {
            return response()->json(['propertyTypes' => $propertyTypes]);
        }

        return view('admin.properties.index', compact('propertyTypes'));
    }

    public function show($type, $id)
    {
        $model = $this->getModel($type);
        $property = $model::with(['vendeur', 'ville', 'delegation'])->findOrFail($id);
        return view('admin.properties.show', compact('property', 'type'));
    }

    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string|required_if:status,rejected',
        ]);

        $model = $this->getModel($type);
        $property = $model::findOrFail($id);

        $property->status = $request->status;
        if ($request->status === 'rejected') {
            $property->rejection_reason = $request->rejection_reason;
        }
        $property->save();

        $seller = $property->vendeur;
        if ($seller && $seller->email) {
            if ($request->status === 'approved') {
                Mail::to($seller->email)->send(new \App\Mail\PropertyApproved($property));
            } else {
                Mail::to($seller->email)->send(new \App\Mail\PropertyRejected($property, $request->rejection_reason));
            }
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Statut mis à jour avec succès', 'property' => $property]);
        }

        return redirect()->route('admin.properties.index')->with('success', 'Statut mis à jour avec succès.');
    }

    private function getModel($type)
    {
        switch ($type) {
            case 'maisons': return Maison::class;
            case 'villas': return Villa::class;
            case 'appartements': return Appartement::class;
            case 'bureaux': return Bureau::class;
            case 'fermes': return Ferme::class;
            case 'etage_villa': return EtageVilla::class;
            case 'terrains': return Terrain::class;
            default: abort(404, 'Type de propriété non valide');
        }
    }
}