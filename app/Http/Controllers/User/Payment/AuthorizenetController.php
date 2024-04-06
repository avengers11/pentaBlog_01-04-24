<?php

namespace App\Http\Controllers\User\Payment;

use Omnipay\Omnipay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;


class AuthorizenetController extends Controller
{
    public $gateway;

    public function __construct()
    {
        $data = UserPaymentGeteway::whereKeyword('authorize.net')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        $this->gateway = Omnipay::create('AuthorizeNetApi_Api');
        $this->gateway->setAuthName($paydata['login_id']);
        $this->gateway->setTransactionKey($paydata['transaction_key']);
        if ($paydata['sandbox_check'] == 1) {
            $this->gateway->setTestMode(true);
        }
    }

    public function paymentProcess(Request $request, $_amount, $_cancel_url, $_title, $be)
    {
        if ($request->opaqueDataDescriptor && $request->opaqueDataValue) {
            Session::put('user_request', $request->all());
            // Generate a unique merchant site transaction ID.
            $transactionId = rand(100000000, 999999999);
            $response = $this->gateway->authorize([
                'amount' => $_amount,
                'currency' => $be->base_currency_text,
                'transactionId' => $transactionId,
                'opaqueDataDescriptor' => $request->opaqueDataDescriptor,
                'opaqueDataValue' => $request->opaqueDataValue,
            ])->send();

            $transactionReference = $response->getTransactionReference();
            $response = $this->gateway->capture([
                'amount' => $_amount,
                'currency' => $be->base_currency_text,
                'transactionReference' => $transactionReference,
            ])->send();
            $transaction_id = $response->getTransactionReference();
            // Insert transaction data into the database
            $requestData = Session::get('user_request');
            $transaction_id = $transaction_id;
            $txnId = $transaction_id;
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
        }else{
             return redirect()->back()->with('error', __('Something went Problem.'))->withInput($request->all());
        }
    }

    public function cancelPayment()
    {
        session()->flash('warning', __('cancel_payment'));
        return redirect()->route('front.user.pricing', getParam());
    }
}
