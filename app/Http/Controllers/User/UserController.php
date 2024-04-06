<?php

namespace App\Http\Controllers\User;

use App;
use Auth;
use Session;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Package;
use App\Models\User\Post;
use App\Models\Membership;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User\Follower;
use App\Models\User\Language;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('setlang');
    }

    public function index()
    {
        $data['user'] = $user = Auth::user();
        $langId = Language::where('user_id', Auth::id())->where('is_default', 1)->firstOrFail()->id;
        $data['followers'] = Follower::where('following_id', Auth::id())->count();
        $data['followings'] = Follower::where('follower_id', Auth::id())->count();

        $data['memberships'] = Membership::query()->where('user_id', Auth::user()->id)
            ->orderBy('id', 'DESC')
            ->limit(10)->get();


        $data['posts'] = Post::join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $langId)
            ->where('post_contents.user_id', '=', Auth::id())
            ->orderByDesc('posts.id')
            ->limit(10)
            ->get();

        $nextPackageCount = Membership::query()->where([
            ['user_id', Auth::id()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', '<>', 2)->count();
        //current package
        $data['current_membership'] = Membership::query()->where([
            ['user_id', Auth::id()],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->first();
        if ($data['current_membership']) {
            $countCurrMem = Membership::query()->where([
                ['user_id', Auth::id()],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])->count();
            if ($countCurrMem > 1) {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', Auth::id()],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->orderBy('id', 'DESC')->first();
            } else {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', Auth::id()],
                    ['start_date', '>', $data['current_membership']->expire_date],
                ])->where('status', '<>', 2)->first();
            }
            $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
        }
        $data['current_package'] = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
        $data['package_count'] = $nextPackageCount;
        $data['post_count'] = $user->posts->count();
        $data['featured_post_count'] = $user->posts->where('is_featured', 1)->count();
        $data['post_category_count'] = $user->postCategory->where('language_id', $langId)->count();
        $data['featured_post_category_count'] = $user->postCategory->where('language_id', $langId)->where('is_featured', 1)->count();
        $data['gallery_item_count'] = $user->galleryItem->where('language_id', $langId)->count();
        $data['featured_gallery_item_count'] = $user->galleryItem->where('language_id', $langId)->where('is_featured', 1)->count();
        $data['gallery_category_count'] = $user->galleryCategory->where('language_id', $langId)->count();
        $data['faq_count'] = $user->faq->where('language_id', $langId)->count();
        $data['language_count'] = $user->languages->count();
        $data['advertisement_count'] = $user->advertisements->count();

        return view('user.dashboard', $data);
    }


    public function profile()
    {
        $user = Auth::user();
        return view('user.edit-profile', compact('user'));
    }

    public function profileupdate(Request $request)
    {
        $img = $request->file('photo');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username,' . Auth::user()->id,
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'address' => 'required',
            'designation' => 'required',
            'photo' => [
                function ($attribute, $value, $fail) use ($request, $img, $allowedExts) {
                    if ($request->hasFile('photo')) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ]);

        //--- Validation Section Ends
        $input = $request->all();
        $data = Auth::user();
        if ($file = $request->file('photo')) {
            $input['photo'] = processFile($file, "profile");
            if ($data->photo != null) {
                Storage::delete($data->photo);
            }
        }
        $data->update($input);
        Session::flash('success', 'Profile Updated Successfully!');
        return "success";
    }

    public function resetform()
    {
        return view('user.reset');
    }

    public function reset(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
            'confirmation_password' => 'required',
        ]);
        $user = Auth::user();
        if ($request->current_password) {
            if (Hash::check($request->current_password, $user->password)) {
                if ($request->new_password == $request->confirmation_password) {
                    $input['password'] = Hash::make($request->new_password);
                } else {
                    return back()->with('err', __('Confirm password does not match.'));
                }
            } else {
                return back()->with('err', __('Current password Does not match.'));
            }
        }

        $user->update($input);
        Session::flash('success', 'Successfully change your password');
        return back();
    }

    public function changePass()
    {
        return view('user.changepass');
    }

    public function updatePassword(Request $request)
    {
        $messages = [
            'password.required' => 'The new password field is required',
            'password.confirmed' => "Password does'nt match"
        ];
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ], $messages);
        // if given old password matches with the password of this authenticated user...
        if (Hash::check($request->old_password, Auth::guard('web')->user()->password)) {
            $oldPassMatch = 'matched';
        } else {
            $oldPassMatch = 'not_matched';
        }
        if ($validator->fails() || $oldPassMatch == 'not_matched') {
            if ($oldPassMatch == 'not_matched') {
                $validator->errors()->add('oldPassMatch', true);
            }
            return redirect()->route('user.changePass')
                ->withErrors($validator);
        }

        // updating password in database...
        $user = App\Models\User::findOrFail(Auth::guard('web')->user()->id);
        $user->password = bcrypt($request->password);
        $user->save();

        Session::flash('success', 'Password changed successfully!');

        return redirect()->back();
    }

    public function changeTheme(Request $request)
    {
        return redirect()->back()->withCookie(cookie()->forever('user-theme', $request->theme));
    }
}

