<?php

namespace App\Http\Controllers\User;

use Exception;
use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\BasicExtended;
use App\Models\User\UserOrder;
use PHPMailer\PHPMailer\PHPMailer;
use App\Exports\PorductOrderExport;
use App\Exports\ProductOrderExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User\UserOfflineGateway;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ItemOrderController extends Controller
{
    public function all(Request $request)
    {
        $search = $request->search;
        $data['orders'] =
            UserOrder::when($search, function ($query, $search) {
                return $query->where('order_number', $search);
            })
            ->orderBy('id', 'DESC')->paginate(10);
        return view('user.item.order.index', $data);
    }

    public function pending(Request $request)
    {
        $search = $request->search;
        $data['orders'] = UserOrder::when($search, function ($query, $search) {
            return $query->where('order_number', $search);
        })
            ->where('order_status', 'pending')->orderBy('id', 'DESC')->paginate(10);
        return view('user.item.order.index', $data);
    }

    public function processing(Request $request)
    {
        $search = $request->search;
        $data['orders'] = UserOrder::where('order_status', 'processing')
            ->when($search, function ($query, $search) {
                return $query->where('order_number', $search);
            })
            ->orderBy('id', 'DESC')->paginate(10);
        return view('user.item.order.index', $data);
    }

    public function completed(Request $request)
    {
        $search = $request->search;
        $data['orders'] = UserOrder::where('order_status', 'completed')->when($search, function ($query, $search) {
            return $query->where('order_number', $search);
        })
            ->orderBy('id', 'DESC')->paginate(10);
        return view('user.item.order.index', $data);
    }

    public function rejected(Request $request)
    {
        $search = $request->search;
        $data['orders'] = UserOrder::where('order_status', 'rejected')->when($search, function ($query, $search) {
            return $query->where('order_number', $search);
        })
            ->orderBy('id', 'DESC')->paginate(10);
        return view('user.item.order.index', $data);
    }

    public function status(Request $request)
    {

        $po = UserOrder::find($request->order_id);

        $po->order_status = $request->order_status;
        $po->save();

        $user = Customer::findOrFail($po->customer_id);

        $be = BasicExtended::first();

        $sub = 'Order Status Update';

        $to = $user->email;
        // Send Mail to Buyer
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($user->email, $user->fname);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $user->fname . '</strong>,<br/>Your order status is ' . $request->order_status . '.<br/>Thank you.';
                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        } else {
            try {

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($user->email, $user->fname);


                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $user->fname . '</strong>,<br/>Your order status is ' . $request->order_status . '.<br/>Thank you.';

                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        }


        Session::flash('success', 'Order status changed successfully!');
        return back();
    }

    public function mail(Request $request)
    {
        $rules = [
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $be = BasicExtended::first();
        $from = $be->from_mail;
        $sub = $request->subject;
        $msg = $request->message;
        $to = $request->email;
        // Mail::to($to)->send(new ContactMail($from, $sub, $msg));
        // Send Mail
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;
                //Recipients
                $mail->setFrom($from);
                $mail->addAddress($to);
                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = $msg;

                $mail->send();
            } catch (Exception $e) {
            }
        } else {
            try {
                //Recipients
                $mail->setFrom($from);
                $mail->addAddress($to);
                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = $msg;
                $mail->send();
            } catch (Exception $e) {
            }
        }

        Session::flash('success', 'Mail sent successfully!');
        return "success";
    }

    public function details($id)
    {
        $order = UserOrder::findOrFail($id);
        return view('user.item.order.details', compact('order'));
    }


    public function bulkOrderDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $order = UserOrder::findOrFail($id);
            @unlink(public_path('assets/front/invoices/' . $order->invoice_number));
            @unlink(public_path('assets/front/receipt/' . $order->receipt));
            foreach ($order->orderitems as $item) {
                $item->delete();
            }
            $order->delete();
        }

        Session::flash('success', 'Orders deleted successfully!');
        return "success";
    }

    public function orderDelete(Request $request)
    {
        $order = UserOrder::findOrFail($request->order_id);
        @unlink(public_path('assets/front/invoices/' . $order->invoice_number));
        @unlink(public_path('assets/front/receipt/' . $order->receipt));
        foreach ($order->orderitems as $item) {
            $item->delete();
        }
        $order->delete();

        Session::flash('success', 'Item order deleted successfully!');
        return back();
    }

    public function report(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $paymentStatus = $request->payment_status;
        $orderStatus = $request->order_status;
        $paymentMethod = $request->payment_method;

        if (!empty($fromDate) && !empty($toDate)) {
            $orders = UserOrder::when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
            })->when($toDate, function ($query, $toDate) {
                return $query->whereDate('created_at', '<=', Carbon::parse($toDate));
            })->when($paymentMethod, function ($query, $paymentMethod) {
                return $query->where('method', $paymentMethod);
            })->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })->when($orderStatus, function ($query, $orderStatus) {
                return $query->where('order_status', $orderStatus);
            })->select('order_number', 'billing_fname', 'billing_email', 'billing_number', 'billing_city', 'billing_country', 'shpping_fname', 'shpping_email', 'shpping_number', 'shpping_city', 'shpping_country', 'method', 'shipping_method', 'cart_total', 'discount', 'tax', 'shipping_charge', 'total', 'created_at', 'payment_status', 'order_status')
                ->orderBy('id', 'DESC');

            Session::put('item_orders_report', $orders->get());
            $data['orders'] = $orders->paginate(10);
        } else {
            Session::put('item_orders_report', []);
            $data['orders'] = [];
        }

        $data['onPms'] = UserPaymentGeteway::where('status', 1)->get();
        $data['offPms'] = UserOfflineGateway::where('item_checkout_status', 1)->get();


        return view('user.item.order.report', $data);
    }

    public function paymentStatus(Request $request)
    {
        $po = UserOrder::find($request->order_id);
        $po->payment_status = $request->payment_status;

        $user = Customer::findOrFail($po->customer_id);
        $be = BasicExtended::first();
        $sub = 'Payment Status Updated';
        $po->save();

        // Send Mail to Buyer
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($user->email, $user->fname);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $user->username . '</strong>,<br/>
                 Your Payment status is ' . $request->payment_status . '.<br/>
                 Your Order number is ' . $po->order_number . '.<br/>
                 See Orders: <a href="' . route('customer.orders-details', ['id' => $po->id, Auth::guard('web')->user()->username]) . '">' . route('customer.orders-details', ['id' => $po->id, Auth::guard('web')->user()->username]) . '"</a>" <br/>
                 Thank you.';
                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        } else {
            try {
                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($user->email, $user->fname);
                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $user->username . '</strong>,<br/>
                 Your Payment status is ' . $request->payment_status . '.<br/>
                 Your Order number is ' . $po->order_number . '.<br/>
                 See Orders: <a href="' . route('customer.orders-details', ['id' => $po->id, Auth::guard('web')->user()->username]) . '">' . route('customer.orders-details', ['id' => $po->id, Auth::guard('web')->user()->username]) . '"</a>" <br/>
                 Thank you.';
                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        }

        Session::flash('success', 'Payment status changed successfully!');
        return back();
    }



    public function exportReport()
    {
        $orders = Session::get('item_orders_report');
        if (empty($orders) || count($orders) == 0) {
            Session::flash('warning', 'There are no orders to export');
            return back();
        }
        return Excel::download(new ProductOrderExport($orders), 'product-orders.csv');
    }

}
