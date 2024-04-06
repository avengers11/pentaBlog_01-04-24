<?php

namespace App\Http\Controllers\User\Payment;

use Carbon\Carbon;
use Razorpay\Api\Api;
use App\Models\Package;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Helpers\MegaMailer;
use App\Models\User\UserPackage;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Controllers\Front\CheckoutController;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Http\Controllers\Front\UserCheckoutController;

class RazorpayController extends Controller
{
    public function __construct()
    {
        $data = UserPaymentGeteway::whereKeyword('razorpay')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        $this->keyId = $paydata['key'];
        $this->keySecret = $paydata['secret'];
        $this->api = new Api($this->keyId, $this->keySecret);
    }


    public function paymentProcess(Request $request, $_amount, $_item_number, $_cancel_url, $_success_url, $_title, $_description, $bs)
    {
        $cancel_url = $_cancel_url;
        $notify_url = $_success_url;

        $orderData = [
            'receipt' => $_title,
            'amount' => $_amount * 100,
            'currency' => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $this->api->order->create($orderData);
        Session::put('user_request', $request->all());
        Session::put('user_order_payment_id', $razorpayOrder['id']);

        $displayAmount = $amount = $_amount;

        $checkout = 'automatic';

        if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true)) {
            $checkout = $_GET['checkout'];
        }

        $data = [
            "key" => $this->keyId,
            "amount" => $_amount,
            "name" => $_title,
            "description" => $_description,
            "prefill" => [
                "name" => $request->billing_fname,
                "email" => $request->billing_email,
                "contact" => $request->billing_number,
            ],
            "notes" => [
                "address" => $request->razorpay_address,
                "merchant_order_id" => $_item_number,
            ],
            "theme" => [
                "color" => "{{$bs->base_color}}"
            ],
            "order_id" => $razorpayOrder['id'],
        ];

        if ($bs->base_currency_text !== 'INR') {
            $data['display_currency'] = $bs->base_currency_text;
            $data['display_amount'] = $displayAmount;
        }

        $json = json_encode($data);
        $displayCurrency = $bs->base_currency_text;

        return view('user-front.razorpay', compact('data', 'displayCurrency', 'json', 'notify_url'));
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');

        $user = getUser();

        $be = BasicSetting::where('user_id', $user->id)->firstorFail();

        /** Get the payment ID before session clear **/
        $payment_id = Session::get('user_order_payment_id');
        $success = true;

        if (empty($request['razorpay_payment_id']) === false) {
            try {
                $attributes = array(
                    'razorpay_order_id' => $payment_id,
                    'razorpay_payment_id' => $request['razorpay_payment_id'],
                    'razorpay_signature' => $request['razorpay_signature']
                );

                $this->api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
            }
        }

        if ($success === true) {

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
        }
        return redirect()->route('front.user.pricing', getParam());
    }

    public function cancelPayment()
    {
        session()->flash('warning', __('cancel_payment'));
        return redirect()->route('front.user.pricing', getParam());
    }
}
