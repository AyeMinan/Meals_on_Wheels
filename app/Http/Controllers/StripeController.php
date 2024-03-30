<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function checkout(){
        return "Card Info Request Page";
    }
    public function payment(Request $request){
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SK'));

            Stripe::setApiKey(env('STRIPE_SK'));

            // Create a charge using the token
            $response = $stripe->charges->create([
                'amount' => $request->amount,
                'currency' => 'usd',
                'source' => 'tok_visa',
            ]);

            return response()->json([
                'status' => $response->status
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error message' => $e->getMessage()
            ], 500);
        }
    }


    public function success(){
        return "Payment Successful";
    }
}
