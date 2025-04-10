<?php

namespace App\Http\Controllers;

use App\Models\OrientationFermes;
use Illuminate\Http\Request;

class OrientationFermesController extends Controller
{
    public function index()
    {
        return response()->json(OrientationFermes::all());
    }
}
