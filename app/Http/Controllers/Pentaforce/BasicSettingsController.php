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

class BasicSettingsController extends Controller
{
    /*
    ===========================
    Preferences
    ===========================
    */
    public function preferencesShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        return response()->json($user);
    }
    public function preferencesUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $user->online_status = $request->online_status;
        $user->listing_page = $request->listing_page;
        $user->save();

        return response()->json(['success' => 'Preferences updated successfully!']);
    }

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
            return redirect()->back()->withErrors($validator->errors());
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
        $data = BasicSetting::where('user_id', $user->id)
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
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
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
    logo
    ===========================
    */
    public function logoShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data = BasicSetting::where('user_id', $user->id)
            ->select('logo')
            ->first();
        return response()->json($data);
    }
    public function logoUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $settings = BasicSetting::where('user_id', $user->id)->first();
        if ($settings->logo != null) {
            Storage::delete($settings->logo);
        }
        $settings->logo = $request->logo;
        $settings->save();

        return response()->json(['success' => 'Logo updated successfully!']);
    }

    /*
    ===========================
    favicon
    ===========================
    */
    public function faviconShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data = BasicSetting::where('user_id', $user->id)
            ->select('favicon')
            ->first();
        return response()->json($data);
    }
    public function faviconUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $settings = BasicSetting::where('user_id', $user->id)->first();
        if ($settings->favicon != null) {
            Storage::delete($settings->favicon);
        }
        $settings->favicon = $request->favicon;
        $settings->save();

        return response()->json(['success' => 'Logo updated successfully!']);
    }

    /*
    ===========================
    preloader
    ===========================
    */
    public function preloaderShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data = BasicSetting::where('user_id', $user->id)
            ->select('preloader', 'preloader_status')
            ->first();
        return response()->json($data);
    }
    public function preloaderUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $settings = BasicSetting::where('user_id', $user->id)->first();
        if ($request->preloader != null) {
            // Storage::delete($settings->preloader);
        }
        $settings->preloader = $request->preloader != null ? $request->preloader : $settings->preloader;
        $settings->preloader_status = $request->preloader_status;
        $settings->save();

        return response()->json(['success' => 'Logo updated successfully!']);
    }

}
