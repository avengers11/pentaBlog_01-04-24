<?php

namespace App\Http\Controllers\Pentaforce;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\HomeSection;
use App\Models\User\Language;
use App\Models\User\PageHeading;
use App\Models\User\SEO;
use App\Models\User\FooterText;
use App\Models\User\FooterQuickLink;

class BasicSettingsController extends Controller
{
    /*
    ===========================
    Theme
    ===========================
    */
    public function themeShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data = BasicSetting::where('user_id', $user->id)
        ->select('theme_version')
        ->first();

        return response()->json($data);
    }
    public function themeUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'theme_version' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        BasicSetting::where('user_id', $user->id)->update(
            ['theme_version' => $request->theme_version]
        );

        return response()->json(['success' => 'Theme & home version updated successfully!']);
    }

    /*
    ===========================
    general
    ===========================
    */
    public function generalShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data['basic'] = BasicSetting::where('user_id', $user->id)
            ->first();

        return response()->json($data);
    }
    public function generalUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

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
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }


        BasicSetting::where('user_id', $user->id)->update([
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

        return response()->json(['success' => 'Information updated successfully!']);
    }

    /*
    ===========================
    Website Appearance
    ===========================
    */
    public function appearanceShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data['basic'] = BasicSetting::where('user_id', $user->id)
            ->first();

        return response()->json($data);
    }
    public function appearanceUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $basic = BasicSetting::where('user_id', $user->id)->first();

        // Logo
        if($request->logo != null){
            $logo = $request->logo;
            if ($user->logo != null) {
                Storage::delete($user->logo);
            }
        }else{
            $logo = $basic->logo;
        }

        // favicon
        if($request->favicon != null){
            $favicon = $request->favicon;
            if ($user->favicon != null) {
                Storage::delete($user->favicon);
            }
        }else{
            $favicon = $basic->favicon;
        }

        // preloader
        if($request->preloader != null){
            $preloader = $request->preloader;
            if ($user->preloader != null) {
                Storage::delete($user->preloader);
            }
        }else{
            $preloader = $basic->preloader;
        }

        // breadcrumb
        if($request->breadcrumb != null){
            $breadcrumb = $request->breadcrumb;
            if ($user->breadcrumb != null) {
                Storage::delete($user->breadcrumb);
            }
        }else{
            $breadcrumb = $basic->breadcrumb;
        }

        BasicSetting::where('user_id', $user->id)->update([
            'logo' => $logo,
            'favicon' => $favicon,
            'preloader' => $preloader,
            'breadcrumb' => $breadcrumb,
            'preloader_status' => $request->preloader_status,
            'cookie_status' => $request->cookie_status,
            'primary_color' => ltrim($request->primary_color, '#'),
            'breadcrumb_overlay_color' => ltrim($request->breadcrumb_overlay_color, '#'),
            'breadcrumb_overlay_opacity' => $request->breadcrumb_overlay_opacity,

            // logo to txt 
            'text_to_logo' => $request->text_to_logo,
            'text_to_logo_status' => $request->text_to_logo_status,
        ]);

        return response()->json(['success' => 'Website appearance updated successfully!']);
    }

    /*
    ===========================
    SEO & Headings
    ===========================
    */
    public function homeSectionsShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data['hs'] = HomeSection::where('user_id', $user->id)->first();
        $data['websiteInfo'] = BasicSetting::where('user_id', $user->id)->select('theme_version')->first();

        return response()->json($data);
    }
    public function homeSectionsUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        HomeSection::where('user_id', $user->id)->update([
            'slider_posts' => $request->slider_posts,
            'latest_posts' => $request->latest_posts,
            'author_info' => $request->author_info,
            'popular_posts' => $request->popular_posts,
            'newsletter' => $request->newsletter,
            'sidebar_ads' => $request->sidebar_ads,
            'featured_category_posts' => $request->featured_category_posts,
            'footer' => $request->footer,
        ]);

        return response()->json(['success' => 'Home sections updated successfully!']);
    }

    // pageSectionsShow
    public function pageSectionsShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $language = Language::where('code', $request->language)
            ->where('user_id', $user->id)
            ->firstOrFail();
        $information['language'] = $language;
        $information['data'] = PageHeading::where('language_id', $language->id)->where('user_id', $user->id)->first();
        $information['langs'] = Language::where('user_id', $user->id)->get();

        return response()->json($information);
    }
    public function pageSectionsUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        if (!empty($user)) {
            $permissions = UserPermissionHelper::packagePermission($user->id);
            $permissions = json_decode($permissions, true);
        }
        $language = Language::where('code', $request->language)
        ->where('user_id', $user->id)
        ->first();

        $data = [
            'about_me_title' => $request->about_me_title,
            'posts_title' => $request->posts_title,
            'post_details_title' => $request->post_details_title,
            'contact_me_title' => $request->contact_me_title,
            'error_page_title' => $request->error_page_title,
            'shop' => $request->shop,
            'shop_details' => $request->shop_details,
            'cart' => $request->cart,
            'checkout' => $request->checkout,
            'user_id' => $user->id,
            'language_id' => $language->id
        ];
        if (!empty($permissions) && in_array('Gallery', $permissions)) {
            $data['gallery_title'] = $request->gallery_title;
        }
        if (!empty($permissions) && in_array('FAQ', $permissions)) {
            $data['faq_title'] = $request->faq_title;
        }

        PageHeading::updateOrCreate([
            'user_id' => $user->id,
            'language_id' => $language->id
        ], $data);

        return response()->json(['success' => 'Page heading updated successfully!']);
    }

    // seoInfoShow
    public function seoInfoShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $language = Language::where('code', $request->language)->where('user_id', $user->id)->first();
        $information['language'] = $language;

        $information['data'] = SEO::where('language_id', $language->id)->where('user_id', $user->id)->first();

        $information['langs'] = Language::where('user_id', $user->id)->get();

        return response()->json($information);
    }
    public function seoInfoUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        if (!empty($user)) {
            $permissions = UserPermissionHelper::packagePermission($user->id);
            $permissions = json_decode($permissions, true);
        }
        $language = Language::where('code', $request->language)
        ->where('user_id', $user->id)
        ->first();

        $data = [
            "meta_keyword_home" => $request->meta_keyword_home,
            "meta_keyword_about" => $request->meta_keyword_about,
            "meta_description_home" => $request->meta_description_home,
            "meta_description_about" => $request->meta_description_about,
            "meta_keyword_gallery" => $request->meta_keyword_gallery,
            "meta_keyword_posts" => $request->meta_keyword_posts,
            "meta_description_gallery" => $request->meta_description_gallery,
            "meta_description_posts" => $request->meta_description_posts,
            "meta_keyword_faq" => $request->meta_keyword_faq,
            "meta_keyword_contact" => $request->meta_keyword_contact,
            "meta_description_faq" => $request->meta_description_faq,
            "meta_description_contact" => $request->meta_description_contact,
            "meta_keyword_login" => $request->meta_keyword_login,
            "meta_keyword_signup" => $request->meta_keyword_signup,
            "meta_description_login" => $request->meta_description_login,
            "meta_description_signup" => $request->meta_description_signup,
            "meta_keyword_forget_password" => $request->meta_keyword_forget_password,
            "meta_keyword_shop" => $request->meta_keyword_shop,
            "meta_description_forget_password" => $request->meta_description_forget_password,
            "meta_description_shop" => $request->meta_description_shop,
            "meta_keyword_shop_details" => $request->meta_keyword_shop_details,
            "meta_description_shop_details" => $request->meta_description_shop_details,
        ];
        if (!empty($permissions) && in_array('Gallery', $permissions)) {
            $data['gallery_title'] = $request->gallery_title;
        }
        if (!empty($permissions) && in_array('FAQ', $permissions)) {
            $data['faq_title'] = $request->faq_title;
        }

        SEO::updateOrCreate([
            'user_id' => $user->id,
            'language_id' => $language->id
        ], $data);

        return response()->json(['success' => 'Page heading updated successfully!']);
    }

    // Plugins
    public function pluginsShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data = BasicSetting::where('user_id', $user->id)
            ->first();

        return response()->json($data);
    }
    public function pluginsUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $basic = BasicSetting::where('user_id', $user->id)->first();

        if($request->type == 1){
            $basic->analytics_status = $request->analytics_status;
            $basic->measurement_id = $request->measurement_id;
        }else if($request->type == 2){
            $basic->is_recaptcha = $request->is_recaptcha;
            $basic->google_recaptcha_site_key = $request->google_recaptcha_site_key;
            $basic->google_recaptcha_secret_key = $request->google_recaptcha_secret_key;
        }else if($request->type == 3){
            $basic->disqus_status = $request->disqus_status;
            $basic->disqus_short_name = $request->disqus_short_name;
        }else if($request->type == 4){
            $basic->whatsapp_status = $request->whatsapp_status;
            $basic->whatsapp_number = $request->whatsapp_number;
            $basic->whatsapp_header_title = $request->whatsapp_header_title;
            $basic->whatsapp_popup_status = $request->whatsapp_popup_status;
            $basic->whatsapp_popup_message = $request->whatsapp_popup_message;
        }else if($request->type == 5){
            $basic->tawkto_status = $request->tawkto_status;
            $basic->tawkto_direct_chat_link = $request->tawkto_direct_chat_link;
        }else{

        }
        $basic->save();

        return response()->json(['success' => 'You are successfully update plugins settings!']);
    }

    // footerShow
    public function footerShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        // footer
        $langId = Language::where('user_id', $user->id)->where('is_default', 1)->firstOrFail()->id;

        $data['text'] = FooterText::where('language_id', $langId)->where('user_id', $user->id)->first();

        // footer quick link
        $data['links'] = FooterQuickLink::where('language_id', $langId)
                        ->where('user_id', $user->id)
                        ->orderBy('id', 'desc')
                        ->get();
        $data['langs'] = Language::where('user_id', $user->id)->get();

        return response()->json($data);
    }
    public function footerTextUpdateShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $langId = Language::where('user_id', $user->id)->where('is_default', 1)->firstOrFail()->id;
        $data = FooterText::where('language_id', $langId)->where('user_id', $user->id)->first();
        // $theme = BasicSetting::where('user_id', $user->id)->first()->theme_version;

        if(is_null($data))
        {
            $data = new FooterText;
        }
        $rules = [
            'about_company' => 'required',
            'copyright_text' => 'required'
        ];
        $message = [
            'about_company.required' => 'The about company field is required',
            'copyright_text.required' => 'The copy right text field is required'
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        if($request->logo != null){
            $data->logo =  $request->logo;
            if ($data->logo != null) {
                // Storage::delete($data->logo);
            }
        }

        $data->language_id =  $langId;
        $data->copyright_text =  clean($request->copyright_text);
        $data->user_id = $user->id;
        $data->about_company = $request->about_company;
        $data->save();

        return response()->json(['success' => 'You are successfully update plugins settings!']);
    }

    public function footerQuickAddShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'title' => 'required',
            'user_language_id' => 'required',
            'url' => 'required',
            'serial_number' => 'required'
        ];
        $message = [
            'user_language_id.required' => 'The language field is required.',
            'title.required' => 'The title field is required',
            'url.required' => 'The url field is required',
            'serial_number.required' => 'The serial number field is required',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        FooterQuickLink::create($request->except('language_id','user_id') + [
                'language_id' => $request->user_language_id,
                'user_id' => $user->id,
            ]);

        return response()->json(['success' => 'You are successfully add quick links settings!']);
    }
    public function footerQuickUpdateShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'title' => 'required',
            'url' => 'required',
            'serial_number' => 'required'
        ];
        $message = [
            'title.required' => 'The title field is required',
            'url.required' => 'The url field is required',
            'serial_number.required' => 'The serial number field is required',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $data = $request->except('link_id');
        FooterQuickLink::where('id', $request->link_id)->where('user_id', $user->id)->update($data);

        return response()->json(['success' => 'Quick link updated successfully!']);
    }
    public function footerQuickDeleteShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        FooterQuickLink::where('id', $request->link_id)->where('user_id', $user->id)->delete();

        return response()->json(['success' => 'Quick link deleted successfully!']);
    }
}
