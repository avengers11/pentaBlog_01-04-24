<?php

namespace App\Http\Controllers\Front;

use Academe\AuthorizeNet\Request\Model\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\UserItem;
use App\Models\User\UserCoupon;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use App\Models\User\UserItemContent;
use App\Models\User\UserShopSetting;
use Illuminate\Support\Facades\Auth;
use App\Models\User\CustomerWishList;
use App\Models\User\UserOfflineGateway;
use App\Models\User\UserPaymentGeteway;
use App\Models\User\UserShippingCharge;
use Illuminate\Support\Facades\Session;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer')->except('addToCart', 'cart', 'addToWishlist', 'cartitemremove', 'updatecart');
    }
    public function addToCart($domain, $id)
    {
        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $cart = Session::get('cart');
        $data = explode(',,,', $id);
        $id = (int)$data[0];
        $qty = (int)$data[1];
        $total = (float)$data[2];
        $variant = json_decode($data[3], true);
        $item = UserItem::findOrFail($id);
        // validations
        $item_content =  UserItemContent::where('item_id', $id)->where('language_id', $userCurrentLang->id)->first();
        if ($qty < 1) {
            return response()->json(['error' => 'Quanty must be 1 or more than 1.']);
        }
        $pvariant = $item_content->variations;
        if (count($pvariant) > 0 && empty($variant)) {
            return response()->json(['error' => 'You must select a variant.']);
        }
        if (!$item) {
            abort(404);
        }
        $ckey = uniqid();
        // if cart is empty then this the first product
        if (!$cart) {
            $cart = [
                $ckey => [
                    "id" => $id,
                    "name" => $item_content->title,
                    "qty" => (int)$qty,
                    "variations" => $variant,
                    "product_price" => (float)$item->current_price,
                    "total" => $total,
                    "slug" => $item_content->slug
                ]
            ];
            Session::put('cart', $cart);
            return response()->json(['message' => 'Item added to cart successfully!']);
        }

        // if cart not empty then check if this product (with same variation) exist then increment quantity
        foreach ($cart as $key => $cartItem) {
            if ($cartItem["id"] == $id && $variant == $cartItem["variations"]) {
                $cart[$key]['qty'] = (int)$cart[$key]['qty'] + $qty;
                $cart[$key]['total'] = (float)$cart[$key]['total'] + $total;
                Session::put('cart', $cart);
                return response()->json(['message' => 'Item added to cart successfully!']);
            }
        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$ckey] = [
            "id" => $id,
            "name" => $item_content->title,
            "qty" => (int)$qty,
            "variations" => $variant,
            "product_price" => (float)$item->current_price,
            "total" => $total,
            "slug" => $item_content->slug
        ];
        Session::put('cart', $cart);
        return response()->json(['message' => 'Item added to cart successfully!']);
    }


    public function addToWishlist($domain, $id)
    {

        if (!Auth::guard('customer')->check()) {
            return response()->json(['error' => 'Customer Login required']);
        }

        $user = getUser();
        $wishlist = CustomerWishList::where('customer_id', Auth::guard('customer')->user()->id)->where('item_id', $id)->first();
        $data = explode(',,,', $id);
        $id = (int)$data[0];
        // if wishlist is empty then this the first Item for this user
        if (!$wishlist) {
            CustomerWishList::create([
                'customer_id' => Auth::guard('customer')->user()->id,
                'item_id' => $id,
            ]);
            return response()->json(['message' => 'Item added to wishlist successfully!']);
        } else {
            $wishlist->delete();
            return response()->json(['message' => 'Item removed from wishlist successfully!']);
        }
    }


    public function cart($domain)
    {

        $user = getUser();
        $userShop = UserShopSetting::where('user_id', $user->id)->first();
        if ($userShop->is_shop == 0) {
            return back();
        }
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        if (Session::has('cart')) {
            $data['cart'] = Session::get('cart');
        } else {
            $data['cart'] = null;
        }
        $userBs = BasicSetting::where('user_id', $user->id)->first();
        $version = $userBs->theme_version;
        if ($version == 'dark') {
            $version = 'default';
        }
        $data['version'] = $version;
        $data['bgImg'] = $this->getUserBreadcrumb($user->id);
        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang, $user->id);
        return view('user-front.common.cart', $data);
    }
    public function cartitemremove($doamin, $uid)
    {
        if ($uid) {
            $cart = Session::get('cart');
            if (isset($cart[$uid])) {
                unset($cart[$uid]);
                Session::put('cart', $cart);
            }
            $total = 0;
            $count = 0;
            foreach ($cart as $i) {
                $total += $i['product_price'] * $i['qty'];
                $count += $i['qty'];
            }
            $total = round($total, 2);
            return response()->json(['message' => 'Item removed successfully', 'count' => $count, 'total' => $total]);
        }
    }
    public function updatecart(Request $request)
    {
        $cart = Session::get('cart');

        if(is_null($cart) || count($cart) <= 0){
            return response()->json(['message' => 'Cart is Empty.']);
        }
        $qtys = $request->qty;
        $i = 0;
        foreach ($cart as $cartKey => $cartItem) {
            $total = 0;
            $cart[$cartKey]["qty"] = (int)$qtys[$i];
            // calculate total
            if (is_array($cartItem["variations"])) {
                foreach ($cartItem["variations"] as $varKey => $variant) {
                    $total += (float)$variant["price"];
                }
            }
            $total += (float)$cartItem["product_price"];
            $total = $total * $qtys[$i];
            // save total in the cart item
            $cart[$cartKey]["total"] = $total;
            $i++;
        }
        Session::put('cart', $cart);
        return response()->json(['message' => 'Cart Update Successfully.']);
    }
    public function checkout($doamin, Request $request)
    {

        $user = getUser();
        $userShop = UserShopSetting::where('user_id', $user->id)->first();
        if ($userShop->is_shop == 0) {
            return back();
        }
        $data['userShopSetting'] = $userShop;
        if (!Auth::check()) {
            Session::put('link', route('front.user.checkout', getParam()));
            return redirect(route('customer.login', ['redirected' => 'checkout']));
        }


        if (!Session::get('cart')) {
            Session::flash('error', 'Your cart is empty.');
            return back();
        }
        $data['bgImg'] = $this->getUserBreadcrumb($user->id);
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        if (Session::has('cart')) {
            $data['cart'] = Session::get('cart');
        } else {
            $data['cart'] = null;
        }
        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang, $user->id);
        $data['shippings'] = UserShippingCharge::where('user_id', $user->id)->where('language_id', $userCurrentLang->id)->get();
        $data['offlines'] = UserOfflineGateway::where('user_id', $user->id)->get();
        $data['payment_gateways'] = UserPaymentGeteway::where('user_id', $user->id)->where('status', 1)->get();
        $data['discount'] = session()->has('user_coupon') && !empty(session()->get('user_coupon')) ? session()->get('user_coupon') : 0;
        // determining the theme version selected
        $userBs = BasicSetting::where('user_id', $user->id)->first();
        $version = $userBs->theme_version;
        if ($version == 'dark') {
            $version = 'default';
        }
        $data['version'] = $version;
        return view('user-front.common.checkout', $data);
    }
    public function coupon(Request $request)
    {
        $user = getUser();
        $coupon = UserCoupon::where('code', $request->coupon)->where('user_id', $user->id);
        $userBs = BasicSetting::where('user_id', $user->id)->first();
        if ($coupon->count() == 0) {
            return response()->json(['status' => 'error', 'message' => "Coupon is not valid"]);
        } else {
            $coupon = $coupon->first();
            if (cartTotal() < $coupon->minimum_spend) {
                return response()->json(['status' => 'error', 'message' => "Cart Total must be minimum " . $coupon->minimum_spend . " " . $userBs->base_currency_text]);
            }
            $start = Carbon::parse($coupon->start_date);
            $end = Carbon::parse($coupon->end_date);
            $today = Carbon::now();
            // return response()->json($today->greaterThanOrEqualTo($start));
            // if coupon is active
            if ($today->greaterThanOrEqualTo($start) && $today->lessThan($end)) {
                $cartTotal = cartTotal();
                $value = $coupon->value;
                $type = $coupon->type;

                if ($type == 'fixed') {
                    if ($value > cartTotal()) {
                        return response()->json(['status' => 'error', 'message' => "Coupon discount is greater than cart total"]);
                    }
                    $couponAmount = $value;
                } else {
                    $couponAmount = ($cartTotal * $value) / 100;
                }
                session()->put('user_coupon', round($couponAmount, 2));

                return response()->json(['status' => 'success', 'message' => "Coupon applied successfully"]);
            } else {
                return response()->json(['status' => 'error', 'message' => "Coupon is not valid"]);
            }
        }
    }
}
