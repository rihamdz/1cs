<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
    
        // Recherchez un employé avec l'e-mail spécifié
        $employee = Employee::where('email', $request->email)->first();
    
        if ($employee) {
            // Si un employé avec cet e-mail existe déjà, mettez à jour ses données
            $employee->update([
                'active' => true, 
            ]);
            //créez un nouvel utilisateur dans la table "users"
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash the password
                'email_verified_at' => null, // Marquer comme non vérifié pour le moment
            ]);
            // Génération d'un token pour la vérification par e-mail
            $verificationToken = Str::random(60);
            $user->verification_token = $verificationToken;
            $user->save();

            return response()->json(['message' => 'User added successfully'], 200);
        } else {
            // Si aucun employé avec cet e-mail n'existe, retournez une erreur
            return response()->json(['message' => 'Employee not found with this email'], 404);
        }
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        // Vérifiez les informations d'identification pour l'authentification API
        if (Auth::attempt($credentials)) {
            // Authentification réussie
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            return response()->json(['token' => $token]);
        } else {
            // Authentification échouée
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    
}