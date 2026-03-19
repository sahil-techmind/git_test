<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleController extends Controller
{
    public function redirect()
    {
        $client_id = env('GOOGLE_CLIENT_ID');
        $redirect_uri = url('/dashboard');



        $url = "https://accounts.google.com/o/oauth2/v2/auth?"
            . http_build_query([
                'client_id' => $client_id,
                'redirect_uri' => $redirect_uri,
                'response_type' => 'code',
                'scope' => 'email profile',
                'access_type' => 'offline',
                'prompt' => 'select_account'
            ]);



        return redirect($url);
    }

    public function callback(Request $request)
    {

        $client_id = env('GOOGLE_CLIENT_ID');
        $client_secret = env('GOOGLE_CLIENT_SECRET');
        $redirect_uri = url('/google/callback');

        $code = $request->code;

        $response = Http::asForm()->post(
            'https://oauth2.googleapis.com/token',
            [
                'code' => $code,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => $redirect_uri,
                'grant_type' => 'authorization_code'
            ]
        );

        $token = $response->json()['access_token'];

        $user = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get('https://www.googleapis.com/oauth2/v2/userinfo');

        $userData = $user->json();

        return $userData;
    }
}
