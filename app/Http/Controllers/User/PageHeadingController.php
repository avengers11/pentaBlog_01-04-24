<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Language;
use App\Models\User\PageHeading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PageHeadingController extends Controller
{
    public function pageHeadings(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->firstOrFail();
        $information['language'] = $language;

        // then, get the page headings info of that language from db
        $information['data'] = PageHeading::where('language_id', $language->id)->where('user_id', Auth::guard('web')->user()->id)->first();

        // get all the languages from db
        $information['langs'] = Language::where('user_id', Auth::guard('web')->user()->id)->get();

        return view('user.basic-settings.page-headings', $information);
    }

    public function updatePageHeadings(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!empty($user)) {
            $permissions = UserPermissionHelper::packagePermission($user->id);
            $permissions = json_decode($permissions, true);
        }
        $rules = [
            'about_me_title' => 'required',
            'posts_title' => 'required',
            'post_details_title' => 'required',
            'contact_me_title' => 'required',
            'error_page_title' => 'required',
            'shop' => 'required',
            'shop_details' => 'required',
            'cart' => 'required',
            'checkout' => 'required'
        ];
        if (!empty($permissions) && in_array('Gallery', $permissions)) {
            $rules['gallery_title'] = 'required';
        }
        if (!empty($permissions) && in_array('FAQ', $permissions)) {
            $rules['faq_title'] = 'required';
        }
        if (!empty($permissions) && in_array('Ecommerce', $permissions)) {
            $rules['shop'] = 'required';
            $rules['shop_details'] = 'required';
            $rules['cart'] = 'required';
            $rules['checkout'] = 'required';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        // first, get the language info from db
        $language = Language::where('code', $request->language)
            ->where('user_id', Auth::guard('web')->user()->id)
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
            'user_id' => Auth::guard('web')->user()->id,
            'language_id' => $language->id
        ];
        if (!empty($permissions) && in_array('Gallery', $permissions)) {
            $data['gallery_title'] = $request->gallery_title;
        }
        if (!empty($permissions) && in_array('FAQ', $permissions)) {
            $data['faq_title'] = $request->faq_title;
        }
        PageHeading::updateOrCreate([
            'user_id' => Auth::guard('web')->user()->id,
            'language_id' => $language->id
        ], $data);

        $request->session()->flash('success', 'Page headings updated successfully!');
        return "success";
    }
}
