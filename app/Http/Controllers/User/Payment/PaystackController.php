<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;

class PaystackController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Redirect the User to Paystack Payment Page
     * @return
     */
    public function paymentProcess(Request $request, $_amount, $_email, $_success_url, $bex)
    {

       
        $data = UserPaymentGeteway::whereKeyword('paystack')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();

        $secret_key = $paydata['key'];

        $curl = curl_init();
        $callback_url = $_success_url; // url to go to after payment

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount' => $_amount,
                'email' => $_email,
                'callback_url' => $callback_url
            ]),
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer " . $secret_key, //replace this with your own test key
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        if ($err) {
            return redirect()->back()->with('error', $err);
        }
        $tranx = json_decode($response, true);
        Session::put('user_request', $request->all());

        if (!$tranx['status']) {
            return redirect()->back()->with("error", $tranx['message']);
        }
        return redirect($tranx['data']['authorization_url']);
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user = getUser();
        $be = BasicSetting::where('user_id', $user->id)->firstorFail();
        if ($request['trxref'] === $request['reference']) {
            $txnId = UserPermissionHelper::uniqidReal(8);
            $chargeId = $request->paymentId;
            $order = $this->saveOrder($requestData, $txnId, $chargeId, 'Completed');
            $order_id = $order->id;
            $this->saveOrderedItems($order_id);
            $this->sendMails($order);
            session()->flash('success', __('successful_payment'));
            Session::forget('user_request');
            Session::forget('user_amount');
            Session::forget('user_paypal_payment_id');
            return redirect()->route('customer.success.page', getParam());
        } else {
            session()->flash('warning', __('cancel_payment'));
            return redirect()->route('front.user.pricing', getParam());
        }
    }
}
