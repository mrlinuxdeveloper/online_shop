<?php

namespace App\Http\Controllers;

class ApiGoldController extends Controller
{
    public function index()
    {
        $url = 'https://www.goldapi.io/api/history/XAU/USD?from=2026-08-08&to=2026-09-07';

        $api_key = env('GOLD_API_KEY');

        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_HTTPHEADER => [
                'x-access-token: ' . $api_key,
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);

            return response()->json([
                'error' => $error
            ], 500);
        }

        curl_close($curl);

        $gold = json_decode($response, true);

        return view('welcome', compact('gold'));
    }
}