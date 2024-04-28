<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\User\UserPaymentGeteway;

class PaymentGatewaysApiController extends Controller
{
    /*
    ======================
    onlineShow
    ======================
    */
    public function onlineShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data['paypal'] = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'paypal']])->first();
        $data['stripe'] = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'stripe']])->first();
        $data['paystack'] = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'paystack']])->first();
        $data['paytm'] = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'paytm']])->first();
        $data['flutterwave'] = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'flutterwave']])->first();
        $data['instamojo'] = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'instamojo']])->first();
        $data['mollie'] = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'mollie']])->first();
        $data['razorpay'] = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'razorpay']])->first();
        $data['mercadopago'] = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'mercadopago']])->first();
        $data['anet'] = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'authorize.net']])->first();

        return response()->json($data);
    }
}
