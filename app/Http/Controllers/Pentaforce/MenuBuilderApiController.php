<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use App\Models\User\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\User\Language;

class MenuBuilderApiController extends Controller
{
    // MenuShow
    public function MenuShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $lang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        $menu = Menu::query()->where('language_id', $lang->id)
        ->where('user_id', $user->id)
        ->first();
        if(!$menu){
            $menu = new Menu;
            $menu->language_id = $lang->id;
            $menu->user_id = $user->id;
            $menu->menus = json_encode([]);
            $menu->save();
        }

        return response()->json(['menu' => json_decode($menu->menus), "lang" => $lang]);
    }

    // MenuInsert
    public function MenuInsert(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        Menu::where('language_id', $request->language_id)
        ->where('user_id', $user->id)
        ->delete();

        $menu = new Menu;
        $menu->language_id = $request->language_id;
        $menu->user_id = $user->id;
        $menu->menus = isset($request->str) ? json_encode($request->str) : json_encode([]);
        $menu->save();
        return response()->json(['status' => 'success', 'message' => 'Menu updated successfully!']);
    }
}
