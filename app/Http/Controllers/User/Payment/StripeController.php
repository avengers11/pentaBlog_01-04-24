<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Models\User\UserPackage;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use App\Http\Controllers\Front\UserCheckoutController;

class StripeController extends Controller
{
    public function __construct()
    {
        //Set Spripe Keys
        $stripe = UserPaymentGeteway::whereKeyword('stripe')->where('user_id', getUser()->id)->first();
        $stripeConf = json_decode($stripe->information, true);
        Config::set('services.stripe.key', $stripeConf["key"]);
        Config::set('services.stripe.secret', $stripeConf["secret"]);
    }

    public function paymentProcess(Request $request, $_amount, $_title, $_success_url, $_cancel_url)
    {
        $title = $_title;
        $price = $_amount;
        $price = round($price, 2);
        $cancel_url = $_cancel_url;
        Session::put('user_request', $request->all());
        $stripe = Stripe::make(Config::get('services.stripe.secret'));
        $token = $request->stripeToken;

        if (!isset($token)) {
            return back()->with('error', 'Token Problem With Your Token.');
        }
        $charge = $stripe->charges()->create([
            'source' => $token,
            'currency' => 'USD',
            'amount'   => $price
        ]);

        if ($charge['status'] == 'succeeded') {
            $user = getUser();
            $txnId = UserPermissionHelper::uniqidReal(8);
            $chargeId = $request->paymentId;
            $order = $this->saveOrder($request, $txnId, $chargeId, 'Completed');
            $order_id = $order->id;
            $this->saveOrderedItems($order_id);
            $this->sendMails($order);
            session()->flash('success', __('successful_payment'));
            Session::forget('user_request');
            Session::forget('user_amount');
            Session::forget('user_paypal_payment_id');
            return redirect()->route('customer.success.page', getParam());
        }
        return redirect($cancel_url)->with('error', 'Please Enter Valid Credit Card Informations.');
    }
    public function cancelPayment()
    {
        session()->flash('warning', __('cancel_payment'));
        return redirect()->route('customer.itemcheckout.stripe.cancel', getParam());
    }
}
