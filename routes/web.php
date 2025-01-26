<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/vue/{n1?}/{n2?}/{n3?}', function () {
    return view('vue');
});

Route::post('user/login', [LoginController::class, 'authenticate']);

route::get('/stripe/set-payment-method', function () {
    // auth()->user()
    $user = User::find(1);
    return view('stripe.payment-method', ['intent' => $user->createSetupIntent()]);
});
route::get('/stripe/get-payment-method', function () {
    // auth()->user()
    $user = User::find(1);
    //dd($user->findPaymentMethod("pm_1Qg1XiCWud7Ri9mJzUVwMsoo"));
    // dd($user->defaultPaymentMethod());
    dd($user->paymentMethods());
});
route::get('/stripe/delete-payment-method', function () {
    // auth()->user()
    $user = User::find(1);
    // $paymentMethod = $user->findPaymentMethod("pm_1Qg1XiCWud7Ri9mJzUVwMsoo");
    // $paymentMethod->delete();

    $user->deletePaymentMethods();
});
route::get('/stripe/create-payment-intent', function () {
    $user = User::find(1);
    $payment = $user->pay(100);

    return view('stripe.payment-confirm', ['clientSecret' => $payment->client_secret]);
});
route::get('/stripe/new-subcription', function () {
    // auth()->user()
    $user = User::find(1);

    dd(
        $user->newSubscription(
            'default2',
            'price_1QYPNDCWud7Ri9mJPPPtwAnj'
        )->quantity(3)
        ->create('pm_1Qh7GJCWud7Ri9mJr1sC38w8')
    );
});
route::get('/stripe/is-subcribed', function () {
    // auth()->user()
    $user = User::find(1);

    dd(
        // $user->subscribed('default')
        // $user->subscription('default')->onGracePeriod()
        // $user->subscription('default')->canceled()
        $user->subscription('default')->ended()
    );
});
