<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Client;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        $client = Client::where('email', $request->email)->first();

        if ($user) {
            $token = $this->generateResetToken();
            $this->insertResetToken($user->email, $token); // Insérer le token dans la base de données
            $this->sendResetLinkEmailToUser($user->email, $token);
        } elseif ($client) {
            $token = $this->generateResetToken();
            $this->insertResetToken($client->email, $token); // Insérer le token dans la base de données
            $this->sendResetLinkEmailToUser($client->email, $token);
        } else {
            return response()->json(['ResultInfo' => ['Success' => false, 'ErrorMessage' => 'Aucun utilisateur ou client trouvé avec cette adresse e-mail']], 404);
        }

        return response()->json(['ResultInfo' => ['Success' => true, 'ErrorMessage' => ''], 'ResultData' => ['message' => 'Code envoyé dans votre email']], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        $client = Client::where('email', $request->email)->first();

        if ($user && $this->isTokenValid($user->email, $request->token)) {
            $response = $this->resetUserPassword($user, $request->token, $request->password);
        } elseif ($client && $this->isTokenValid($client->email, $request->token)) {
            $response = $this->resetClientPassword($client, $request->token, $request->password);
        } else {
            return response()->json(['ResultInfo' => ['Success' => false, 'ErrorMessage' => 'Email ou token invalide.']], 400);
        }

        if ($response) {
            DB::table('password_resets')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->delete();

            return response()->json(['ResultInfo' => ['Success' => true, 'ErrorMessage' => ''], 'ResultData' => ['message' => 'Mot de passe réinitialisé avec succès']], 200);
        } else {
            return response()->json(['ResultInfo' => ['Success' => false, 'ErrorMessage' => 'Une erreur est survenue lors de la réinitialisation du mot de passe']], 400);
        }
    }

    protected function generateResetToken()
    {
        return Str::random(6);
    }

    protected function sendResetLinkEmailToUser($email, $token)
    {
        Mail::raw('Voici votre code de réinitialisation : ' . $token, function ($message) use ($email) {
            $message->to($email)->subject('Réinitialisation de mot de passe');
        });
    }

    protected function insertResetToken($email, $token)
    {
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );
    }

    protected function resetUserPassword($user, $token, $password)
    {
        $status = Password::broker('users')->reset(
            ['email' => $user->email, 'password' => $password, 'token' => $token],
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET;
    }

    protected function resetClientPassword($client, $token, $password)
    {
        $status = Password::broker('clients')->reset(
            ['email' => $client->email, 'password' => $password, 'token' => $token],
            function ($client, $password) {
                $client->password = bcrypt($password);
                $client->save();
            }
        );

        return $status === Password::PASSWORD_RESET;
    }

    protected function isTokenValid($email, $token)
    {
        return DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->exists();
    }
}
