<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; //
use App\Models\User; //
use Firebase\JWT\JWT;//
use Illuminate\Support\Facades\Auth; //

class UserController extends Controller
{
    public function register(Request $request) {
        $validator = validator::make($request->all(), [
            'name' => 'required|',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $user = $validator->validated();

        User::create($user);

        $payload = [
            'name' => $user['name'],
            'role' => 'user',
            'iat' => Carbon::now()->timestamp,
            'exp' => Carbon::now()->timestamp + 7200
        ];

        $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

        return response()->json([
            'msg' => 'Akun berhasil dibuat',
            'data' => 'Bearer '.$token
        ], 200);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $user = $validator->validated();

        if(Auth::attempt($user)) {
            $payload = [
                'name' => Auth::user()->name,
                'role' => Auth::user()->role,
                'iat' => Carbon::now()->timestamp,
                'exp' => Carbon::now()->timestamp + 7200
            ];

            $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

            return response()->json([
                'msg' => 'Berhasil login',
                'data' => 'Bearer '.$token
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Email atau password salah'
            ], 401);
        }
    }
}
