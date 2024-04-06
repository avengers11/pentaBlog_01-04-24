<?php

namespace App\Http\Controllers\User;

use Validator;
use App\Models\User\UserPay;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Http\Controllers\Controller;
use App\Models\User\UserOfflineGateway;
use Illuminate\Support\Facades\Auth;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Models\User\UserOfflinePaymentGeteway;
use App\Models\User\UserShopSetting;

class GatewayController extends Controller
{
    public function index()
    {
        $data['paypal'] = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paypal']])->first();
        $data['stripe'] = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'stripe']])->first();
        $data['paystack'] = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paystack']])->first();
        $data['paytm'] = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paytm']])->first();
        $data['flutterwave'] = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'flutterwave']])->first();
        $data['instamojo'] = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'instamojo']])->first();
        $data['mollie'] = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'mollie']])->first();
        $data['razorpay'] = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'razorpay']])->first();
        $data['mercadopago'] = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'mercadopago']])->first();
        $data['anet'] = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'authorize.net']])->first();
        // dd($data);
        return view('user.gateways.index', $data);
    }

    public function paypalUpdate(Request $request)
    {
        $paypal = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paypal']])->first();
        $paypal->status = $request->status;

        $information = [];
        $information['client_id'] = $request->client_id;
        $information['client_secret'] = $request->client_secret;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your PayPal account.";

        $paypal->information = json_encode($information);

        $paypal->save();

        $request->session()->flash('success', "Paypal informations updated successfully!");

        return back();
    }

    public function stripeUpdate(Request $request)
    {
        $stripe = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'stripe']])->first();
        $stripe->status = $request->status;

        $information = [];
        $information['key'] = $request->key;
        $information['secret'] = $request->secret;
        $information['text'] = "Pay via your Credit account.";

        $stripe->information = json_encode($information);

        $stripe->save();

        $request->session()->flash('success', "Stripe informations updated successfully!");

        return back();
    }

    public function paystackUpdate(Request $request)
    {
        $paystack = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paystack']])->first();
        $paystack->status = $request->status;

        $information = [];
        $information['key'] = $request->key;
        $information['email'] = $request->email;
        $information['text'] = "Pay via your Paystack account.";

        $paystack->information = json_encode($information);

        $paystack->save();

        $request->session()->flash('success', "Paystack informations updated successfully!");

        return back();
    }

    public function paytmUpdate(Request $request)
    {
        $paytm = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paytm']])->first();
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


        $request->session()->flash('success', "Paytm informations updated successfully!");

        return back();
    }

    public function flutterwaveUpdate(Request $request)
    {
        $flutterwave = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'flutterwave']])->first();
        $flutterwave->status = $request->status;

        $information = [];
        $information['public_key'] = $request->public_key;
        $information['secret_key'] = $request->secret_key;
        $information['text'] = "Pay via your Flutterwave account.";

        $flutterwave->information = json_encode($information);

        $flutterwave->save();

        $request->session()->flash('success', "Flutterwave informations updated successfully!");

        return back();
    }

    public function instamojoUpdate(Request $request)
    {
        $instamojo = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'instamojo']])->first();
        $instamojo->status = $request->status;

        $information = [];
        $information['key'] = $request->key;
        $information['token'] = $request->token;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your Instamojo account.";

        $instamojo->information = json_encode($information);

        $instamojo->save();

        $request->session()->flash('success', "Instamojo informations updated successfully!");

        return back();
    }

    public function mollieUpdate(Request $request)
    {
        $mollie = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'mollie']])->first();
        $mollie->status = $request->status;

        $information = [];
        $information['key'] = $request->key;
        $information['text'] = "Pay via your Mollie Payment account.";

        $mollie->information = json_encode($information);

        $mollie->save();

        $arr = ['MOLLIE_KEY' => $request->key];
        setEnvironmentValue($arr);
        \Artisan::call('config:clear');

        $request->session()->flash('success', "Mollie Payment informations updated successfully!");

        return back();
    }

    public function razorpayUpdate(Request $request)
    {
        $razorpay = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'razorpay']])->first();
        $razorpay->status = $request->status;

        $information = [];
        $information['key'] = $request->key;
        $information['secret'] = $request->secret;
        $information['text'] = "Pay via your Razorpay account.";

        $razorpay->information = json_encode($information);

        $razorpay->save();

        $request->session()->flash('success', "Razorpay informations updated successfully!");

        return back();
    }

    public function anetUpdate(Request $request)
    {
        $anet = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'authorize.net']])->first();
        $anet->status = $request->status;

        $information = [];
        $information['login_id'] = $request->login_id;
        $information['transaction_key'] = $request->transaction_key;
        $information['public_key'] = $request->public_key;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your Authorize.net account.";

        $anet->information = json_encode($information);

        $anet->save();

        $request->session()->flash('success', "Authorize.net informations updated successfully!");

        return back();
    }

    public function mercadopagoUpdate(Request $request)
    {
        $mercadopago = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'mercadopago']])->first();
        $mercadopago->status = $request->status;

        $information = [];
        $information['token'] = $request->token;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your Mercado Pago account.";

        $mercadopago->information = json_encode($information);

        $mercadopago->save();

        $request->session()->flash('success', "Mercado Pago informations updated successfully!");

        return back();
    }

    public function offline(Request $request)
    {
        $data['ogateways'] = UserOfflineGateway::where('user_id', Auth::guard('web')->user()->id)->orderBy('id', 'DESC')->get();
        $data['shopsettings'] = UserShopSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        return view('user.gateways.offline.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'short_description' => 'nullable',
            'serial_number' => 'required|integer',
            'is_receipt' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $in = $request->all();
        $in['user_id'] = Auth::guard('web')->user()->id;
        UserOfflineGateway::create($in);
        Session::flash('success', 'Gateway added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'short_description' => 'nullable',
            'serial_number' => 'required|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $in = $request->except('_token', 'ogateway_id');
        UserOfflineGateway::where('id', $request->ogateway_id)->update($in);
        Session::flash('success', 'Gateway updated successfully!');
        return "success";
    }
    public function status(Request $request)
    {
        $og = UserOfflineGateway::find($request->ogateway_id);
        if (!empty($request->type) && $request->type == 'item') {
            $og->item_checkout_status = $request->item_checkout_status;
        }
        $og->save();
        Session::flash('success', 'Gateway status changed successfully!');
        return back();
    }

    public function delete(Request $request)
    {
        $ogateway = UserOfflineGateway::findOrFail($request->ogateway_id);
        $ogateway->delete();
        Session::flash('success', 'Gateway deleted successfully!');
        return back();
    }
}
