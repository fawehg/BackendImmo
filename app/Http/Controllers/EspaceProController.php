<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EspaceProController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $response = [
            "ResultInfo" => [
                'Success' => true,
                'ErrorMessage' => "",
            ],
            "ResultData" => []
        ];

        $validEmail = "business2client2024@gmail.com";
        $validPassword = "Khalilo2512";

        if ($credentials['email'] !== $validEmail || $credentials['password'] !== $validPassword) {
            $response["ResultInfo"]["Success"] = false;
            $response["ResultInfo"]["ErrorMessage"] = 'Adresse e-mail ou mot de passe incorrect.';
            return response()->json($response, 401);
        }

        $response["ResultInfo"]["Success"] = true;
        $response["ResultData"]['user'] = ['email' => $validEmail];

        return response()->json($response, 200);
    }
}
