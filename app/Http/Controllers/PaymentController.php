<?php

namespace App\Http\Controllers;

use App\Statics\Statics;
use App\SystemDesignType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\SetupIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
//     For design request
    public function placeHoldOnFunds(Request $request)
    {
        $type = SystemDesignType::with('latestPrice')->where('name', $request->name)->firstOrFail();

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $stripeResponse = \Stripe\PaymentIntent::create([
            'amount' => $type->latestPrice->price * 100,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'capture_method' => 'manual',
            'customer' => Auth::user()->stripe_id,
            'payment_method' => Auth::user()->default_payment_method
        ]);
        Log::info("Amount held for design request", ["stripe_response" => $stripeResponse]);
        return ["client_secret" => $stripeResponse->client_secret,"response"=>$stripeResponse];
    }

    //     For change request
    public function placeHoldOnFundsForCR(Request $request)
    {
        $this->validate($request, [
            "change_request_id" => 'required',
            "design_id" => "required"
        ]);

        $design = Auth::user()->designs()->with(['changeRequests' => function ($query) use ($request) {
            $query->findOrFail($request->change_request_id);
        }])->where('system_designs.id', $request->design_id)->firstOrFail();

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        if ($design->changeRequests[0]->price > 0){
            $stripeResponse = \Stripe\PaymentIntent::create([
                'amount' => $design->changeRequests[0]->price * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'capture_method' => 'manual',
                'customer' => Auth::user()->stripe_id,
                'payment_method' => Auth::user()->default_payment_method
            ]);
            Log::info("Amount held for change request", ["stripe_response" => $stripeResponse]);
            return ["client_secret" => $stripeResponse->client_secret];
        }else
            return ["client_secret" => "skip"];

    }

    public function getPaymentMethods()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $cards = \Stripe\PaymentMethod::all([
            'customer' => Auth::user()->stripe_id,
            'type' => 'card',
        ]);

        $data = collect($cards->data);

        $filtered = $data->map(function ($item, $key) {
            return [
                "id" => $item->id,
                "card" => $item->card,
            ];
        });

        $default = $filtered->firstWhere('id', '=', Auth::user()->default_payment_method);

        $filtered = $filtered->reject(function ($item, $key) {
            return Auth::user()->default_payment_method === $item['id'];
        });

        return view('profile.payment-methods', ["cards" => $filtered, "default" => $default]);
    }

    public function newCard()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $intent = SetupIntent::create([
            'customer' => Auth::user()->stripe_id
        ]);
        return ["secret" => $intent->client_secret];
    }

    public function setDefault(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $info = Customer::update(
            Auth::user()->stripe_id,
            [
                "invoice_settings" => [
                    "default_payment_method" => $request->id]
            ]);
        return Auth::user()->update([
            "default_payment_method" => $info['invoice_settings']['default_payment_method']
        ]);
    }


    public function cancelPayment(Request $request)
    {
        //$type = SystemDesignType::with('latestPrice')->where('name', $request->name)->firstOrFail();

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripeResponse = \Stripe\PaymentIntent::retrieve($request->code,[]);
        if($request->status=='cancel initiated')
        {
        
        $response = $stripeResponse->cancel();
        }
        else if($request->status=='refund initiated')
        {
            $response=\Stripe\Refund::create(['payment_intent' => $stripeResponse]);
            //$response=$stripeResponse->refund();
        }

        Log::info("Payment Intent :", $response->toArray());
        return $response;
    }
}
