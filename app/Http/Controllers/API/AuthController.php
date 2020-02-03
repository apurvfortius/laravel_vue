<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;

        try{
            $responce = $http->post('http://127.0.0.1:8000/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => '2',
                    'client_secret' => 'aRoE7mlgom1PYiMgxX9jw7dwb3bHBdCOB4fHRN4t',
                    'username' => $request->email,
                    'password' => $request->password
                ]
            ]);
            return $responce->getBody();
        }
        catch(\GuzzleHttp\Exception\BadResponceException $e){
            if($e->getCode() === 400){
                return response()->json("Invalid Request. Please enter email and password", $e->getCode());
            }
            elseif($e->getCode() === 401){
                return response()->json("Your credentials are incorrect. Please try again", $e->getCode());
            }
            return response()->json("Something went wrong", $e->getCode());
        }
    }
}
