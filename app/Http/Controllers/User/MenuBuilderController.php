<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\Language;
use App\Models\User\Menu;
use App\Models\User\Page;
use Auth;
use Illuminate\Support\Facades\DB;

class MenuBuilderController extends Controller
{

    public function index(Request $request) {
        $lang = Language::query()->where('code', $request->language)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();
        $data['lang_id'] = $lang->id;

        $data['keywords'] = json_decode($lang->keywords, true);

        // get previous menus
        $menu = Menu::query()->where('language_id', $lang->id)
            ->where('user_id', Auth::user()->id)
            ->first();
        $data['prevMenu'] = '';

        if (!empty($menu)) {
            $data['prevMenu'] = $menu->menus;
        }
        $data['apages'] = DB::table('user_pages')
            ->join('user_page_contents', 'user_pages.id', '=', 'user_page_contents.page_id')
            ->where('user_page_contents.language_id', '=', $lang->id)
            ->where('user_page_contents.user_id', '=', Auth::id())
            ->orderByDesc('user_pages.id')
            ->get();

        return view('user.menu_builder.index', $data);
    }

    public function update(Request $request) {
        Menu::query()->where('language_id', $request->language_id)
            ->where('user_id', Auth::user()->id)
            ->delete();

        $menu = new Menu;
        $menu->language_id = $request->language_id;
        $menu->user_id = Auth::user()->id;
        $menu->menus = $request->str;
        $menu->save();

        return response()->json(['status' => 'success', 'message' => 'Menu updated successfully!']);
    }
}

