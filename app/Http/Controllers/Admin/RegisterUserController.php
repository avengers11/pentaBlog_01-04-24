<?php

namespace App\Http\Controllers\Admin;

use App\Models\User\BookmarkPost;
use App\Models\User\Post;
use App\Models\User\PostContent;
use App\Models\User\PostView;
use App\Models\User\Testimonial;
use App\Models\User\TestimonialContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\BasicExtended;
use App\Models\Membership;
use App\Models\OfflineGateway;
use App\Models\Package;
use App\Models\PaymentGateway;
use App\Models\User;
use App\Models\User\BasicSetting;
use App\Models\User\HomeSection;
use App\Models\User\Language;
use App\Models\User\Menu;
use App\Models\User\UserPaymentGeteway;
use App\Models\User\UserShopSetting;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

class RegisterUserController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term;
        $users = User::when($term, function ($query, $term) {
            $query->where('username', 'like', '%' . $term . '%')->orWhere('email', 'like', '%' . $term . '%');
        })->orderBy('id', 'DESC')->paginate(10);

        $online = PaymentGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->get();
        $gateways = $online->merge($offline);
        $packages = Package::query()->where('status', '1')->get();

        return view('admin.register_user.index', compact('users', 'gateways', 'packages'));
    }

    public function view($id)
    {
        $user = User::findOrFail($id);
        return view('admin.register_user.details', compact('user'));
    }

    public function store(Request $request)
    {

        $rules = [
            'username' => 'required|alpha_num|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'package_id' => 'required',
            'payment_gateway' => 'required',
            'online_status' => 'required'
        ];

        $messages = [
            'package_id.required' => 'The package field is required',
            'online_status.required' => 'The publicly hidden field is required'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user = User::where('username', $request['username']);
        if ($user->count() == 0) {
            $user = User::create([
                'email' => $request['email'],
                'username' => $request['username'],
                'password' => bcrypt($request['password']),
                'online_status' => $request["online_status"],
                'status' => 1,
                'email_verified' => 1,
            ]);

            BasicSetting::create([
                'user_id' => $user->id,
            ]);
            //create default payment gateway
            $payment_keywords = ['flutterwave', 'razorpay', 'paytm', 'paystack', 'instamojo', 'stripe', 'paypal', 'mollie', 'mercadopago', 'authorize.net'];
            foreach ($payment_keywords as $key => $value) {
                UserPaymentGeteway::create([
                    'title' => null,
                    'user_id' => $user->id,
                    'details' => null,
                    'keyword' => $value,
                    'subtitle' => null,
                    'name' => ucfirst($value),
                    'type' => 'automatic',
                    'information' => null
                ]);
            }
            //create default shop Settings
            UserShopSetting::create([
                'user_id' => $user->id,
                'is_shop' => 1,
                'catalog_mode' => 0,
                'item_rating_system' => 1,
                'tax' => 0,
            ]);

            $homeSection = new HomeSection();
            $homeSection->user_id = $user->id;
            $homeSection->save();
        }

        if ($user) {
            $deLang = Language::firstOrFail();
            $langCount = Language::where('user_id', $user->id)->where('is_default', 1)->count();
            if ($langCount == 0) {
                $lang = new User\Language;
                $lang->name = 'English';
                $lang->code = 'en';
                $lang->is_default = 1;
                $lang->rtl = 0;
                $lang->user_id = $user->id;
                $lang->keywords = $deLang->keywords;
                $lang->save();

                $umenu = new Menu();
                $umenu->language_id = $lang->id;
                $umenu->user_id = $user->id;
                $umenu->menus = '[{"text":"Home","href":"","icon":"empty","target":"_self","title":"","type":"home"},{"text":"About","href":"","icon":"empty","target":"_self","title":"","type":"about"},{"text":"Posts","href":"","icon":"empty","target":"_self","title":"","type":"posts"},{"text":"Gallery","href":"","icon":"empty","target":"_self","title":"","type":"gallery"},{"text":"FAQs","href":"","icon":"empty","target":"_self","title":"","type":"faq"},{"text":"Contact","href":"","icon":"empty","target":"_self","title":"","type":"contact"}]';
                $umenu->save();
            }

            $package = Package::find($request['package_id']);
            $be = BasicExtended::first();
            $bs = BasicSetting::select('website_title')->first();
            $transaction_id = UserPermissionHelper::uniqidReal(8);

            $startDate = Carbon::today()->format('Y-m-d');
            if ($package->term === "monthly") {
                $endDate = Carbon::today()->addMonth()->format('Y-m-d');
            } elseif ($package->term === "yearly") {
                $endDate = Carbon::today()->addYear()->format('Y-m-d');
            } elseif ($package->term === "lifetime") {
                $endDate = Carbon::maxValue()->format('d-m-Y');
            }

            Membership::create([
                'price' => $package->price,
                'currency' => $be->base_currency_text ? $be->base_currency_text : "USD",
                'currency_symbol' => $be->base_currency_symbol ? $be->base_currency_symbol : $be->base_currency_text,
                'payment_method' => $request["payment_gateway"],
                'transaction_id' => $transaction_id ? $transaction_id : 0,
                'status' => 1,
                'is_trial' => 0,
                'trial_days' => 0,
                'receipt' => $request["receipt_name"] ? $request["receipt_name"] : null,
                'transaction_details' => null,
                'settings' => json_encode($be),
                'package_id' => $request['package_id'],
                'user_id' => $user->id,
                'start_date' => Carbon::parse($startDate),
                'expire_date' => Carbon::parse($endDate),
            ]);
            $package = Package::findOrFail($request['package_id']);
            $requestData = [
                'start_date' => $startDate,
                'expire_date' => $endDate,
                'payment_method' => $request['payment_gateway']
            ];
            $file_name = $this->makeInvoice($requestData, "membership", $user, null, $package->price, $request['payment_gateway'], null, $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title);

            $mailer = new MegaMailer();
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
            $data = [
                'toMail' => $user->email,
                'toName' => $user->fname,
                'username' => $user->username,
                'package_title' => $package->title,
                'package_price' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
                'activation_date' => $startDate->toFormattedDateString(),
                'expire_date' => $endDate->toFormattedDateString(),
                'membership_invoice' => $file_name,
                'website_title' => $bs->website_title,
                'templateType' => 'registration_with_premium_package',
                'type' => 'registrationWithPremiumPackage'
            ];
            $mailer->mailFromAdmin($data);
        }

        Session::flash('success', __('User added successfully!'));
        return "success";
    }


    public function userban(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->update([
            'status' => $request->status,
        ]);
        Session::flash('success', __('Status update successfully!'));
        return back();
    }


    public function emailStatus(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->update([
            'email_verified' => $request->email_verified,
        ]);
        Session::flash('success', __('Email status updated for ' . $user->username));
        return back();
    }

    public function userFeatured(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->featured = $request->featured;
        $user->save();
        Session::flash('success', __('User featured update successfully!'));
        return back();
    }

    public function userTemplate(Request $request)
    {
        if ($request->template == 1) {
            $prevImg = $request->file('preview_image');
            $allowedExts = array('jpg', 'png', 'jpeg');

            $rules = [
                'serial_number' => 'required|integer',
                'preview_image' => [
                    'required',
                    function ($attribute, $value, $fail) use ($prevImg, $allowedExts) {
                        if (!empty($prevImg)) {
                            $ext = $prevImg->getClientOriginalExtension();
                            if (!in_array($ext, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg image is allowed");
                            }
                        }
                    },
                ]
            ];


            $request->validate($rules);
        }

        $user = User::where('id', $request->user_id)->first();

        if ($request->template == 1) {
            if ($request->hasFile('preview_image')) {
                @unlink(public_path('assets/front/img/template-previews/' . $user->template_img));
                $filename = uniqid() . '.' . $prevImg->getClientOriginalExtension();
                $dir = public_path('assets/front/img/template-previews/');
                @mkdir($dir, 0775, true);
                $request->file('preview_image')->move($dir, $filename);
                $user->template_img = $filename;
            }
            $user->template_serial_number = $request->serial_number;
        } else {
            @unlink(public_path('assets/front/img/template-previews/' . $user->template_img));
            $user->template_img = NULL;
            $user->template_serial_number = 0;
        }
        $user->preview_template = $request->template;
        $user->save();
        Session::flash('success', __('Status updated successfully!'));
        return back();
    }

    public function userUpdateTemplate(Request $request)
    {
        $prevImg = $request->file('preview_image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'serial_number' => 'required|integer',
            'preview_image' => [
                function ($attribute, $value, $fail) use ($prevImg, $allowedExts) {
                    if (!empty($prevImg)) {
                        $ext = $prevImg->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ]
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user = User::where('id', $request->user_id)->first();


        if ($request->hasFile('preview_image')) {
            @unlink(public_path('assets/front/img/template-previews/' . $user->template_img));
            $filename = uniqid() . '.' . $prevImg->getClientOriginalExtension();
            $dir = public_path('assets/front/img/template-previews/');
            @mkdir($dir, 0775, true);
            $request->file('preview_image')->move($dir, $filename);
            $user->template_img = $filename;
        }
        $user->template_serial_number = $request->serial_number;
        $user->save();


        Session::flash('success', __('Status updated successfully!'));
        return "success";
    }


    public function changePass($id)
    {
        $data['user'] = User::findOrFail($id);
        return view('admin.register_user.password', $data);
    }


    public function updatePassword(Request $request)
    {
        $messages = [
            'npass.required' => 'New password is required',
            'cfpass.required' => 'Confirm password is required',
        ];

        $request->validate([
            'npass' => 'required',
            'cfpass' => 'required',
        ], $messages);


        $user = User::findOrFail($request->user_id);
        if ($request->npass == $request->cfpass) {
            $input['password'] = Hash::make($request->npass);
        } else {
            return back()->with('warning', __('Confirm password does not match.'));
        }

        $user->update($input);

        Session::flash('success', __('Password update for ' . $user->username));
        return back();
    }

    public function delete(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        // delete the user's custom domain
        if ($user->user_custom_domains()->count() > 0) {
            $user_custom_domains = $user->user_custom_domains()->get();
            if (count($user_custom_domains) > 0) {
                foreach ($user_custom_domains as $user_custom_domain) {
                    $user_custom_domain->delete();
                }
            }
        }
        //deleting page heading for corresponding user
        if ($user->pageHeading()->count() > 0) {
            $user->pageHeading()->delete();
        }
        // deleting seos for corresponding user
        if ($user->seos()->count() > 0) {
            $user->seos()->delete();
        }
        // deleting cookie alert for corresponding user
        if ($user->cookieAlert()->count() > 0) {
            $user->cookieAlert()->delete();
        }

        // deleting menus for corresponding user
        if ($user->menus()->count() > 0) {
            $user->menus()->delete();
        }
        // deleting social media for corresponding user
        if ($user->social_media()->count() > 0) {
            $user->social_media()->delete();
        }
        // deleting post and post contents
        $postContents = PostContent::where('user_id', $user->id);
        if ($postContents->count() > 0) {
            foreach ($postContents->get() as $pc) {
                // if this post has no post_contents of other languages except the selected one,
                // then delete the post...
                $otherPcs = PostContent::where('user_id', $user->id)
                    ->where('post_id', $pc->post_id)
                    ->where('id', '<>', $pc->id)
                    ->count();
                if ($otherPcs == 0) {
                    $post = Post::findOrFail($pc->post_id);
                    @unlink(public_path('assets/user/img/posts/' . $post->thumbnail_image));
                    if (!empty($post->slider_images) && $post->slider_images != '[]') {
                        $sliders = json_decode($post->slider_images, true);
                        foreach ($sliders as $slider) {
                            @unlink(public_path('assets/user/img/posts/slider-images/' . $slider));
                        }
                    }
                    $bookmarks = BookmarkPost::where('post_id', $post->id)->where('author_id', $user->id);
                    if ($bookmarks->count() > 0) {
                        $bookmarks->delete();
                    }
                    $pvs = PostView::where('post_id', $post->id)->where('author_id', $user->id);
                    if ($pvs->count() > 0) {
                        $pvs->delete();
                    }
                    $post->delete();
                }
                $pc->delete();
            }
        }

        // delete the post category images
        if ($user->postCategory()->count() > 0) {
            $categories = $user->postCategory()->get();
            if (count($categories) > 0) {
                foreach ($categories as $category) {
                    @unlink(public_path('assets/user/img/post-categories/' . $category->image));
                    $category->delete();
                }
            }
        }


        // delete the gallery item's images
        $items = $user->galleryItem()->get();
        if (count($items) > 0) {
            foreach ($items as $item) {
                @unlink(public_path('assets/user/img/gallery/' . $item->image));
                $item->delete();
            }
        }
        if ($user->galleryCategory()->count() > 0) {
            $user->galleryCategory()->delete();
        }
        // delete the author's image & video background image
        if ($user->authorInfo()->count() > 0) {
            $info = $user->authorInfo()->first();
            @unlink(public_path('assets/user/img/authors/' . $info->image));
            @unlink(public_path('assets/user/img/' . $info->video_background_image));
            $info->delete();
        }
        // delete the users faq
        if ($user->faq()->count() > 0) {
            $user->faq()->delete();
        }
        // delete the popup images
        if ($user->announcementPopup()->count() > 0) {
            $popups = $user->announcementPopup()->get();
            if (count($popups) > 0) {
                foreach ($popups as $popup) {
                    @unlink(public_path('assets/user/img/popups/' . $popup->image));
                    $popup->delete();
                }
            }
        }
        // delete the brand images
        if ($user->brands()->count() > 0) {
            $brands = $user->brands;
            if (count($brands) > 0) {
                foreach ($brands as $brand) {
                    @unlink(public_path('assets/user/img/brands/' . $brand->brand_img));
                    $brand->delete();
                }
            }
        }

        // delete the user's footer text
        if ($user->footerText()->count() > 0) {
            $user->footerText()->delete();
        }
        // delete the user's footer quick link
        if ($user->footerQuickLink()->count() > 0) {
            $quickLinks = $user->footerQuickLink()->get();
            if (count($quickLinks) > 0) {
                foreach ($quickLinks as $quickLink) {
                    $quickLink->delete();
                }
            }
        }
        // delete the user's languages
        if ($user->languages()->count() > 0) {
            $user->languages()->delete();
        }
        // delete the user's basic settings
        if ($user->basic_setting()->count() > 0) {
            $bs = $user->basic_setting;
            @unlink(public_path('assets/user/img/' . $bs->logo));
            @unlink(public_path('assets/user/img/' . $bs->preloader));
            @unlink(public_path('assets/user/img/' . $bs->favicon));
            $bs->delete();
        }
        // delete the user memberships
        if ($user->memberships()->count() > 0) {
            foreach ($user->memberships as $key => $membership) {
                @unlink(public_path('assets/front/img/membership/receipt/' . $membership->receipt));
                $membership->delete();
            }
        }
        // delete the user testimonials
        $testContents = TestimonialContent::where('user_id', $user->id);
        if ($testContents->count() > 0) {
            foreach ($testContents->get() as $tc) {
                // if this testimonial has no testimonial_contents of other languages except the selected one,
                // then delete the testimonial...
                $otherTcs = TestimonialContent::where('user_id', $user->id)
                    ->where('testimonial_id', $tc->testimonial_id)
                    ->count();

                if ($otherTcs == 0) {
                    $test = Testimonial::findOrFail($tc->testimonial_id);
                    @unlink(public_path('assets/user/img/testimonials/' . $test->client_image));
                    $test->delete();
                }
                $tc->delete();
            }
        }

        // delete the user's subscriber
        if ($user->subscriber()->count() > 0) {
            $subscribers = $user->subscriber()->get();
            if (count($subscribers) > 0) {
                foreach ($subscribers as $subscriber) {
                    $subscriber->delete();
                }
            }
        }

        // deleting qrcode for corresponding user
        if ($user->qr_code()->count() > 0) {
            @unlink(public_path('assets/user/img/qr/' . $user->qr_code()->image));
            $user->qr_code()->delete();
        }
        if ($user->vcards()->count() > 0) {
            $vcards = $user->vcards()->get();
            foreach ($vcards as $vcard) {
                @unlink(public_path('assets/user/img/vcard/' . $vcard->profile_image));
                @unlink(public_path('assets/user/img/vcard/' . $vcard->cover_image));
                if ($vcard->user_vcard_projects()->count() > 0) {
                    foreach ($vcard->user_vcard_projects as $project) {
                        @unlink(public_path('assets/user/img/projects/' . $project->image));
                        $project->delete();
                    }
                }
                if ($vcard->user_vcard_services()->count() > 0) {
                    foreach ($vcard->user_vcard_services as $service) {
                        @unlink(public_path('assets/user/img/services/' . $service->image));
                        $service->delete();
                    }
                }
                if ($vcard->user_vcard_testimonials()->count() > 0) {
                    foreach ($vcard->user_vcard_testimonials as $testimonial) {
                        @unlink(public_path('assets/user/img/testimonials/' . $testimonial->image));
                        $testimonial->delete();
                    }
                }
                $vcard->delete();
            }
        }
        // delete the user home section
        if ($user->home_section()->count() > 0) {
            $user->home_section()->delete();
        }

        // delete the user's customer
        if ($user->customer()->count() > 0) {
            $customers = $user->customer()->get();
            if (count($customers) > 0) {
                foreach ($customers as $customer) {
                    @unlink(public_path('assets/user/img/users/' . $customer->image));
                    $customer->delete();
                }
            }
        }
        @unlink(public_path('assets/user/img/' . $user->photo));
        $user->delete();
        Session::flash('success', __('User deleted successfully!'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $user = User::findOrFail($id);
            // delete the user's custom domain
            if ($user->user_custom_domains()->count() > 0) {
                $user_custom_domains = $user->user_custom_domains()->get();
                if (count($user_custom_domains) > 0) {
                    foreach ($user_custom_domains as $user_custom_domain) {
                        $user_custom_domain->delete();
                    }
                }
            }
            //deleting page heading for corresponding user
            if ($user->pageHeading()->count() > 0) {
                $user->pageHeading()->delete();
            }
            // deleting seos for corresponding user
            if ($user->seos()->count() > 0) {
                $user->seos()->delete();
            }
            // deleting cookie alert for corresponding user
            if ($user->cookieAlert()->count() > 0) {
                $user->cookieAlert()->delete();
            }

            // deleting menus for corresponding user
            if ($user->menus()->count() > 0) {
                $user->menus()->delete();
            }
            // deleting social media for corresponding user
            if ($user->social_media()->count() > 0) {
                $user->social_media()->delete();
            }
            // deleting post and post contents
            $postContents = PostContent::where('user_id', $user->id);
            if ($postContents->count() > 0) {
                foreach ($postContents->get() as $pc) {
                    // if this post has no post_contents of other languages except the selected one,
                    // then delete the post...
                    $otherPcs = PostContent::where('user_id', $user->id)
                        ->where('post_id', $pc->post_id)
                        ->where('id', '<>', $pc->id)
                        ->count();
                    if ($otherPcs == 0) {
                        $post = Post::findOrFail($pc->post_id);
                        @unlink(public_path('assets/user/img/posts/' . $post->thumbnail_image));
                        if (!empty($post->slider_images) && $post->slider_images != '[]') {
                            $sliders = json_decode($post->slider_images, true);
                            foreach ($sliders as $slider) {
                                @unlink(public_path('assets/user/img/posts/slider-images/' . $slider));
                            }
                        }
                        $bookmarks = BookmarkPost::where('post_id', $post->id)->where('author_id', $user->id);
                        if ($bookmarks->count() > 0) {
                            $bookmarks->delete();
                        }
                        $pvs = PostView::where('post_id', $post->id)->where('author_id', $user->id);
                        if ($pvs->count() > 0) {
                            $pvs->delete();
                        }
                        $post->delete();
                    }
                    $pc->delete();
                }
            }

            // delete the post category images
            if ($user->postCategory()->count() > 0) {
                $categories = $user->postCategory()->get();
                if (count($categories) > 0) {
                    foreach ($categories as $category) {
                        @unlink(public_path('assets/user/img/post-categories/' . $category->image));
                        $category->delete();
                    }
                }
            }


            // delete the gallery item's images
            $items = $user->galleryItem()->get();
            if (count($items) > 0) {
                foreach ($items as $item) {
                    @unlink(public_path('assets/user/img/gallery/' . $item->image));
                    $item->delete();
                }
            }
            if ($user->galleryCategory()->count() > 0) {
                $user->galleryCategory()->delete();
            }
            // delete the author's image & video background image
            if ($user->authorInfo()->count() > 0) {
                $info = $user->authorInfo()->first();
                @unlink(public_path('assets/user/img/authors/' . $info->image));
                @unlink(public_path('assets/user/img/' . $info->video_background_image));
                $info->delete();
            }
            // delete the users faq
            if ($user->faq()->count() > 0) {
                $user->faq()->delete();
            }
            // delete the popup images
            if ($user->announcementPopup()->count() > 0) {
                $popups = $user->announcementPopup()->get();
                if (count($popups) > 0) {
                    foreach ($popups as $popup) {
                        @unlink(public_path('assets/user/img/popups/' . $popup->image));
                        $popup->delete();
                    }
                }
            }
            // delete the brand images
            if ($user->brands()->count() > 0) {
                $brands = $user->brands;
                if (count($brands) > 0) {
                    foreach ($brands as $brand) {
                        @unlink(public_path('assets/user/img/brands/' . $brand->brand_img));
                        $brand->delete();
                    }
                }
            }

            // delete the user's footer text
            if ($user->footerText()->count() > 0) {
                $user->footerText()->delete();
            }
            // delete the user's footer quick link
            if ($user->footerQuickLink()->count() > 0) {
                $quickLinks = $user->footerQuickLink()->get();
                if (count($quickLinks) > 0) {
                    foreach ($quickLinks as $quickLink) {
                        $quickLink->delete();
                    }
                }
            }
            // delete the user's languages
            if ($user->languages()->count() > 0) {
                $user->languages()->delete();
            }
            // delete the user's basic settings
            if ($user->basic_setting()->count() > 0) {
                $bs = $user->basic_setting;
                @unlink(public_path('assets/user/img/' . $bs->logo));
                @unlink(public_path('assets/user/img/' . $bs->preloader));
                @unlink(public_path('assets/user/img/' . $bs->favicon));
                $bs->delete();
            }
            // delete the user memberships
            if ($user->memberships()->count() > 0) {
                foreach ($user->memberships as $key => $membership) {
                    @unlink(public_path('assets/front/img/membership/receipt/' . $membership->receipt));
                    $membership->delete();
                }
            }
            // delete the user testimonials
            $testContents = TestimonialContent::where('user_id', $user->id);
            if ($testContents->count() > 0) {
                foreach ($testContents->get() as $tc) {
                    // if this testimonial has no testimonial_contents of other languages except the selected one,
                    // then delete the testimonial...
                    $otherTcs = TestimonialContent::where('user_id', $user->id)
                        ->where('testimonial_id', $tc->testimonial_id)
                        ->count();

                    if ($otherTcs == 0) {
                        $test = Testimonial::findOrFail($tc->testimonial_id);
                        @unlink(public_path('assets/user/img/testimonials/' . $test->client_image));
                        $test->delete();
                    }
                    $tc->delete();
                }
            }

            // delete the user's subscriber
            if ($user->subscriber()->count() > 0) {
                $subscribers = $user->subscriber()->get();
                if (count($subscribers) > 0) {
                    foreach ($subscribers as $subscriber) {
                        $subscriber->delete();
                    }
                }
            }

            // deleting qrcode for corresponding user
            if ($user->qr_code()->count() > 0) {
                @unlink(public_path('assets/user/img/qr/' . $user->qr_code()->image));
                $user->qr_code()->delete();
            }
            if ($user->vcards()->count() > 0) {
                $vcards = $user->vcards()->get();
                foreach ($vcards as $vcard) {
                    @unlink(public_path('assets/user/img/vcard/' . $vcard->profile_image));
                    @unlink(public_path('assets/user/img/vcard/' . $vcard->cover_image));
                    if ($vcard->user_vcard_projects()->count() > 0) {
                        foreach ($vcard->user_vcard_projects as $project) {
                            @unlink(public_path('assets/user/img/projects/' . $project->image));
                            $project->delete();
                        }
                    }
                    if ($vcard->user_vcard_services()->count() > 0) {
                        foreach ($vcard->user_vcard_services as $service) {
                            @unlink(public_path('assets/user/img/services/' . $service->image));
                            $service->delete();
                        }
                    }
                    if ($vcard->user_vcard_testimonials()->count() > 0) {
                        foreach ($vcard->user_vcard_testimonials as $testimonial) {
                            @unlink(public_path('assets/user/img/testimonials/' . $testimonial->image));
                            $testimonial->delete();
                        }
                    }
                    $vcard->delete();
                }
            }
            // delete the user home section
            if ($user->home_section()->count() > 0) {
                $user->home_section()->delete();
            }

            // delete the user's customer
            if ($user->customer()->count() > 0) {
                $customers = $user->customer()->get();
                if (count($customers) > 0) {
                    foreach ($customers as $customer) {
                        @unlink(public_path('assets/user/img/users/' . $customer->image));
                        $customer->delete();
                    }
                }
            }
            // delete the user's advertisement
            if ($user->advertisements()->count() > 0) {
                $advertisements = $user->advertisements()->get();
                if (count($advertisements) > 0) {
                    foreach ($advertisements as $advertisement) {
                        $advertisement->delete();
                    }
                }
            }
            @unlink(public_path('assets/user/img/' . $user->photo));
            $user->delete();
        }

        Session::flash('success', __('Users deleted successfully!'));
        return "success";
    }
    public function secretUserLogin(Request $request)
    {

        $user = User::find($request->user_id);
        if ($user) {
            Auth::guard('web')->login($user, true);
            return redirect()->route('user-dashboard')
                ->withSuccess('You have Successfully Loggedin');
        }
        Session::flash('warning', 'Opps You provide Invalid Credentials !');
        return back();
    }
}
