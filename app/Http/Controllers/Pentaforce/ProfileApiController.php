<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProfileApiController extends Controller
{
    // getProfileData
    public function getProfileData(User $user)
    {
        return response()->json($user);
    }

    // profileUpdate
    public function profileUpdate(Request $request, User $user)
    {
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
        return response()->json([$input]);
    }
}
