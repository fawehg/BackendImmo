<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delegation;

class DelegationController extends Controller
{
    public function getDelegationsByVille($villeId)
    {
        $delegations = Delegation::where('ville_id', $villeId)->get();
        return response()->json($delegations);
    }
}
