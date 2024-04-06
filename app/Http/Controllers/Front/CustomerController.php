<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\User\Language;
use App\Models\User\UserOrder;
use App\Http\Helpers\MegaMailer;
use App\Models\User\BasicSetting;
use App\Models\User\BookmarkPost;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\Http\Controllers\Controller;
use App\Models\User\UserShopSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User\CustomerWishList;
use App\Models\User\UserItem;
use App\Models\User\UserOfflineGateway;
use App\Models\User\UserOrderItem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerController extends Controller
{

    public function __construct()
    {
        $user = getUser();
        $userBs = BasicSetting::where('user_id', $user->id)->first();

        Config::set('captcha.sitekey', $userBs->google_recaptcha_site_key);
        Config::set('captcha.secret', $userBs->google_recaptcha_secret_key);
    }


    public function login(Request $request, $domain)
    {
        $user = getUser();
        // when user have to redirect to check out page after login.
        if (
            $request->input('redirect_path') == 'checkout' &&
            !empty($request->input('digital_item'))
        ) {
            $hasDigitalProduct = $request->input('digital_item');
        }

        if (isset($hasDigitalProduct)) {
            $queryResult['digitalStatus'] = $hasDigitalProduct;
        }

        /**
         * when user have to redirect to product details page after login.
         * or, when user have to redirect to previous page for bookmark a post after login.
         */
        if (
            $request->input('redirect_path') == 'product-details' ||
            !empty($request->input('redirect_for'))
        ) {
            $request->session()->put('redirectTo', url()->previous());
        }

        $queryResult['bgImg'] = $this->getUserBreadcrumb($user->id);

        return view('user-front.user.login', $queryResult);
    }

    public function loginSubmit(Request $request, $domain)
    {


        // at first, get the url from session which will be redirected after login
        if ($request->session()->has('redirectTo')) {
            $redirectURL = $request->session()->get('redirectTo');
        } else {
            $redirectURL = null;
        }

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $ubs  = BasicSetting::where('user_id', getUser()->id)->first();
        $messages = [];
        if ($ubs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
            $messages = [
                'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
                'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
            ];
        }

        $request->validate($rules, $messages);


        // get the email and password which has provided by the user
        $credentials = $request->only('email', 'password', 'user_id');

        // login attempt
        if (Auth::guard('customer')->attempt($credentials)) {
            $authUser = Auth::guard('customer')->user();

            // first, check whether the user's email address verified or not
            if ($authUser->email_verified_at == null) {
                $request->session()->flash('error', 'Please, verify your email address.');

                // logout auth user as condition not satisfied
                Auth::guard('customer')->logout();

                return redirect()->back();
            }

            // second, check whether the user's account is active or not
            if ($authUser->status == 0) {
                $request->session()->flash('error', 'Sorry, your account has been deactivated.');

                // logout auth user as condition not satisfied
                Auth::guard('customer')->logout();

                return redirect()->back();
            }

            // otherwise, redirect auth user to next url
            if ($redirectURL == null) {
                return redirect()->route('customer.dashboard', getParam());
            } else {
                // before, redirect to next url forget the session value
                $request->session()->forget('redirectTo');

                return redirect($redirectURL);
            }
        } else {
            $request->session()->flash('error', 'The provided credentials do not match our records!');

            return redirect()->back();
        }
    }
    public function contact(Request $request, $domain)
    {

        $data['user'] = getUser();

        $language = $this->getUserCurrentLanguage($data['user']->id);

        $queryResult['pageHeading'] = $this->getUserPageHeading($language, $data['user']->id);

        $queryResult['bgImg'] = $this->getUserBreadcrumb($data['user']->id);

        $queryResult['mapInfo'] = DB::table('user_basic_settings')
            ->where('user_id', $data['user']->id)
            ->select('latitude', 'longitude')
            ->first();

        return view('user-front.common.contact', $queryResult);
    }

    public function contactMessage(Request $request, $domain)
    {
        $ubs  = BasicSetting::where('user_id', getUser()->id)->first();
        $messages = [];
        $rules = [
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required'
        ];
        if ($ubs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
            $messages = [
                'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
                'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
            ];
        }
        if (!empty($request->type) && $request->type == 'vcard') {
            $rules['fullname'] = 'required';
        } else {
            $rules['first_name'] = 'required';
            $rules['last_name'] = 'required';
        }
        $request->validate($rules, $messages);
        if (!empty($request->type) && $request->type == 'vcard') {
            $data['toMail'] = $request->to_mail;
            $data['toName'] = $request->to_name;
            $data['fullname'] = $request->fullname;
        } else {
            $toUser = getUser();
            $data['toMail'] = $toUser->email;
            $data['toName'] = $toUser->username;
            $data['fullname'] = $request->first_name . ' ' . $request->last_name;
        }
        $data['subject'] = $request->subject;
        $data['body'] = "<div>$request->message</div><br>
                         <strong>For further contact with the enquirer please use the below information:</strong><br>
                         <strong>Enquirer Name:</strong>" .  $data['fullname'] . "<br>
                         <strong>Enquirer Mail:</strong> $request->email <br>
                         ";
        $data['email'] = $request->email;

        $mailer = new MegaMailer();
        $mailer->mailContactMessage($data);
        Session::flash('success', 'Mail sent successfully');
        return back();
    }

    public function forgetPassword($domain)
    {
        $user = getUser();
        return view('user-front.user.forget-password', ['bgImg' => $this->getUserBreadcrumb($user->id)]);
    }
    public function sendMail(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email:rfc,dns',
            ]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Customer::where('email', $request->email)->first();

        // first, get the mail template information from db
        $mailTemplate = EmailTemplate::where('email_type', 'reset_password')->first();
        $mailSubject = $mailTemplate->email_subject;
        $mailBody = $mailTemplate->email_body;

        // second, send a password reset link to user via email
        $info = DB::table('basic_extendeds')
            ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();
        $websiteInfo = DB::table('basic_settings')
            ->select('website_title')
            ->first();

        $name = $user->first_name . ' ' . $user->last_name;

        $link = '<a href=' . route('customer.reset_password', getParam()) . '>Click Here</a>';

        $mailBody = str_replace('{customer_name}', $name, $mailBody);
        $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $websiteInfo->website_title, $mailBody);

        // initialize a new mail
        $mail = new PHPMailer(true);

        // if smtp status == 1, then set some value for PHPMailer
        if ($info->is_smtp == 1) {
            $mail->isSMTP();
            $mail->Host       = $info->smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $info->smtp_username;
            $mail->Password   = $info->smtp_password;

            if ($info->encryption == 'TLS') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port = $info->smtp_port;
        }

        // finally, add other information and send the mail
        try {
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress($request->email);

            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->Body = $mailBody;

            $mail->send();

            $request->session()->flash('success', 'A mail has been sent to your email address.');
        } catch (Exception $e) {
            $request->session()->flash('error', 'Mail could not be sent!');
        }

        // store user email in session to use it later
        $request->session()->put('userEmail', $user->email);

        return redirect()->back();
    }


    public function resetPassword($domain)
    {
        $user = getUser();
        return view('user-front.user.reset-password', ['bgImg' => $this->getUserBreadcrumb($user->id)]);
    }

    public function resetPasswordSubmit(Request $request, $domain)
    {
        $author = getUser();
        // get the user email from session
        $emailAddress = $request->session()->get('userEmail');

        $rules = [
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation failed.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = Customer::where('email', $emailAddress)->where('user_id', $author->id)->first();

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        $request->session()->flash('success', 'Password updated successfully.');

        return redirect()->route('customer.login', getParam());
    }

    public function signup($domain)
    {
        $user = getUser();

        $queryResult['bgImg'] = $this->getUserBreadcrumb($user->id);

        return view('user-front.user.signup', $queryResult);
    }

    public function signupSubmit(Request $request, $domain)
    {
        $user = getUser();
        $messages = [];
        $rules = [
            'username' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($user) {
                    if (Customer::where('username', $value)->where('user_id', $user->id)->count() > 0) {
                        $fail('Username has already been taken');
                    }
                }
            ],
            'email' => ['required', 'email', 'max:255', function ($attribute, $value, $fail) use ($user) {
                if (Customer::where('email', $value)->where('user_id', $user->id)->count() > 0) {
                    $fail('Email has already been taken');
                }
            }],
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];
        $ubs  = BasicSetting::where('user_id', $user->id)->first();
        if ($ubs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
            $messages = [
                'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
                'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
            ];
        }

        $request->validate($rules, $messages);

        $customer = new Customer;
        $customer->username = $request->username;
        $customer->email = $request->email;
        $customer->user_id = $user->id;
        $customer->password = Hash::make($request->password);

        // first, generate a random string
        $randStr = Str::random(20);

        // second, generate a token
        $token = md5($randStr . $request->username . $request->email);

        $customer->verification_token = $token;
        $customer->save();

        // send a mail to user for verify his/her email address
        $this->sendVerificationMail($request, $token);

        return redirect()
            ->back()
            ->with('sendmail', 'We need to verify your email address. We have sent an email to  ' . $request->email . ' to verify your email address. Please click link in that email to continue.');
    }


    public function sendVerificationMail(Request $request, $token)
    {
        // first get the mail template information from db
        $mailTemplate = EmailTemplate::where('email_type', 'email_verification')->first();
        $mailSubject = $mailTemplate->email_subject;
        $mailBody = $mailTemplate->email_body;

        // second get the website title & mail's smtp information from db
        $info = DB::table('basic_extendeds')
            ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();
        $websiteInfo = DB::table('basic_settings')
            ->select('website_title')
            ->first();

        $link = '<a href=' . route('customer.signup.verify', ['token' => $token, getParam()]) . '>Click Here</a>';

        // replace template's curly-brace string with actual data
        $mailBody = str_replace('{customer_name}', $request->username, $mailBody);
        $mailBody = str_replace('{verification_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $websiteInfo->website_title, $mailBody);

        $userInfo = BasicSetting::where('user_id', Auth::id())->select('email', 'from_name')->first();
        $email = $userInfo->email ?? Auth::user()->email;
        $name = $userInfo->from_name ?? Auth::user()->username;


        // initialize a new mail
        $mail = new PHPMailer(true);

        // if smtp status == 1, then set some value for PHPMailer
        if ($info->is_smtp == 1) {
            $mail->isSMTP();
            $mail->Host       = $info->smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $info->smtp_username;
            $mail->Password   = $info->smtp_password;

            if ($info->encryption == 'TLS') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port = $info->smtp_port;
        }

        // finally, add other information and send the mail
        try {
            $mail->setFrom($info->from_mail, $name);
            $mail->addReplyTo($email);
            $mail->addAddress($request->email);

            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->Body = $mailBody;

            $mail->send();

            $request->session()->flash('success', 'A verification mail has been sent to your email address.');
        } catch (Exception $e) {
            $request->session()->flash('error', 'Mail could not be sent!');
        }

        return;
    }

    public function customerOrders($domain)
    {
        $user = getUser();
        $bex = UserShopSetting::where('user_id', $user->id)->first();
        if ($bex->is_shop == 0) {
            return back();
        }

        $data['bgImg'] = $this->getUserBreadcrumb($user->id);
        $data['orders'] = UserOrder::where('customer_id', Auth::guard('customer')->user()->id)->orderBy('id', 'DESC')->get();
        return view('user-front.user.order', $data);
    }

    public function orderdetails($domain, $id)
    {
        $user = getUser();
        $data['bgImg'] = $this->getUserBreadcrumb($user->id);
        $data['currentLanguage'] = $this->getUserCurrentLanguage(getUser()->id);
        $bex = UserShopSetting::where('user_id',  $user->id)->first();
        if ($bex->is_shop == 0) {
            return back();
        }
        $data['data'] = UserOrder::findOrFail($id);
        return view('user-front.user.order_details', $data);
    }

    public function customerWishlist($domain)
    {
        $user = getUser();
        $data['bgImg'] = $this->getUserBreadcrumb($user->id);
        if (session()->has('user_lang') && !empty($user)) {
            $data['language'] = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($data['language'])) {
                $data['language'] = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $data['language']->code);
            }
        } else {
            $data['language'] = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        $data['wishlist'] = CustomerWishList::where('customer_id', Auth::guard('customer')->user()->id)
            ->with('item.itemContents')
            ->orderBy('id', 'DESC')->get();
        return view('user-front.user.wishlist', $data);
    }

    public function removefromWish($domain, $id)
    {
        $data['wishlist'] = CustomerWishList::findOrFail($id)->delete();
        return response()->json(['message' => 'Item removed from wishlist successfully']);
    }

    public function onlineSuccess()
    {
        Session::forget('user_coupon');
        Session::forget('coupon_amount');
        return view('user-front.success');
    }

    public function signupVerify(Request $request, $domain, $token)
    {
        try {
            $user = Customer::where('verification_token', $token)->firstOrFail();
            // after verify user email, put "null" in the "verification token"
            $user->update([
                'email_verified_at' => date('Y-m-d H:i:s'),
                'status' => 1,
                'verification_token' => null
            ]);

            $request->session()->flash('success', 'Your email has verified.');

            // after email verification, authenticate this user
            Auth::guard('customer')->login($user);

            return redirect()->route('customer.dashboard', getParam());
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('error', 'Could not verify your email!');
            return redirect()->route('customer.signup', getParam());
        }
    }

    public function redirectToDashboard($domain)
    {
        $author = getUser();

        $queryResult['bgImg'] = $this->getUserBreadcrumb($author->id);

        $user = Auth::guard('customer')->user();

        $queryResult['authUser'] = $user;

        $queryResult['totalBookmarks'] = BookmarkPost::where('user_id', $user->id)->where('author_id', $author->id)->count();

        return view('user-front.user.dashboard', $queryResult);
    }

    public function editProfile()
    {
        $user = getUser();

        $queryResult['bgImg'] = $this->getUserBreadcrumb($user->id);

        $queryResult['authUser'] = Auth::guard('customer')->user();

        return view('user-front.user.edit-profile', $queryResult);
    }

    public function updateProfile(Request $request)
    {
        $authUser = Auth::guard('customer')->user();

        if ($request->hasFile('image')) {
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/users/' . $authUser->image));

            // second, set a name for the new image and store it to local storage
            $proPic = $request->file('image');
            $picName = time() . '.' . $proPic->getClientOriginalExtension();
            $directory = public_path('./assets/user/img/users/');

            @mkdir($directory, 0775, true);
            $proPic->move($directory, $picName);
        }

        $authUser->update($request->except('image') + [
            'image' => $request->exists('image') ? $picName : $authUser->image
        ]);

        $request->session()->flash('success', 'Your profile updated successfully.');

        return redirect()->back();
    }

    public function myBookmarks($domain)
    {
        $user = getUser();

        $queryResult['bgImg'] = $this->getUserBreadcrumb($user->id);

        $authUser = Auth::guard('customer')->user();

        $queryResult['bookmarks'] = BookmarkPost::where('user_id', $authUser->id)
            ->where('author_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        if (session()->has('user_lang') && !empty($user)) {
            $language = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($language)) {
                $language = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $language->code);
            }
        } else {
            $language = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $queryResult['language'] = $language;

        return view('user-front.user.my-bookmarks', $queryResult);
    }

    public function shippingdetails($domain)
    {
        $user = getUser();
        $bex = UserShopSetting::where('user_id', $user->id)->first();
        if ($bex->is_shop == 0) {
            return back();
        }
        $bgImg = $this->getUserBreadcrumb($user->id);
        $user = Auth::guard('customer')->user();
        return view('user-front.user.shipping_details', compact('user', 'bgImg'));
    }
    public function shippingupdate(Request $request)
    {
        $request->validate([
            "shpping_fname" => 'required',
            "shpping_lname" => 'required',
            "shpping_email" => 'required',
            "shpping_number" => 'required',
            "shpping_city" => 'required',
            "shpping_state" => 'required',
            "shpping_address" => 'required',
            "shpping_country" => 'required',
        ]);
        Auth::guard('customer')->user()->update($request->all());
        Session::flash('success', 'Shipping Details Update Successfully.');
        return back();
    }
    public function billingdetails()
    {
        $user = getUser();
        $bex = UserShopSetting::where('user_id', $user->id)->first();
        if ($bex->is_shop == 0) {
            return back();
        }
        $bgImg = $this->getUserBreadcrumb($user->id);
        Auth::guard('customer')->user();
        return view('user-front.user.billing_details', compact('user', 'bgImg'));
    }
    public function billingupdate(Request $request)
    {
        $request->validate([
            "billing_fname" => 'required',
            "billing_lname" => 'required',
            "billing_email" => 'required',
            "billing_number" => 'required',
            "billing_city" => 'required',
            "billing_state" => 'required',
            "billing_address" => 'required',
            "billing_country" => 'required',
        ]);
        Auth::guard('customer')->user()->update($request->all());
        Session::flash('success', 'Billing Details Update Successfully.');
        return back();
    }


    public function changePassword()
    {
        return view('user-front.user.change-password', ['bgImg' => $this->getUserBreadcrumb(getUser()->id)]);
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::guard('customer')->user()->password)) {
                        $fail('Your password was not updated, since the provided current password does not match.');
                    }
                }
            ],
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation failed.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::guard('customer')->user();

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        $request->session()->flash('success', 'Password updated successfully.');

        return redirect()->back();
    }

    public function logoutSubmit(Request $request, $domain)
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login', getParam());
    }
    public function digitalDownload(Request $request)
    {
        $itemid = $request->item_id;
        if ($itemid) {
            $itemId = $itemid;
            $customer = false;
        } else {
            $customer = true;
            $itemId = $request->item_id;
        }
        $item = UserItem::find($itemId);

        if ($customer) {
            $count = UserOrderItem::where('item_id', $itemId)->where('customer_id', Auth::guard('customer')->user()->id)->get();
        } else {
            $count = UserOrderItem::where('item_id', $itemId)->get();
        }
        // if the auth user didn't purchase the item
        if ($count->count() == 0) {
            return back();
        }

        $pathToFile = 'core/storage/digital_products/' . $item->download_file;
        if (file_exists($pathToFile)) {
            return response()->download($pathToFile, $item->itemContents[0]->slug . '.zip');
        } else {
            $request->session()->flash('error', "No donwloadable file exists!");
            return back();
        }
    }
    public function paymentInstruction(Request $request)
    {
        $offline = UserOfflineGateway::where('name', $request->name)
            ->select('short_description', 'instructions', 'is_receipt')
            ->first();

        return response()->json([
            'description' => $offline->short_description,
            'instructions' => $offline->instructions, 'is_receipt' => $offline->is_receipt
        ]);
    }
}
