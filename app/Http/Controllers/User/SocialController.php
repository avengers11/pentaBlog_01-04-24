<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SocialController extends Controller
{
    public function index() {
        $data['socials'] = Social::where('user_id',Auth::id())
                                   ->orderBy('id', 'DESC')
                                   ->get();
        return view('user.about-me.social.index', $data);
    }

    public function store(Request $request) {
        $request->validate([
            'icon' => 'required',
            'url' => 'required',
            'serial_number' => 'required|integer',
        ]);

        $social = new Social;
        $social->icon = $request->icon;
        $social->url = $request->url;
        $social->serial_number = $request->serial_number;
        $social->user_id = Auth::id();
        $social->save();

        Session::flash('success', 'New link added successfully!');
        return back();
    }

    public function edit($id) {
        $data['social'] = Social::findOrFail($id);
        return view('user.about-me.social.edit', $data);
    }

    public function update(Request $request) {
        $request->validate([
            'icon' => 'required',
            'url' => 'required',
            'serial_number' => 'required|integer',
        ]);

        $social = Social::findOrFail($request->socialid);
        $social->icon = $request->icon;
        $social->url = $request->url;
        $social->serial_number = $request->serial_number;
        $social->user_id = Auth::id();
        $social->save();

        Session::flash('success', 'Social link updated successfully!');
        return back();
    }

    public function delete(Request $request) {

        Social::findOrFail($request->social_id)->delete();
        Session::flash('success', 'Social link deleted successfully!');
        return back();
    }
}
