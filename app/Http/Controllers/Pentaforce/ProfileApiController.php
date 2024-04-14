<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Crypt;
use Illuminate\Support\Facades\Storage;

class ProfileApiController extends Controller
{
    // getProfileData
    public function getProfileData($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        return response()->json($user);
    }

    // profileUpdate
    public function profileUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $input = $request->all();
        if($request->photo != null){
            $input['photo'] = $request->photo;
            if ($user->photo != null) {
                Storage::delete($user->photo);
            }
        }else{
            $input['photo'] = $user->photo;
        }
        $data = $user;
        $data->update($input);
        return response()->json(['success' => 'You are successfully update your profile!'], 200);
    }
}
