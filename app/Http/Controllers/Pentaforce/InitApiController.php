<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class InitApiController extends Controller
{
    //language
    public function language($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $languages = Language::where('user_id', $user->id)
        ->orderByRaw('id = ? DESC', [$user->default_language_id])
        ->latest()
        ->get();
        return response()->json(['languages' => $languages]);
    }
}
