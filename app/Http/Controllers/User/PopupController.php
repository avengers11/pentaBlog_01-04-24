<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Language;
use App\Models\User\Popup;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', Auth::id())->first();
        $information['language'] = $language;
        $information['popups'] = Popup::where('language_id', $language->id)
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();
        // also, get all the languages from db
        $information['langs'] = Language::where('user_id', Auth::id())->get();
        return view('user.popup.index', $information);
    }
    public function popupType()
    {
        return view('user.popup.popup-type');
    }
    public function create($type)
    {
        $information['popupType'] = $type;
        // get all the languages from db
        $information['languages'] = Language::where('user_id', Auth::id())->get();
        return view('user.popup.create', $information);
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'user_language_id' => 'required',
                'type' => 'required',
                'image' => 'required|mimes:jpeg,jpg,png,gif,svg',
                'name' => 'required|max:255',
                'background_color' => 'required_if:type,2|required_if:type,3|required_if:type,7',
                'background_color_opacity' => 'required_if:type,2|required_if:type,3|numeric|between:0,1',
                'title' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7|max:255',
                'text' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7',
                'button_text' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7|max:255',
                'button_color' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7',
                'button_url' => 'required_if:type,2|required_if:type,4|required_if:type,6|required_if:type,7',
                'end_date' => 'required_if:type,6|required_if:type,7|date',
                'end_time' => 'required_if:type,6|required_if:type,7|date_format:h:i A',
                'delay' => 'required|numeric',
                'serial_number' => 'required|numeric'
            ],[
                'language_id.required' => 'The language field is required.'
            ]
        );
        // get image extension
        $imageURL = $request->image;
        $fileExtension = $imageURL ? $imageURL->extension() : null;

        // set a name for the image and store it to local storage
        $imageName = time() . '.' . $fileExtension;
        $directory = public_path('./assets/user/img/popups/');
        @mkdir($directory, 0775, true);
        @copy($imageURL, $directory . $imageName);
        Popup::create($request->except('language_id','image', 'end_date', 'end_time','user_id') + [
                'image' => $imageName,
                'language_id' => $request->user_language_id,
                'end_date' => $request->has('end_date') ? Carbon::parse($request['end_date']) : null,
                'end_time' => $request->has('end_time') ? date('h:i', strtotime($request['end_time'])) : null,
                'user_id' => Auth::id()
            ]);

        $request->session()->flash('success', 'New popup added successfully!');
        return 'success';
    }
    public function updateStatus(Request $request, $id)
    {
        $popup = Popup::where('id',$id)->where('user_id', Auth::id())->first();
        if ($request->status == 1) {
            $popup->update(['status' => 1]);
            $request->session()->flash('success', 'Popup activated successfully!');
        } else {
            $popup->update(['status' => 0]);
            $request->session()->flash('success', 'Popup deactivated successfully!');
        }
        return redirect()->back();
    }

    public function edit($id)
    {
        $popup = Popup::where('id',$id)->where('user_id', Auth::id())->first();
        return view('user.popup.edit', compact('popup'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => $request->has('image') ? 'mimes:jpeg,jpg,png,svg,gif' : '',
            'name' => 'required|max:255',
            'background_color' => 'required_if:type,2|required_if:type,3|required_if:type,7',
            'background_color_opacity' => 'required_if:type,2|required_if:type,3|numeric|between:0,1',
            'title' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7|max:255',
            'text' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7',
            'button_text' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7|max:255',
            'button_color' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7',
            'button_url' => 'required_if:type,2|required_if:type,4|required_if:type,6|required_if:type,7',
            'end_date' => 'required_if:type,6|required_if:type,7|date',
            'end_time' => 'required_if:type,6|required_if:type,7|date_format:h:i A',
            'delay' => 'required|numeric',
            'serial_number' => 'required|numeric'
        ]);
        $popup = Popup::where('id',$id)->where('user_id', Auth::id())->first();

        if ($request->hasFile('image')) {
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/popups/' . $popup->image));

            // second, get image extension
            $imageURL = $request->image;
            $fileExtension = $imageURL ? $imageURL->extension() : null;

            // third, set a name for the image and store it to local storage
            $imageName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/popups/');
            @copy($imageURL, $directory . $imageName);
        }

        $popup->update($request->except('image', 'end_date', 'end_time') + [
                'image' => $request->has('image') ? $imageName : $popup->image,
                'end_date' => $request->has('end_date') ? Carbon::parse($request['end_date']) : null,
                'end_time' => $request->has('end_time') ? date('h:i', strtotime($request['end_time'])) : null
            ]);
        $request->session()->flash('success', 'Popup updated successfully!');
        return 'success';
    }
    public function destroy($id)
    {
        $popup = Popup::where('id',$id)->where('user_id', Auth::id())->first();
        @unlink(public_path('assets/user/img/popups/' . $popup->image));
        $popup->delete();
        return redirect()->back()->with('success', 'Popup deleted successfully!');
    }
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $popup = Popup::where('id',$id)->where('user_id', Auth::id())->first();
            @unlink(public_path('assets/user/img/popups/' . $popup->image));
            $popup->delete();
        }
        $request->session()->flash('success', 'Popups deleted successfully!');
        return 'success';
    }
}
