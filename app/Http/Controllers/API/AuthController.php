<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Route;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //$http = new \GuzzleHttp\Client;

        $email = $request->email;
        $password = $request->password;
        $request->request->add([
            'username' => $email,
            'password' => $password,
            'grant_type' => 'password',
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'scope' => '*'
        ]);

        $tokenRequest = Request::create(
            env('APP_URL').'/oauth/token',
            'post'
        );
        $response = Route::dispatch($tokenRequest);

        if($response->getStatusCode() == 200){
            return $response = $response->getContent();
        }
        elseif ($response->getStatusCode() === 400) {
            return response()->json('Invalid Request. Please enter a username or a password.', $response->getStatusCode());
        } 
        else if ($response->getStatusCode() === 401) {
            return response()->json('Your credentials are incorrect. Please try again', $response->getStatusCode());
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }
}
