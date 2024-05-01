<?php

namespace App\Http\Controllers\Pentaforce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;

class SiteManagementApiController extends Controller
{
    //gallery
    public function gallery($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $data['gallery'] = BasicSetting::where('user_id', $user->id)
            ->select('gallery_bg', 'gallery_category_status')
            ->first();

        return response()->json($data);
    }
}
