<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use Mollie\Laravel\Facades\Mollie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;

class MollieController extends Controller
{
    public function __construct()
    {
        $data = UserPaymentGeteway::whereKeyword('mollie')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        Config::set('mollie.key', $paydata['key']);
    }

    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bex)
    {

        $notify_url = $_success_url;
        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => $bex->base_currency_text,
                'value' => '' . sprintf('%0.2f', $_amount) . '', // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'description' => $_title,
            'redirectUrl' => $notify_url,
        ]);

        /** add payment ID to session **/
        Session::put('user_request', $request->all());
        Session::put('user_payment_id', $payment->id);
        Session::put('user_success_url', $_success_url);

        $payment = Mollie::api()->payments()->get($payment->id);

        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user  = getUser();
        $bs = BasicSetting::where('user_id', $user->id)->firstorFail();
        $requestData['user_id'] = Auth::guard('customer')->user()->id;
        $cancel_url = Session::get('cancel_url');
        $payment_id = Session::get('user_payment_id');
        /** Get the payment ID before session clear **/
        $payment = Mollie::api()->payments()->get($payment_id);
        if ($payment->status == 'paid') {
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
        return redirect($cancel_url);
    }
    public function cancelPayment()
    {
        session()->flash('warning', __('cancel_payment'));
        return redirect()->route('front.user.pricing', getParam());
    }
}
