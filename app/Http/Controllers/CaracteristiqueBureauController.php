<?php

namespace App\Http\Controllers;

use App\Models\CaracteristiqueBureau;
use Illuminate\Http\Request;

class CaracteristiqueBureauController extends Controller
{
    public function index()
    {
        return CaracteristiqueBureau::all();
    }

   
}