<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT; 
use Carbon\Carbon; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; 
use Laravel\Socialite\Facades\Socialite; 

class OAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback() {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $password = Hash::make(Str::random(16));

        $user = User::firstOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'password' => $password
            ]
        );

        $payload = [
            'name' => $user['name'],
            'role' => 'user',
            'iat' => Carbon::now()->timestamp,
            'exp' => Carbon::now()->timestamp + 7200 // 2 hours
        ];

        $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

        return response()->json([
            'msg' => 'Registered and logged in succesfully!',
            'data' => [
                'name' => $user['name'],
                'email' => $user['email']
            ],
            'token' => 'Bearer '.$token
        ], 200);
    }
}
