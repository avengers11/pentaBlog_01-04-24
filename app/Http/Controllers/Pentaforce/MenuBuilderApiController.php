<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuBuilderApiController extends Controller
{
    // MenuShow
    public function MenuShow(Request $request, User $user)
    {
        $menu = Menu::query()->where('language_id', $request->language_id)
        ->where('user_id', $user->id)
        ->first();

        return response()->json(['menu' => json_decode($menu->menus)]);
    }

    // MenuInsert
    public function MenuInsert(Request $request, User $user)
    {
        Menu::query()->where('language_id', $request->language_id)
        ->where('user_id', $user->id)
        ->delete();

        $menu = new Menu;
        $menu->language_id = $request->language_id;
        $menu->user_id = $user->id;
        $menu->menus = json_encode($request->str);
        $menu->save();
        return response()->json(['status' => 'success', 'message' => 'Menu updated successfully!']);
    }
}
