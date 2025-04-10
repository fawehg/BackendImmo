<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Notifications\NouvelleDemandeNotification;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\TravailDemander;
use Illuminate\Support\Facades\Mail;
use App\Mail\AcceptanceNotification;
use Illuminate\Support\Facades\Validator;

class DemandeController extends Controller
{
    public function demandestore(Request $request)
    {
        $request->validate([
            'domaines' => 'required|string',
            'specialites' => 'required|string',
            'city' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $client = Auth::guard('client_api')->user();

        if (!$client) {
            return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
        }

        $demande = new Demande($request->only('domaines', 'specialites', 'city', 'date', 'time', 'description'));
        $demande->client_id = $client->id;

        if ($request->hasFile('image')) {
            $demande->image = $request->file('image')->store('uploads', 'public');
        }

        $demande->save();

        $token = JWTAuth::fromUser($client);

        return response()->json([
            'Success' => true,
            'ErrorMessage' => '',
            'ResultData' => [
                'demande' => $demande,
                'client_token' => $token,
           'demande_id' => $demande->id, 

                'message' => 'Demande créée avec succès'
            ]
        ], 201);
    }



    
    public function selectOuvrier(Request $request)
    {
        $client = Auth::guard('client_api')->user();
    
        if (!$client) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }
    
        $demande = Demande::first(); 
    
        if (!$demande) {
            return response()->json(['error' => 'Demande non trouvée'], 404);
        }
    
          
        $ouvrierId = $request->input('ouvrier_id');
$ouvrier = User::findOrFail($ouvrierId);

if (!$ouvrier) {
    return response()->json(['error' => 'Ouvrier non trouvé'], 404);
}

$travailDemander = new TravailDemander();
$travailDemander->client_id = $client->id;
$travailDemander->demande_id = $demande->id; 
$travailDemander->ouvrier_id = $ouvrierId; 
$travailDemander->save();

        
            if (!$ouvrier) {
                return response()->json(['error' => 'Ouvrier non trouvé'], 404);
            }
        
            $ouvrier->notify(new NouvelleDemandeNotification($demande, $client));
        
            $clientInfo = [
                'Nom du client' => $client->nom,
                'Prénom du client' => $client->prenom,
                'Adresse du client' => $client->adresse,
                'Email du client' => $client->email,
            ];
        
            $demandeInfo = [
                'Domaines' => $demande->domaines,
                'Spécialités' => $demande->specialites,
                'Ville' => $demande->city,
                'Description' => $demande->description,
        

            ];
        
            return response()->json([
                'message' => 'Notification envoyée à louvrier choisi',
                'client' => $clientInfo,
                'demande' => $demandeInfo,
                'ouvrier_id'=>$ouvrierId
            ]);
        }




        public function travailDemander(Request $request)
        {
     
            $travailDemanders = TravailDemander::with('client', 'demande')->get();
        
            if ($travailDemanders->isEmpty()) {
                return response()->json(['error' => 'Aucun travail trouvé'], 404);
            }
        
            $travails = [];
        
            foreach ($travailDemanders as $travailDemander) {
                $client = $travailDemander->client;
                $demande = $travailDemander->demande;
        
                if (!$client || !$demande) {
                    return response()->json(['error' => 'Client ou demande non trouvé'], 404);
                }
        
                $travails[] = [
                    'client' => [
                        'Nom' => $client->nom,
                        'Prénom' => $client->prenom,
                        'Adresse' => $client->adresse,
                        'Email' => $client->email,
                    ],
                    'demande' => [
                        'Domaines' => $demande->domaines,
                        'Spécialités' => $demande->specialites,
                        'Ville' => $demande->city,
                        'Description' => $demande->description,
                        'Date' => $demande->date,
                        'Heure' => $demande->time,
                    ],
                ];
            }
        
            return response()->json($travails);
        }








        
        public function confirmDemande(Request $request)
        {
            $demandeId = $request->input('demandeId');

            $demande = Demande::find($demandeId);

            $travailDemander = TravailDemander::with('client', 'demande')->where('demande_id', $demandeId)->first();

            if ($travailDemander && $travailDemander->client && $travailDemander->client->email) {
                Mail::to($travailDemander->client->email)->send(new AcceptanceNotification($travailDemander->client->nom, $demande->description));
            } else {
            }

            return response()->json(['message' => 'Demande confirmée avec succès']);
        }
        public function validation(Request $request)
        {
            $ouvrierId = $request->input('ouvrier_id');
        
            $travailDemander = TravailDemander::where('ouvrier_id', $ouvrierId)->first();
        
            if (!$travailDemander) {
                return response()->json(['error' => 'Aucun travail trouvé pour cet ouvrier'], 404);
            }
        
            $ouvrier = $travailDemander->ouvrier;
            $demande = $travailDemander->demande;
        
            if (!$ouvrier || !$demande) {
                return response()->json(['error' => 'Ouvrier ou demande non trouvé'], 404);
            }
        
            $travail = [
                'ouvrier' => [
                    'Nom' => $ouvrier->nom,
                    'Prénom' => $ouvrier->prenom,
                    'Adresse' => $ouvrier->adresse,
                    'Email' => $ouvrier->email,
                ],
                'demande' => [
                    'Domaines' => $demande->domaines,
                    'Spécialités' => $demande->specialites,
                    'Ville' => $demande->city,
                    'Description' => $demande->description,
                    'Date' => $demande->date,
                    'Heure' => $demande->time,
                ],
            ];
        
            return response()->json($travail);
        }
        
        public function index()
        {
            $demandes = Demande::orderBy('created_at', 'DESC')->get();
      
            return view('demandes.index', compact('demandes'));
        }
      
    
        public function create()
        {
            return view('demandes.create');
        }
      
      
        public function store(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'domaines' => 'required|string',
                'specialites' => 'required|string',
                'city' => 'required|string',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $demande = new Demande([
                'domaines' => $request->domaines,
                'specialites' => $request->specialites,
                'city' => $request->city,
                'date' => $request->date,
                'time' => $request->time,
                'description' => $request->description,
            ]);
    
            $demande->save();
    
            return redirect()->route('demandes.index')->with('success', 'Demande ajoutée avec succès');
        }
      
      
        public function show(string $id)
        {
            $demande = Demande::findOrFail($id);
      
            return view('demandes.show', compact('demande'));
        }
      
     
        public function edit(string $id)
        {
            $demande = Demande::findOrFail($id);
      
            return view('demandes.edit', compact('demande'));
        }
      
   
        public function update(Request $request, string $id)
        {
            $demande = Demande::findOrFail($id);
      
            $demande->update($request->all());
      
            return redirect()->route('demandes')->with('success', 'Demande mise à jour avec succès');
        }
      
      
        public function destroy(string $id)
        {
            $demande = Demande::findOrFail($id);
      
            $demande->delete();
      
            return redirect()->route('demandes')->with('success', 'Demande supprimée avec succès');
        }   
}