<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Language;
use App\Models\User\SEO;
use Auth;
use Illuminate\Http\Request;

class SEOController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', Auth::id())->first();
        $information['language'] = $language;

        // then, get the seo info of that language from db
        $information['data'] = SEO::where('language_id', $language->id)->where('user_id', Auth::id())->first();

        // get all the languages from db
        $information['langs'] = Language::where('user_id', Auth::id())->get();

        return view('user.basic-settings.seo', $information);
    }

    public function update(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', Auth::id())->first();
        $user = Auth::guard('web')->user();
        if (!empty($user)) {
            $permissions = UserPermissionHelper::packagePermission($user->id);
            $permissions = json_decode($permissions, true);
        }

        $data = [
            'user_id' => Auth::id(),
            'language_id' => $language->id
        ];
        if (!empty($permissions) && in_array('Gallery', $permissions)) {
            $data['meta_keyword_gallery'] = $request->meta_keyword_gallery;
            $data['meta_description_gallery'] = $request->meta_description_gallery;
        }
        if (!empty($permissions) && in_array('FAQ', $permissions)) {
            $data['meta_keyword_faq'] = $request->meta_keyword_faq;
            $data['meta_description_faq'] = $request->meta_description_faq;
        }
        if (!empty($permissions) && in_array('Ecommerce', $permissions)) {
            $data['meta_keyword_shop'] = $request->meta_keyword_shop;
            $data['meta_description_shop'] = $request->meta_description_shop;
            $data['meta_keyword_shop_details'] = $request->meta_keyword_shop_details;
            $data['meta_description_shop_details'] = $request->meta_description_shop_details;
        }

        SEO::updateOrCreate([
            'user_id' => Auth::id(),
            'language_id' => $language->id
        ], $request->except('language_id', 'user_id', 'meta_keyword_gallery', 'meta_description_gallery', 'meta_keyword_faq', 'meta_description_faq') + $data);
        $request->session()->flash('success', 'SEO Informations updated successfully!');

        return "success";
    }
}
