<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\BasicSetting;
use App\Models\User\HomeSection;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use Session;

class BasicSettingController extends Controller
{
    public function favicon()
    {
        $data = BasicSetting::where('user_id', Auth::id())
            ->select('favicon')
            ->first();

        return view('user.basic-settings.favicon', ['data' => $data]);
    }

    public function updateFavicon(Request $request)
    {
        $faviconURL = $request->favicon;

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg', 'ico');
        $fileExtension = $faviconURL ? $faviconURL->extension() : null;

        $rule = [
            'favicon' => function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension, $faviconURL) {
                if (!empty($faviconURL) && !in_array($fileExtension, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png .ico and .svg file is allowed.');
                }
            }
        ];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        if (!empty($faviconURL)) {
            // first, get the favicon from db
            $data = BasicSetting::where('user_id', Auth::id())
                ->select('favicon')
                ->first();

            // second, delete the previous favicon from local storage
            @unlink(public_path('assets/user/img/' . $data->favicon));

            // third, set a name for the favicon and store it to local storage
            $iconName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/');

            @mkdir($directory, 0775, true);

            @copy($faviconURL, $directory . $iconName);

            // finally, store the favicon into db
            BasicSetting::where('user_id', Auth::id())
                ->update(['favicon' => $iconName]);
        }

        $request->session()->flash('success', 'Favicon updated successfully!');
        return "success";
    }
    public function logo()
    {
        $data = BasicSetting::where('user_id', Auth::id())
            ->select('logo')
            ->first();
        return view('user.basic-settings.logo', ['data' => $data]);
    }
    public function updateLogo(Request $request)
    {
        $logoURL = $request->logo;
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
        $fileExtension = $logoURL ? $logoURL->extension() : null;
        $rule = [
            'logo' => function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension, $logoURL) {
                if (!empty($logoURL) && !in_array($fileExtension, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                }
            }
        ];

        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        if (!empty($logoURL)) {
            // first, get the logo from db
            $data = BasicSetting::where('user_id', Auth::id())
                ->select('logo')
                ->first();
            // second, delete the previous logo from local storage
            @unlink(public_path('assets/user/img/' . $data->logo));
            // third, set a name for the logo and store it to local storage
            $logoName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/');
            @mkdir($directory, 0775, true);
            @copy($logoURL, $directory . $logoName);
            // finally, store the logo into db
            BasicSetting::where('user_id', Auth::id())
                ->update(['logo' => $logoName]);
        }
        $request->session()->flash('success', 'Logo updated successfully!');
        return "success";
    }
    public function preloader()
    {
        $data = BasicSetting::where('user_id', Auth::id())
            ->select('preloader', 'preloader_status')
            ->first();
        return view('user.basic-settings.preloader', ['data' => $data]);
    }
    public function updatePreloader(Request $request)
    {
        $preloaderURL = $request->preloader;
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg', 'gif');
        $fileExtension = $preloaderURL ? $preloaderURL->extension() : null;
        $rule = [
            'preloader' => function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension, $preloaderURL) {
                if (!empty($preloaderURL) && !in_array($fileExtension, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png, .svg and .gif file is allowed.');
                }
            }
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        if (!empty($preloaderURL)) {
            // first, get the preloader from db
            $data = BasicSetting::where('user_id', Auth::id())
                ->select('preloader')
                ->first();
            // second, delete the previous preloader from local storage
            @unlink(public_path('assets/user/img/' . $data->preloader));
            // third, set a name for the preloader and store it to local storage
            $preloaderName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/');
            @mkdir($directory, 0775, true);
            @copy($preloaderURL, $directory . $preloaderName);
        }
        $bs = BasicSetting::where('user_id', Auth::id())
            ->select('preloader')
            ->first();
        // finally, store the preloader into db
        BasicSetting::where('user_id', Auth::id())->update([
            'preloader' => !empty($preloaderURL) ? $preloaderName : $bs->preloader,
            'preloader_status' => $request->preloader_status
        ]);
        $request->session()->flash('success', 'Preloader updated successfully!');
        return "success";
    }
    public function preferences()
    {
        $data = Auth::user();
        return view('user.basic-settings.preferences', ['data' => $data]);
    }
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        $user->online_status = $request->online_status;
        $user->listing_page = $request->listing_page;
        $user->save();
        Session::flash('success', "Preferences updated successfully!");
        return "success";
    }

    public function information()
    {
        $data = BasicSetting::where('user_id', Auth::guard('web')->user()->id)
            ->first();

        return view('user.basic-settings.information', ['data' => $data]);
    }

    public function updateInfo(Request $request)
    {

        $user = Auth::guard('web')->user();
        $package = UserPermissionHelper::currentPackagePermission($user->id);
        if (!empty($user)) {
            $permissions = UserPermissionHelper::packagePermission($user->id);
            $permissions = json_decode($permissions, true);
        }
        $rules = [];
        $rules['website_title'] = 'required';
        if (!empty($permissions) && in_array('Ecommerce', $permissions)) {
            $rules['base_currency_symbol'] = 'required';
            $rules['base_currency_symbol_position'] = 'required';
            $rules['base_currency_text'] = 'required';
            $rules['base_currency_text_position'] = 'required';
            $rules['base_currency_rate'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }


        BasicSetting::where('user_id', Auth::guard('web')->user()->id)->update([
            'website_title' => $request->website_title,
            'support_email' => $request->support_email,
            'support_contact' => $request->support_contact,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'base_currency_symbol' => $request->base_currency_symbol,
            'base_currency_symbol_position' => $request->base_currency_symbol_position,
            'base_currency_text' => $request->base_currency_text,
            'base_currency_text_position' => $request->base_currency_text_position,
            'base_currency_rate' => $request->base_currency_rate
        ]);
        $request->session()->flash('success', 'Information updated successfully!');

        return 'success';
    }

    public function themeAndHome()
    {
        $data = BasicSetting::where('user_id', Auth::id())
            ->select('theme_version')
            ->first();

        return view('user.basic-settings.theme-&-home', ['data' => $data]);
    }

    public function updateThemeAndHome(Request $request)
    {
        $rules = [
            'theme_version' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        BasicSetting::where('user_id', Auth::id())->update(
            ['theme_version' => $request->theme_version]
        );

        $request->session()->flash('success', 'Theme & home version updated successfully!');

        return redirect()->back();
    }


    public function homeSections(Request $request)
    {
        $data['hs'] = HomeSection::where('user_id', Auth::id())->first();
        $data['websiteInfo'] = BasicSetting::where('user_id', Auth::id())->select('theme_version')->first();
        return view('user.basic-settings.home-sections', $data);
    }

    public function updateHomeSections(Request $request)
    {
        $bs = BasicSetting::where('user_id', Auth::id())->select('theme_version')->first();
        $hs = HomeSection::where('user_id', Auth::id())->first();
        if (is_null($hs)) {
            $hs = new HomeSection;
            $hs->user_id = Auth::id();
        }

        if($request->has('slider_posts')){
            $hs->slider_posts = $request->slider_posts;
        }
        if($request->has('hero_section_posts')){
            $hs->hero_section_posts = $request->hero_section_posts;
        }

        if ($bs->theme_version != 3) {
            if ($bs->theme_version != 4) {
                $hs->post_categories = $request->post_categories;
            }
            $hs->featured_posts = $request->featured_posts;

            $user = Auth::guard('web')->user();
            if (!empty($user)) {
                $permissions = UserPermissionHelper::packagePermission($user->id);
                $permissions = json_decode($permissions, true);
            }
            if (!empty($permissions) && in_array('Gallery', $permissions)) {
                $hs->gallery = $request->gallery;
            }
        }

        $hs->latest_posts = $request->latest_posts;

        if ($bs->theme_version != 4) {
            $hs->popular_posts = $request->popular_posts;
            $hs->sidebar_ads = $request->sidebar_ads;

        }
        if($request->has('author_info')){
            $hs->author_info = $request->author_info;
        }
        $hs->newsletter = $request->newsletter;
        $hs->featured_category_posts = $request->featured_category_posts;
        $hs->footer = $request->footer;
        if ($bs->theme_version == 4 || $bs->theme_version == 5 || $bs->theme_version == 6 || $bs->theme_version == 7) {
            $hs->copyright_text = $request->copyright_text;
        }
        $hs->save();

        $request->session()->flash('success', 'Sections updated successfully!');
        return "success";
    }


    public function appearance()
    {
        $data = BasicSetting::where('user_id', Auth::id())
            ->select('primary_color', 'breadcrumb_overlay_color', 'breadcrumb_overlay_opacity')
            ->first();

        return view('user.basic-settings.appearance', ['data' => $data]);
    }

    public function updateAppearance(Request $request)
    {
        $rules = [
            'primary_color' => 'required',
            'breadcrumb_overlay_color' => 'required',
            'breadcrumb_overlay_opacity' => 'required|numeric|min:0|max:1'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        BasicSetting::where('user_id', Auth::id())->update([
            'primary_color' => $request->primary_color,
            'breadcrumb_overlay_color' => $request->breadcrumb_overlay_color,
            'breadcrumb_overlay_opacity' => $request->breadcrumb_overlay_opacity
        ]);

        $request->session()->flash('success', 'Appearance updated successfully!');
        return "success";
    }

    public function breadcrumb()
    {
        $data = BasicSetting::where('user_id', Auth::id())
            ->select('breadcrumb')
            ->first();

        return view('user.basic-settings.breadcrumb', ['data' => $data]);
    }

    public function updateBreadcrumb(Request $request)
    {
        $breadcrumbURL = $request->breadcrumb;

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
        $fileExtension = $breadcrumbURL ? $breadcrumbURL->extension() : null;

        $rule = [
            'breadcrumb' => function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension, $breadcrumbURL) {
                if (!empty($breadcrumbURL) && !in_array($fileExtension, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                }
            }
        ];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        if (!empty($breadcrumbURL)) {
            // first, get the breadcrumb from db
            $data = BasicSetting::where('user_id', Auth::id())->select('breadcrumb')->first();

            // second, delete the previous breadcrumb from local storage
            @unlink(public_path('assets/user/img/' . $data->breadcrumb));

            // third, set a name for the breadcrumb and store it to local storage
            $breadcrumbName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/');

            @mkdir($directory, 0775, true);

            @copy($breadcrumbURL, $directory . $breadcrumbName);

            // finally, store the breadcrumb into db
            BasicSetting::where('user_id', Auth::id())
                ->update(['breadcrumb' => $breadcrumbName]);
        }

        $request->session()->flash('success', 'Breadcrumb updated successfully!');

        return "success";
    }


    public function plugins()
    {
        $data = BasicSetting::where('user_id', Auth::id())->first();
        return view('user.basic-settings.plugins', compact('data'));
    }

    public function updateAnalytics(Request $request)
    {
        $rules = [
            'analytics_status' => 'required',
            'measurement_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        BasicSetting::where('user_id', Auth::id())->update(
            [
                'analytics_status' => $request->analytics_status,
                'measurement_id' => $request->measurement_id
            ]
        );

        $request->session()->flash('success', 'Analytics info updated successfully!');

        return "success";
    }


    public function updateRecaptcha(Request $request)
    {
        $rules = [
            'is_recaptcha' => 'required',
            'google_recaptcha_site_key' => 'required',
            'google_recaptcha_secret_key' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        BasicSetting::where('user_id', Auth::id())->update(
            [
                'is_recaptcha' => $request->is_recaptcha,
                'google_recaptcha_site_key' => $request->google_recaptcha_site_key,
                'google_recaptcha_secret_key' => $request->google_recaptcha_secret_key,
            ]
        );

        $request->session()->flash('success', 'Recaptcha info updated successfully!');

        return "success";
    }

    public function updateWhatsApp(Request $request)
    {
        $rules = [
            'whatsapp_status' => 'required',
            'whatsapp_number' => 'required',
            'whatsapp_header_title' => 'required',
            'whatsapp_popup_status' => 'required',
            'whatsapp_popup_message' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        BasicSetting::where('user_id', Auth::id())->update(
            [
                'whatsapp_status' => $request->whatsapp_status,
                'whatsapp_number' => $request->whatsapp_number,
                'whatsapp_header_title' => $request->whatsapp_header_title,
                'whatsapp_popup_status' => $request->whatsapp_popup_status,
                'whatsapp_popup_message' => clean($request->whatsapp_popup_message)
            ]
        );

        $request->session()->flash('success', 'WhatsApp info updated successfully!');

        return "success";
    }

    public function updateDisqus(Request $request)
    {
        $rules = [
            'disqus_status' => 'required',
            'disqus_short_name' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        BasicSetting::where('user_id', Auth::id())->update(
            [
                'disqus_status' => $request->disqus_status,
                'disqus_short_name' => $request->disqus_short_name
            ]
        );

        $request->session()->flash('success', 'Disqus info updated successfully!');

        return "success";
    }


    public function maintenance()
    {
        $data = BasicSetting::where('id', '=', Auth::id())
            ->select('maintenance_img', 'maintenance_status', 'maintenance_msg', 'bypass_token')
            ->first();

        return view('user.basic-settings.maintenance', ['data' => $data]);
    }

    public function updateMaintenance(Request $request)
    {
        $rules = [
            'maintenance_status' => 'required',
            'maintenance_msg' => 'required'
        ];

        $message = [
            'maintenance_msg.required' => 'The maintenance message field is required.'
        ];

        $maintenanceImgURL = $request->maintenance_img;

        if ($request->filled('maintenance_img')) {
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
            $fileExtension = pathinfo($maintenanceImgURL, PATHINFO_EXTENSION);

            $rules['maintenance_img'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                }
            };
        }

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // first, get the maintenance image from db
        $data = BasicSetting::where('user_id', Auth::id())
            ->select('maintenance_img')
            ->first();

        if ($request->has('maintenance_img')) {
            // second, delete the previous maintenance image from local storage
            @unlink(public_path('assets/user/img/' . $data->maintenance_img));

            // third, set a name for the maintenance image and store it to local storage
            $maintenanceImgName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/');

            @mkdir($directory, 0775, true);

            @copy($maintenanceImgURL, $directory . $maintenanceImgName);
        }

        BasicSetting::where('user_id', Auth::id())->update(
            [
                'maintenance_img' => $request->filled('maintenance_img') ? $maintenanceImgName : $data->maintenance_img,
                'maintenance_status' => $request->maintenance_status,
                'maintenance_msg' => Purifier::clean($request->maintenance_msg),
                'bypass_token' => $request->bypass_token
            ]
        );

        $down = "down";
        if ($request->filled('bypass_token')) {
            $down .= " --secret=" . $request->bypass_token;
        }

        if ($request->maintenance_status == 1) {
            @unlink('core/storage/framework/down');
            Artisan::call($down);
        } else {
            Artisan::call('up');
        }

        $request->session()->flash('success', 'Maintenance Info updated successfully!');

        return redirect()->back();
    }

    public function postSettings()
    {
        $data = BasicSetting::where('user_id', \Illuminate\Support\Facades\Auth::id())->select('post_view_type')->first();

        return view('user.post.settings', ['data' => $data]);
    }

    public function updatePostSettings(Request $request)
    {
        $rule = ['post_view_type' => 'required'];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        // store the view type info into db
        BasicSetting::where('user_id', Auth::id())->updateOrInsert(
            ['user_id' => Auth::id()],
            ['post_view_type' => $request->post_view_type]
        );

        $request->session()->flash('success', 'Post settings updated successfully!');

        return redirect()->back();
    }

    public function gallerySettings()
    {
        $data = BasicSetting::where('user_id', Auth::id())
            ->select('gallery_bg', 'gallery_category_status')
            ->first();

        return view('user.gallery.settings', ['data' => $data]);
    }

    public function updateGallerySettings(Request $request)
    {

        $info = BasicSetting::where('user_id', Auth::id())->select('gallery_bg')->first();

        $rules = [
            'gallery_category_status' => 'required',
            'gallery_bg' => function ($attribute, $value, $fail) use ($info, $request) {
                if (empty($info->gallery_bg) && !$request->hasFile('gallery_bg')) {
                    $fail('The gallery background image field is required.');
                }
            }
        ];

        if ($request->hasFile('gallery_bg')) {
            $rules['gallery_bg'] = 'mimes:jpeg,jpg,png,svg,gif';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('gallery_bg')) {
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/' . $info->gallery_bg));

            // second, get image extension
            $bgImgURL = $request->file('gallery_bg');
            $fileExtension = $bgImgURL ? $bgImgURL->getClientOriginalExtension() : null;

            // third, set a name for the image and store it to local storage
            $bgImgName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/');

            @mkdir($directory, 0775, true);

            @copy(str_replace(' ', '%20', $bgImgURL), $directory . $bgImgName);
        }

        // store data into db
        $bs = BasicSetting::where('user_id', Auth::id())->first();
        $bs->gallery_bg = $request->hasFile('gallery_bg') ? $bgImgName : $info->gallery_bg;
        $bs->gallery_category_status = $request->gallery_category_status;
        $bs->save();
        $request->session()->flash('success', 'Gallery settings updated successfully!');

        return redirect()->back();
    }


    public function updatePixel(Request $request)
    {
        $rules = [
            'pixel_status' => 'required',
            'pixel_id' => 'required'
        ];

        $request->validate($rules);

        BasicSetting::where('user_id', Auth::guard('web')->user()->id)->update(
            [
                'pixel_status' => $request->pixel_status,
                'pixel_id' => $request->pixel_id
            ]
        );

        $request->session()->flash('success', 'Facebook Pixel info updated successfully!');

        return back();
    }

    public function updateTawkto(Request $request)
    {


        $rules = [
            'tawkto_status' => 'required',
            'tawkto_direct_chat_link' => 'required'
        ];

        $request->validate($rules);

        BasicSetting::where('user_id', Auth::guard('web')->user()->id)->update(
            [
                'tawkto_status' => $request->tawkto_status,
                'tawkto_direct_chat_link' => $request->tawkto_direct_chat_link
            ]
        );



        $request->session()->flash('success', 'Tawk.to info updated successfully!');

        return back();
    }
}
