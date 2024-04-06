<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;


use App\Models\User\CookieAlert;
use App\Models\User\Language;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CookieAlertController extends Controller
{
    public function cookieAlert(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', Auth::id())->first();
        $information['language'] = $language;

        // then, get the cookie alert info of that language from db
        $information['data'] = CookieAlert::where('language_id', $language->id)->first();

        // get all the languages from db
        $information['langs'] = Language::where('user_id', Auth::id())->get();

        return view('user.basic-settings.cookie-alert', $information);
    }

    public function updateCookieAlert(Request $request)
    {
        $rules = [
            'cookie_alert_status' => 'required',
            'cookie_alert_btn_text' => 'required',
            'cookie_alert_text' => 'required'
        ];

        $message = [
            'cookie_alert_btn_text.required' => 'The cookie alert button text field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', Auth::id())->first();

        // then, get the cookie alert info of that language from db

        CookieAlert::updateOrCreate([
            'user_id' => Auth::id(),
            'language_id' => $language->id
        ], $request->except('language_id', 'user_id') + [
                'user_id' => Auth::id(),
                'language_id' => $language->id,
                'cookie_alert_text' => clean($request->cookie_alert_text)
        ]);

        $request->session()->flash('success', 'Cookie alert info updated successfully!');

        return 'success';
    }
}
