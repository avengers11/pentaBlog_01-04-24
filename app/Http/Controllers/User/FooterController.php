<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\BasicSetting;
use App\Models\User\FooterQuickLink;
use App\Models\User\FooterText;
use App\Models\User\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FooterController extends Controller
{
    public function footerText(Request $request)
    {
        // first, get the language info from db
        if ($request->has('language')) {
            $lang = Language::where([
                ['code', $request->language],
                ['user_id', Auth::id()]
            ])->first();
            Session::put('currentLangCode', $request->language);
        } else {
            $lang = Language::where([
                ['is_default', 1],
                ['user_id', Auth::id()]
            ])
                ->first();
            Session::put('currentLangCode', $lang->code);
        }

        // then, get the footer text info of that language from db
        $information['data'] = FooterText::where('language_id', $lang->id)->where('user_id', Auth::id())->first();
        return view('user.footer.text', $information);
    }

    public function updateFooterInfo(Request $request, $language)
    {
        $lang = Language::where('code', $language)->where('user_id', Auth::id())->firstOrFail();
        $data = FooterText::where('language_id', $lang->id)->where('user_id', Auth::id())->first();
        $theme = BasicSetting::where('user_id', Auth::id())->first()->theme_version;
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

        if ($theme != 4) {
            $message['logo.required'] = 'The logo field is required';
            if(is_null($data)){
                $rules['logo'] = 'required|mimes:jpeg,jpg,png|max:1000';
            }
            elseif (is_null($data->logo) && !$request->hasFile('logo')) {
                $rules['logo'] = 'required|mimes:jpeg,jpg,png|max:1000';
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        if ($theme != 4) {
            $request['image_name'] = $data->logo;
            if ($request->hasFile('logo')) {
                $request['image_name'] = Uploader::update_picture(public_path('assets/user/img/footer/'), $request->file('logo'), $data->logo);
            }
            $data->logo =  $request->image_name;
        }

        $data->language_id =  $lang->id;
        $data->copyright_text =  clean($request->copyright_text);
        $data->user_id = Auth::id();
        $data->about_company = $request->about_company;
        $data->save();
        $request->session()->flash('success', 'Footer text info updated successfully!');
        return 'success';
    }


    public function quickLinks(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)
                            ->where('user_id',Auth::id())
                            ->firstOrFail();

        // then, get the footer quick link info of that language from db
        $information['links'] = FooterQuickLink::where('language_id', $language->id)
                                                ->where('user_id', Auth::id())
                                                ->orderBy('id', 'desc')
                                                ->get();

        // also, get all the languages from db
        $information['langs'] = Language::where('user_id', Auth::id())->get();

        return view('user.footer.quick_links', $information);
    }

    public function storeQuickLink(Request $request, $language)
    {
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
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        FooterQuickLink::create($request->except('language_id','user_id') + [
                'language_id' => $request->user_language_id,
                'user_id' => Auth::id(),
            ]);

        $request->session()->flash('success', 'New quick link added successfully!');

        return 'success';
    }

    public function updateQuickLink(Request $request)
    {
        $rules = [
            'title' => 'required',
            'url' => 'required',
            'serial_number' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        FooterQuickLink::findOrFail($request->link_id)->update($request->all());

        $request->session()->flash('success', 'Quick link updated successfully!');

        return 'success';
    }

    public function deleteQuickLink(Request $request)
    {
        FooterQuickLink::findOrFail($request->link_id)->delete();

        $request->session()->flash('success', 'Quick link deleted successfully!');

        return redirect()->back();
    }
}
