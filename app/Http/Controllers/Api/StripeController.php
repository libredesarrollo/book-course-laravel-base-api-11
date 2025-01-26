<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Checkout;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{

    // Orden/Session
    function createSession(string $priceId, string $successURL = 'http://laravelbaseapi.test/vue/stripe/success', string $cancelUrl = 'http://laravelbaseapi.test/vue/stripe/cancel')
    {
      
        $session = Checkout::guest()->create($priceId, [
            // 'mode' => 'payment',
            'mode' => 'subscription',
            'success_url' => $successURL . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl
        ]);

        // return $session; // retorna la pagina de https://checkout.stripe.com/c/pay/c
        return $session->id;
    }

    private function checkSessionById(string $sessionId): Session
    {
        return Cashier::stripe()->checkout->sessions->retrieve($sessionId);
    }

    function checkPayment(string $sessionId)
    {
        $session = $this->checkSessionById($sessionId);

        // dd($session->payment_intent);
        // dd($session->payment_status);
        if ($session->payment_status == 'paid') {
            // To Do verificar que el session no existe en la BD

            // To Do Registrar producto al cliente

            // To Do register el pago BD
            // $session->payment_status;
            // $sessionId;
            // $price = intdiv($session->amount_total, 100);
            // return json_encode($session);

            // return response()->json('payment success');
        }
        return json_encode($session);
        return response()->json('payment not success', 400);
    }

    function checkPaymentIntentByid(string $paymentIntentId)
    {
        Stripe::setApiKey(config('cashier.secret'));
        return PaymentIntent::retrieve($paymentIntentId);
    }

    function stripeCustomer()
    {
        $user = User::find(3);  // auth()->user()->id()
        // $user->createAsStripeCustomer();
        // $user->asStripeCustomer();
        $user->createOrGetStripeCustomer();
    }

    function stripeBalance()
    {
        $user = User::find(3);

        // $user->creditBalance(500,'Remove balance');
        $user->debitBalance(600,'add balance');
        $balance = $user->balance();
        dd($balance);
    }
}
