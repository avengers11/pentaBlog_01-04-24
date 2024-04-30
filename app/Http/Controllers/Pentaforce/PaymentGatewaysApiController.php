<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\User\UserPaymentGeteway;
use App\Models\User\UserOfflineGateway;
use App\Models\User\UserShopSetting;
use Validator;

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
    public function onlineUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        if($request->type == 1){
            $paypal = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'paypal']])->first();
            $paypal->status = $request->status;
            $information = [];
            $information['client_id'] = $request->client_id;
            $information['client_secret'] = $request->client_secret;
            $information['sandbox_check'] = $request->sandbox_check;
            $information['text'] = "Pay via your PayPal account.";
            $paypal->information = json_encode($information);
            $paypal->save();
        }else if($request->type == 2){
            $stripe = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'stripe']])->first();
            $stripe->status = $request->status;
            $information = [];
            $information['key'] = $request->key;
            $information['secret'] = $request->secret;
            $information['text'] = "Pay via your Credit account.";
            $stripe->information = json_encode($information);
            $stripe->save();
        }else if($request->type == 3){
            $paytm = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'paytm']])->first();
            $paytm->status = $request->status;
            $information = [];
            $information['environment'] = $request->environment;
            $information['merchant'] = $request->merchant;
            $information['secret'] = $request->secret;
            $information['website'] = $request->website;
            $information['industry'] = $request->industry;
            $information['text'] = "Pay via your paytm account.";
            $paytm->information = json_encode($information);
            $paytm->save();
        }else if($request->type == 4){
            $instamojo = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'instamojo']])->first();
            $instamojo->status = $request->status;
            $information = [];
            $information['key'] = $request->key;
            $information['token'] = $request->token;
            $information['sandbox_check'] = $request->sandbox_check;
            $information['text'] = "Pay via your Instamojo account.";
            $instamojo->information = json_encode($information);
            $instamojo->save();
        }else if($request->type == 5){
            $paystack = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'paystack']])->first();
            $paystack->status = $request->status;
            $information = [];
            $information['key'] = $request->key;
            $information['email'] = $request->email;
            $information['text'] = "Pay via your Paystack account.";
            $paystack->information = json_encode($information);
            $paystack->save();
        }else if($request->type == 6){
            $flutterwave = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'flutterwave']])->first();
            $flutterwave->status = $request->status;
            $information = [];
            $information['public_key'] = $request->public_key;
            $information['secret_key'] = $request->secret_key;
            $information['text'] = "Pay via your Flutterwave account.";
            $flutterwave->information = json_encode($information);
            $flutterwave->save();
        }else if($request->type == 7){
            $mollie = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'mollie']])->first();
            $mollie->status = $request->status;
            $information = [];
            $information['key'] = $request->key;
            $information['text'] = "Pay via your Mollie Payment account.";
            $mollie->information = json_encode($information);
            $mollie->save();
            $arr = ['MOLLIE_KEY' => $request->key];
            setEnvironmentValue($arr);
            \Artisan::call('config:clear');
        }else if($request->type == 8){
            $razorpay = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'razorpay']])->first();
            $razorpay->status = $request->status;
            $information = [];
            $information['key'] = $request->key;
            $information['secret'] = $request->secret;
            $information['text'] = "Pay via your Razorpay account.";
            $razorpay->information = json_encode($information);
            $razorpay->save();
        }else if($request->type == 9){
            $anet = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'authorize.net']])->first();
            $anet->status = $request->status;
            $information = [];
            $information['login_id'] = $request->login_id;
            $information['transaction_key'] = $request->transaction_key;
            $information['public_key'] = $request->public_key;
            $information['sandbox_check'] = $request->sandbox_check;
            $information['text'] = "Pay via your Authorize.net account.";
            $anet->information = json_encode($information);
            $anet->save();
        }else{
            $mercadopago = UserPaymentGeteway::where([['user_id', $user->id], ['keyword', 'mercadopago']])->first();
            $mercadopago->status = $request->status;
            $information = [];
            $information['token'] = $request->token;
            $information['sandbox_check'] = $request->sandbox_check;
            $information['text'] = "Pay via your Mercado Pago account.";
            $mercadopago->information = json_encode($information);
            $mercadopago->save();
        }

        return response()->json(['success' => 'You are successfully updated payment informations!'], 200);
    }


    /*
    ======================
    Offline
    ======================
    */
    public function offlineShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data['ogateways'] = UserOfflineGateway::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $data['shopsettings'] = UserShopSetting::where('user_id', $user->id)->first();

        return response()->json($data);
    }
    public function offlineAddShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $rules = [
            'name' => 'required|max:100',
            'short_description' => 'nullable',
            'serial_number' => 'required|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $in = $request->all();
        $in['user_id'] = $user->id;

        UserOfflineGateway::create($in);

        return response()->json(['success' => 'Gateway added successfully!'], 200);
    }
    public function offlineUpdateShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $rules = [
            'name' => 'required|max:100',
            'short_description' => 'nullable',
            'serial_number' => 'required|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $in = $request->except('_token', 'ogateway_id');
        UserOfflineGateway::where('id', $request->ogateway_id)->update($in);

        return response()->json(['success' => 'Gateway updated successfully!'], 200);
    }

    public function offlineDeleteShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        UserOfflineGateway::where('id', $request->ogateway_id)->where('user_id', $user->id)->delete();

        return response()->json(['success' => 'Gateway updated successfully!'], 200);
    }

}
