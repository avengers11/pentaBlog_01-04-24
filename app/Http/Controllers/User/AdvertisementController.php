<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Advertisement;
use App\Models\User\BasicSetting;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;

class AdvertisementController extends Controller
{
    public function index()
    {
        $ads = Advertisement::where('user_id',Auth::id())->orderBy('id', 'desc')->get();
        return view('user.advertisement.index', compact('ads'));
    }

    public function settings()
    {
        $data =BasicSetting::where('user_id', Auth::id())
                           ->select('adsense_publisher_id')
                           ->first();
        return view('user.advertisement.settings', compact('data'));
    }

    public function updateSettings(Request $request) {
        $request->validate([
            'adsense_publisher_id' => 'required'
        ],[
           'adsense_publisher_id.required' => 'The publisher field is required'
        ]);
        BasicSetting::where('user_id', Auth::id())->update(['adsense_publisher_id' => $request->adsense_publisher_id]);
        $request->session()->flash('success', 'Settings updated successfully!');
        return back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required_if:ad_type,==,banner',
            'ad_slot' => 'required_if:ad_type,==,script',
            'image' => 'required_if:ad_type,==,banner|mimes:jpeg,jpg,png,svg,gif'
        ],[
          'url.required_if' => 'The URL field is required',
          'ad_slot.required_if' => 'The ad slot field is required',
          'image.required_if' => 'The image field is required',
          'image.mimes' => 'Only JPG, PNG, JPEG, SVG, GIF Images are allowed',
        ]);

        if ($request->hasFile('image')) {
            // get image extension
            $imageURL = $request->image;
            $fileExtension = $imageURL->extension();

            // set a name for the image and store it to local storage
            $imageName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/advertisements/');

            @mkdir($directory, 0775, true);

            @copy($imageURL, $directory . $imageName);
        }

        Advertisement::create($request->except('image', 'ad_slot') + [
                'image' => $request->hasFile('image') ? $imageName : null,
                'ad_slot' => $request->filled('ad_slot') ? $request->ad_slot : null,
                'user_id' => Auth::id()
        ]);

        $request->session()->flash('success', 'New advertisement added successfully!');

        return 'success';
    }

    public function update(Request $request)
    {
        $ad = Advertisement::find($request->id);

        $request->validate([
            'url' => 'required_if:ad_type,==,banner',
            'ad_slot' => 'required_if:ad_type,==,script'
        ],[
            'url.required_if' => 'The URL field is required',
            'ad_slot.required_if' => 'The ad slot field is required'
        ]);

        if ($request->hasFile('image') || $request->ad_type === 'script') {
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/advertisements/' . $ad->image));
        }

        if ($request->hasFile('image')) {
            // get image extension
            $imageURL = $request->image;
            $fileExtension = $imageURL->extension();

            // set a name for the image and store it to local storage
            $imageName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/advertisements/');

            @copy($imageURL, $directory . $imageName);
        }

        $ad->update($request->except('image', 'script') + [
                'image' => $request->hasFile('image') ? $imageName : $ad->image,
                'ad_slot' => $request->filled('ad_slot') ? $request->ad_slot : $ad->ad_slot
            ]);

        $request->session()->flash('success', 'Advertisement updated successfully!');

        return 'success';
    }

    public function destroy($id)
    {
        $ad = Advertisement::find($id);
        if ($ad->ad_type == 'banner') {
            @unlink(public_path('assets/user/img/advertisements/' . $ad->image));
        }
        $ad->delete();
        return redirect()->back()->with('success', 'Advertisement deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $ad = Advertisement::find($id);
            if ($ad->ad_type == 'banner') {
                @unlink(public_path('assets/user/img/advertisements/' . $ad->image));
            }
            $ad->delete();
        }
        $request->session()->flash('success', 'Advertisements deleted successfully!');
        return 'success';
    }
}
