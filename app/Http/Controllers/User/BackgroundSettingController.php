<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BackgroundSettingController extends Controller
{
    public function index(Request $request){
        $language = Language::where('code', $request->language)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->firstOrFail();
        $information['language'] = $language;


    }
    public function update(Request $request){

    }
}
