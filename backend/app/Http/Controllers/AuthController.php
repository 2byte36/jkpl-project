<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    private $key;

    public function __construct()
    {
        $this->key = env('JWT_SECRET');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'username'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'birth_date' => 'required|date'
        ]);

        $user = User::create([
            'username'     => $request->username,
            'email'    => $request->email,
            'password' => app('hash')->make($request->password),
            'birth_date' => $request->birth_date,
            'is_admin' => false
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !app('hash')->check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $payload = [
            'iss' => "lumen-jwt",        // Issuer
            'sub' => $user->id,          // Subject (User ID)
            'iat' => time(),             // Issued at
            'exp' => time() + 60 * 60      // Expiration time (1 hour)
        ];

        $token = JWT::encode($payload, $this->key, 'HS256');

        return response()->json(['token' => $token]);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'username' => $googleUser->getName(),
                'password' => app('hash')->make(str()->random(10)),
                'birth_date' => '2000-01-01', // dummy birth date
                'is_admin' => false
            ]
        );

        $payload = [
            'iss' => "lumen-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60
        ];

        $token = JWT::encode($payload, $this->key, 'HS256');
        
        $frontendUrl = rtrim(env('FRONTEND_URL', 'http://localhost:5173'), '/');

        return response('', 302, ['Location' => $frontendUrl . '/oauth/callback?token=' . $token]);
    }
}
