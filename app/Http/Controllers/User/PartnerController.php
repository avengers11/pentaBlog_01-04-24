<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\Brand;
use App\Models\User\Language;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    public function index()
    {

        // also, get the brand info of that language from db
        $information['brands'] = Brand::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('user.about-me.partners.index', $information);
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_img' => 'required|mimes:jpeg,jpg,png|max:1000',
            'brand_url' => 'required',
            'serial_number' => 'required'
        ],
            [
                'brand_img.required' => 'The brand image field is required.',
                'brand_url.required' => 'The brand url field is required.',
                'serial_number.required' => 'The serial number field is required.'
            ]
        );

        if ($request->hasFile('brand_img')) {
            $request['image_name'] = Uploader::upload_picture(public_path('assets/user/img/brands'), $request->file('brand_img'));
        }
        Brand::create($request->except('language_id', 'brand_img','user_id') + [
                'user_id' => Auth::id(),
                'brand_img' => $request->image_name
            ]);
        $request->session()->flash('success', 'New brand added successfully!');
        return 'success';
    }

    public function update(Request $request)
    {
        $brand = Brand::where('id', $request->brand_id)->first();
        $rules = [
            'brand_url' => 'required',
            'serial_number' => 'required'
        ];
        $messages = [
            'brand_url.required' => 'The brand url field is required.',
            'serial_number.required' => 'The serial number field is required.'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $request['image_name'] = $brand->brand_img;
        if ($request->hasFile('brand_img')) {
            $request['image_name'] = Uploader::update_picture(public_path('assets/user/img/brands'), $request->file('brand_img'), $brand->brand_img);
        }
        $brand->update($request->except('brand_img') + [
                'brand_img' => $request->image_name
            ]);
        $request->session()->flash('success', 'Brand info updated successfully!');
        return 'success';
    }

    public function delete(Request $request)
    {
        $brand = Brand::where('id', $request->brand_id)->first();

        if (!is_null($brand->brand_img) && file_exists(public_path('./assets/user/img/brands/') . $brand->brand_img)) {
            unlink(public_path('./assets/user/img/brands/') . $brand->brand_img);
        }

        $brand->delete();

        $request->session()->flash('success', 'Brand deleted successfully!');

        return redirect()->back();
    }
}
