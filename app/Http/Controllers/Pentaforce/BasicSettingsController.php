<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Validator;

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
            return redirect()->back()->withErrors($validator->errors());
        }

        BasicSetting::where('user_id', $user->id)->update(
            ['theme_version' => $request->theme_version]
        );

        return response()->json(['success' => 'Theme & home version updated successfully!']);
    }

}
