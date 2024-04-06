<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\User\Information;
use App\Models\User\Language;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class InformationController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)
                            ->where('user_id', Auth::id())
                            ->first();
        $information['language'] = $language;
        // then, get the page headings info of that language from db
        $information['data'] = Information::where('language_id', $language->id)
                                          ->where('user_id', Auth::id())
                                          ->first();
        // get all the languages from db
        $information['langs'] = Language::where('user_id', Auth::id())
                                         ->get();

        return view('user.about-me.information', $information);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'about' => 'required'
        ];

        // first, get the language info from db
        $lang = Language::where('code', $request->language)
                        ->where('user_id', Auth::id())
                        ->first();

        // then, get the author info of that language from db
        $info = Information::where('language_id', $lang->id)
                           ->where('user_id', Auth::id())
                           ->first();

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');

        // rule for author image
        if (is_null($info) && !$request->hasFile('image')) {
            $rules['image'] = 'required';
        }
        $imageURL = $request->image;

        if ($request->hasFile('image')) {
            $imgExt = $imageURL ? $imageURL->extension() : null;
            $rules['image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $imgExt) {
                if (!in_array($imgExt, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                }
            };
        }
        // rule for video background image
        if ($request->filled('link') && !$request->hasFile('video_background_image') && empty($info->video_background_image)) {
            $rules['video_background_image'] = 'required';
        }

        $vidBackImgURL = $request->video_background_image;

        if ($request->hasFile('video_background_image')) {
            $vidBackImgExt = $vidBackImgURL ? $vidBackImgURL->extension() : null;
            $rules['video_background_image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $vidBackImgExt) {
                if (!in_array($vidBackImgExt, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                }
            };
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/authors/' . $info->image));
            // second, set a name for the iamge and store it to local storage
            $imageName = time() . '.' . $imgExt;
            $dirOne = public_path('./assets/user/img/authors/');
            @mkdir($dirOne, 0775, true);
            @copy($imageURL, $dirOne . $imageName);
        }

        if ($request->hasFile('video_background_image')) {
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/' . $info->video_background_image));
            // second, set a name for the image and store it to local storage
            $vidBackImgName = time() . '.' . $vidBackImgExt;
            $dirTwo = public_path('./assets/user/img/');
            @mkdir($dirTwo, 0775, true);
            @copy($vidBackImgURL, $dirTwo . $vidBackImgName);
        }

        // format video link
        $link = $request->link;

        if (strpos($link, '&') != 0) {
            $link = substr($link, 0, strpos($link, '&'));
        }
        if(is_null($info)){
            $info = new Information;
        }
        $info->language_id = $lang->id;
        $info->user_id = Auth::id();
        $info->image = $request->hasFile('image') ? $imageName : $info->image;
        $info->name = $request->name;
        $info->about = Purifier::clean($request->about);
        $info->video_background_image = $request->hasFile('video_background_image') ?
                                                  $vidBackImgName :
                                                  $info->video_background_image;
        $info->link = $link;
        $info->save();

        $request->session()->flash('success', 'Information updated successfully!');
        return redirect()->back();
    }
}
