<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\SliderImage;
use App\Models\User\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Validator;

class SliderImageController extends Controller
{
    public function index(Request $request)
    {
        // then, get the slider version info of that language from db
        $information['sliders'] = SliderImage::where('user_id',Auth::id())
                                              ->orderBy('id', 'desc')
                                              ->get();
        return view('user.about-me.slider-images.index', $information);
    }

    public function store(Request $request): string
    {
        $request->validate(
            [
                'serial_number' => 'required',
                'image' => 'required|mimes:jpeg,jpg,png'
            ],[
                'serial_number.required' => 'The serial number field is required.',
                'image.required' => 'The image field is required'
            ]
        );
        if ($request->hasFile('image')) {
            $request['image_name'] = Uploader::upload_picture(public_path('assets/user/img/authors/slider-images'), $request->file('image'));
        }
        SliderImage::create($request->except( 'image', 'user_id') + [
                'image' => $request->image_name,
                'user_id' => Auth::id(),
        ]);
        $request->session()->flash('success', 'New slider added successfully!');
        return 'success';
    }

    public function update(Request $request)
    {
        $rules = [
            'serial_number' => 'required'
        ];
        if ($request->hasFile('image')) {
            $imgURL = $request->image;
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
            $fileExtension = $imgURL ? $imgURL->extension() : null;

            $rules['image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                }
            };
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator->getMessageBag()->add('error', 'true')->toArray();
        }
        $slider = SliderImage::findOrFail($request->id);
        $request['image_name'] = $slider->image;
        if ($request->hasFile('image')) {
            $request['image_name'] = Uploader::update_picture(public_path('assets/user/img/authors/slider-images'), $request->file('image'), $slider->image);
        }
        $slider->update($request->except('image') + [
                'image' => $request->image_name,
        ]);
        $request->session()->flash('success', 'Slider info updated successfully!');
        return "success";
    }

    public function destroy(Request $request)
    {
        $slider = SliderImage::findOrFail($request->slider_id);
        if (!is_null($slider->img) && file_exists(public_path('./assets/user/img/authors/slider-images/') . $slider->image)){
            unlink(public_path('./assets/user/img/authors/slider-images/') . $slider->image);
        }
        $slider->delete();
        $request->session()->flash('success', 'Slider deleted successfully!');
        return redirect()->back();
    }
}
