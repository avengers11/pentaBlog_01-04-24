<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BackgroundImageController extends Controller
{
  public function index(){

    $data['websiteInfo'] = BasicSetting::where('user_id', Auth::id())->select('theme_version','news_letter_section_bg_image','hero_section_bg_image')->first();
    return view('user.background-image.index', $data);
  }
  public function update(Request $request){
    $heroBg = $request->hero_bg;
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
    $fileExtension = $heroBg ? $heroBg->extension() : null;
    // newslatter section
    $newsLetter = $request->newsletter_bg;
    $fileExtension_news_letter = $newsLetter ? $newsLetter->extension() : null;
    $rules = [];

    $rules['hero_bg'] = [
        'nullable',
        function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
            if (!in_array($fileExtension, $allowedExtensions)) {
                $fail('Only .jpg, .jpeg, .png and .svg file is allowed for thumbnail image.');
            }
        }
    ];
    $rules['newsletter_bg'] = [
        'nullable',
        function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension_news_letter) {
            if (!in_array($fileExtension_news_letter, $allowedExtensions)) {
                $fail('Only .jpg, .jpeg, .png and .svg file is allowed for thumbnail image.');
            }
        }
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $validator->getMessageBag()->add('error', 'true');
        return response()->json($validator->errors());
    }


    $info = BasicSetting::where('user_id', Auth::id())->select('hero_section_bg_image','news_letter_section_bg_image')->first();

    // hero bg image
    if (!empty($heroBg)) {
        @unlink(public_path('assets/user/img/' . $info->hero_section_bg_image));
        // third, set a name for the logo and store it to local storage
        $heroBgImageName =  rand(123,343).time() . '.' . $fileExtension;
        $directory = public_path('./assets/user/img/');
        @mkdir($directory, 0775, true);
        @copy($heroBg, $directory . $heroBgImageName);
        // finally, store the logo into db
        BasicSetting::where('user_id', Auth::id())
            ->update(['hero_section_bg_image' => $heroBgImageName]);
    }
       // Newslatter bg image
    if (!empty($newsLetter)) {
    @unlink(public_path('assets/user/img/' . $info->news_letter_section_bg_image));
    // third, set a name for the logo and store it to local storage
    $newsLetterBgImageName = rand(123,343).time() . '.' . $fileExtension_news_letter;
    $directory = public_path('./assets/user/img/');
    @mkdir($directory, 0775, true);
    @copy($newsLetter, $directory . $newsLetterBgImageName);
    // finally, store the logo into db
    BasicSetting::where('user_id', Auth::id())
        ->update(['news_letter_section_bg_image' => $newsLetterBgImageName]);

    }
    $request->session()->flash('success', 'Background Image updated successfully!');
    return "success";
  }
}
