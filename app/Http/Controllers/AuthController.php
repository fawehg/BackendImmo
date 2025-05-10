<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordCode;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage; 
use App\Models\Admin; 
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
    {
       
        
    public function showw($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
   

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                "ResultInfo" => [
                    'Success' => false,
                    'ErrorMessage' => "L'utilisateur avec cet e-mail n'existe pas.",
                ],
                "ResultData" => []
            ], 404);
        }
    
        $resetCode = mt_rand(100000, 999999); 
    
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $resetCode,
            'created_at' => now(),
        ]);
    
        Mail::to($user->email)->send(new ResetPasswordCode($resetCode));
    
        return response()->json([
            "ResultInfo" => [
                'Success' => true,
                'ErrorMessage' => "",
            ],
            "ResultData" => [
                'message' => 'Un code de réinitialisation a été envoyé à votre adresse e-mail.'
            ]
        ]);
    }
    
    public function verifyResetCode(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|digits:6',
        'password' => 'required|string|min:6',
    ]);

    $reset = DB::table('password_resets')
        ->where('email', $request->email)
        ->where('token', $request->code)
        ->first();

    if (!$reset) {
        return response()->json([
            "ResultInfo" => [
                'Success' => false,
                'ErrorMessage' => "Le code de réinitialisation est invalide.",
            ],
            "ResultData" => []
        ], 400);
    }


    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    DB::table('password_resets')->where('email', $request->email)->delete();

    return response()->json([
        "ResultInfo" => [
            'Success' => true,
            'ErrorMessage' => "",
        ],
        "ResultData" => [
            'message' => 'Le mot de passe a été réinitialisé avec succès.'
        ]
    ]);
}

    public function profil(Request $request)
    {
        try {
            $user = $request->user();
    
            return response()->json([
                "ResultInfo" => [
                    'Success' => true,
                ],
                "ResultData" => [
                    'data' => $user,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "ResultInfo" => [
                    'Success' => false,
                    'ErrorMessage' => "Erreur lors de la récupération du profil de l'utilisateur",
                ],
                "ResultData" => [
                    'error' => $e->getMessage(),
                ]
            ], 500);
        }
    }

    public function mettreAJourProfil(Request $request)
    {
        try {
            $user = $request->user();
    
            $validator = Validator::make($request->all(), [
                'nom' => 'string',
                'prenom' => 'string',
                'email' => 'email|unique:users,email,' . $user->id,

                'password' => 'nullable|string|min:6']);
        
    
            if ($validator->fails()) {
                return response()->json([
                    "ResultInfo" => [
                        'Success' => false,
                        'ErrorMessage' => 'Validation failed',
                    ],
                    "ResultData" => [
                        'errors' => $validator->errors(),
                    ]
                ], 400);
            }
    
            if ($request->has('password')) {
                $request->merge(['password' => bcrypt($request->password)]);
            }
    
            $user->update($request->all());
    
            return response()->json([
                "ResultInfo" => [
                    'Success' => true,
                    'Message' => 'Profil mis à jour avec succès',
                ],
                "ResultData" => []
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "ResultInfo" => [
                    'Success' => false,
                    'ErrorMessage' => "Erreur lors de la mise à jour du profil de l'utilisateur",
                ],
                "ResultData" => []
            ], 500);
        }
    }
    public function register()
    {
        return view('auth/register');
    }
  
    public function registerSave(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ])->validate();
  
        Admin::create([ 
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'Admin'
        ]);
  
        return redirect()->route('login');
    }
  
    public function login()
    {
        return view('auth/login');
    }
  
    public function loginAction(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();
  
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }
  
        $request->session()->regenerate();
  
        return redirect()->route('dashboard');
    }
  
    public function logout(Request $request)
    {
Auth::guard('admins')->logout();
  
        $request->session()->invalidate();
  
        return redirect('/login');
    }
 
    public function profile()
    {
        return view('profile');
    }

    
   
    
    
    
  
    
  
   
   
    
    
    public function updateProfile(Request $request)
    {
        try {
            $admin = Admin::findOrFail(Auth::id());

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Update basic fields
            $admin->name = $validatedData['name'];
            $admin->phone = $validatedData['phone'];
            $admin->address = $validatedData['address'];

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if it exists
                if ($admin->avatar) {
                    Storage::disk('public')->delete($admin->avatar);
                }
                // Store new avatar
                $path = $request->file('avatar')->store('avatars', 'public');
                $admin->avatar = $path;
            }

            $admin->save();

            return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

}