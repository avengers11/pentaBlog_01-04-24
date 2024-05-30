<?php

namespace App\Http\Controllers\Pentaforce;

use Exception;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\User\Follower;
use App\Models\User\Language;
use App\Models\User\Subscriber;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\BasicSetting;
use App\Models\BasicExtended;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\User\Popup;

class CommunityManagementApiController extends Controller
{
    /*
    ==============================
    Register Users
    ==============================
    */
    public function registerUser($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $users = Customer::where('user_id', $user->id)
        ->orderBy('id', 'desc')
        ->get();

        return $users;
    }
    public function registerUserEmailStatus(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $customer = Customer::where('id', $request->id)->where('user_id', $user->id)->first();

        if(empty($customer->email_verified_at)){
            $customer->update([
                'email_verified_at' => Carbon::now(),
            ]);
        }else{
            $customer->update([
                'email_verified_at' => null,
            ]);
        }

        return response()->json(['success' => 'Email status updated for ' . $user->username], 200);
    }
    public function registerUserAccountStatus(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $customer = Customer::where('id', $request->id)->where('user_id', $user->id)->first();
        if($customer->status == 1){
            $customer->update([
                'status' => 0
            ]);
        }else{
            $customer->update([
                'status' => 1
            ]);
        }

        return response()->json(['success' => "Account status updated successfully!"], 200);
    }
    public function registerUserLogin(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $customer = Customer::where('id', $request->id)->first();
        $loginUser = $customer->user;
        if ($customer) {
            Auth::guard('customer')->login($customer, true);
            return response()->json(['success' => "You have Successfully loggedin!", "status" => true, "route" => route('customer.dashboard',$user->username)], 200);
        }

        return response()->json(['warning' => "Opps You provide Invalid Credentials!", "status" => false], 200);
    }
    public function registerUserDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $customer = Customer::where('id', $request->id)->where('user_id', $user->id)->firstOrFail();
        if ($customer->bookmarkList()->count() > 0) {
            $customer->bookmarkList()->delete();
        }
        $customer->delete();

        return response()->json(['success' => "User info deleted successfully!"], 200);
    }
    public function registerUserUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $customer = Customer::where('id', $request->id)->where('user_id', $user->id)->first();
        return $customer;
    }
    public function registerUserPassword(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
        'new_password' => 'required|confirmed',
        'new_password_confirmation' => 'required'
        ];
        $messages = [
        'new_password.confirmed' => 'Password confirmation does not match.',
        'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $user = Customer::where('id', $request->id)->where('user_id', $user->id)->firstOrFail();

        $user->update([
        'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['success' => "Password updated successfully!"], 200);
    }



    /*
    ==============================
    subscriber
    ==============================
    */
    public function subscriber($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data['subscs'] = Subscriber::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        return $data;
    }
    public function subscriberDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        Subscriber::where('id', $request->subscriber_id)->where('user_id', $user->id)->delete();
        return response()->json(['success' => "Subscriber deleted successfully!"], 200);
    }


    /*
    ==============================
    follow
    ==============================
    */
    public function follow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $data['follower'] = [];
        $followerListIds = Follower::query()->where('following_id',$user->id)->pluck('follower_id');
        if(count($followerListIds) > 0){
            $data['follower'] = User::whereIn('id',$followerListIds)->get();
        }

        $data['following'] = [];
        $followingListIds = Follower::query()->where('follower_id', $user->id)->pluck('following_id');
        if (count($followingListIds) > 0) {
            $data['following'] = User::whereIn('id',$followingListIds)->get();
        }
        $data['language'] = Language::where('user_id', $user->id)->where('is_default', 1)->first();


        return $data;
    }
    public function unfollow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $followCheck = Follower::query()
        ->where([
            ['follower_id', $user->id],
            ['following_id', $request->id],
        ])->first();
        if(!is_null($followCheck)){
           $followCheck->delete();
            return response()->json(['success' => "You have unfollowed successfully!"], 200);
        }else{
            return response()->json(['error' => "You cannot unfollow the user!"], 200);
        }
    }


    /*
    ==============================
    mail
    ==============================
    */
    public function mailSend(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $sub = $request->subject;
        $msg = $request->message;

        $subscs = Subscriber::where('user_id', $user->id)->get();
        $info = \App\Models\User\BasicSetting::where('user_id', $user->id)->select('email', 'from_name')->first();
        $email = $info->email ?? Auth::user()->email;
        $name = $info->from_name ?? Auth::user()->username;
        $settings = BasicSetting::first();
        $from = $settings->contact_mail;

        $be = BasicExtended::first();

        $mail = new PHPMailer(true);

        if ($be->is_smtp == 1) {
            try {
                //Server settings
                $mail->isSMTP();                                 // Send using SMTP
                $mail->Host = $be->smtp_host;                    // Set the SMTP server to send through
                $mail->SMTPAuth = true;                          // Enable SMTP authentication
                $mail->Username = $be->smtp_username;            // SMTP username
                $mail->Password = $be->smtp_password;            // SMTP password
                $mail->SMTPSecure = $be->encryption;             // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port = $be->smtp_port;                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                $mail->addReplyTo($email);
                //Recipients
                $mail->setFrom($be->from_mail, $name);
                foreach ($subscs as $key => $subsc) {
                    $mail->addAddress($subsc->email);     // Add a recipient
                }
            } catch (Exception $e) {

            }
        } else {
            try {
                //Recipients
                $mail->setFrom($be->from_mail, $name);
                $mail->addReplyTo($email);
                foreach ($subscs as $key => $subsc) {
                    $mail->addAddress($subsc->email);  // Add a recipient
                }
            } catch (Exception $e) {

            }
        }
        // Content
        $mail->isHTML(true);   // Set email format to HTML
        $mail->Subject = $sub;
        $mail->Body = $msg;
        $mail->send();

        return response()->json(['success' => "Mail sent successfully!"], 200);
    }

    /*
    ==============================
    announcement announcementPopupStatus
    ==============================
    */
    public function announcement(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $language = Language::where('user_id', $user->id)->where('is_default', 1)->firstOrFail()->id;
        $information['popups'] = Popup::where('language_id', $language)
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();
        $information['langs'] = Language::where('user_id', $user->id)->get();

        return $information;
    }
    public function announcementPopupStatus(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $popup = Popup::where('id', $request->id)->where('user_id', $user->id)->first();
        if ($request->status == 1) {
            $popup->update(['status' => 1]);
            return response()->json(['success' => "Popup activated successfully!"], 200);
        } else {
            $popup->update(['status' => 0]);
            return response()->json(['error' => "Popup deactivated successfully!"], 200);
        }
    }
    public function announcementPopupDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $popup = Popup::where('id', $request->id)->where('user_id', $user->id)->first();
        $popup->delete();
        return response()->json(['success' => "Popup deleted successfully!"], 200);
    }

}
