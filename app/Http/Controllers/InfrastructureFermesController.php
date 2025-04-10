<?php

namespace App\Http\Controllers;

use App\Models\InfrastructureFermes;
use Illuminate\Http\Request;

class InfrastructureFermesController extends Controller
{
    public function index()
    {
        return response()->json(InfrastructureFermes::all());
    }
}
