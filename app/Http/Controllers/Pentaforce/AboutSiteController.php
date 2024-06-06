<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use App\Models\User\Brand;
use App\Models\User\Social;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\Information;
use App\Models\User\SliderImage;
use App\Models\User\Testimonial;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\User\TestimonialContent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AboutSiteController extends Controller
{
    /*
    ===============================
                slider
    ===============================
    */
    public function slider(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $information['sliders'] = SliderImage::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        return $information;
    }
    public function addSlider(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'serial_number' => 'required',
            'image' => 'required'
        ];
        $message = [
            'serial_number.required' => 'The serial number field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        SliderImage::create([
                'serial_number' => $request->serial_number,
                'image' => $request->image,
                'user_id' => $user->id,
        ]);
        return response()->json(['success' => 'New slider added successfully!']);
    }
    public function updateSlider(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'serial_number' => 'required',
        ];
        $message = [
            'serial_number.required' => 'The serial number field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $slider = SliderImage::where('user_id', $user->id)->where('id', $request->id)->first();
        if($request->image != null){
            $slider->image = $request->image;
            if ($slider->image != null) {
                // Storage::delete($slider->image);
            }
        }

        $slider->serial_number = $request->serial_number;
        $slider->save();
        return response()->json(['success' => 'Slider info updated successfully!']);
    }
    public function deleteSlider(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $slider = SliderImage::where('user_id', $user->id)->where('id', $request->id)->first();
        if ($slider->image != null) {
            Storage::delete($slider->image);
        }
        $slider->delete();

        return response()->json(['success' => 'Slider deleted successfully!']);
    }


    /*
    ===============================
                information
    ===============================
    */
    public function information(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $languageId = Language::where('user_id', $user->id)->where('is_default', 1)->pluck('id')->first();
        return Information::where('user_id', $user->id)->where('language_id', $languageId)->first();
    }
    public function updateInformation(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'name' => 'required',
            'about' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $languageId = Language::where('user_id', $user->id)->where('is_default', 1)->pluck('id')->first();
        $info = Information::where('language_id', $languageId)->where('user_id', $user->id)->first();


        if($request->image != null){
            $info->image = $request->image;
            if ($info->image != null) {
                // Storage::delete($info->image);
            }
        }

        if($request->video_background_image != null){
            $info->video_background_image = $request->video_background_image;
            if ($info->video_background_image != null) {
                // Storage::delete($info->video_background_image);
            }
        }

        // format video link
        $link = $request->link;

        if (strpos($link, '&') != 0) {
            $link = substr($link, 0, strpos($link, '&'));
        }
        if(is_null($info)){
            $info = new Information;
        }
        $info->language_id = $languageId;
        $info->user_id = $user->id;
        $info->name = $request->name;
        $info->about = Purifier::clean($request->about);
        $info->link = $link;
        $info->save();

        return response()->json(['success' => 'Information updated successfully!']);
    }



    /*
    ===============================
                social
    ===============================
    */
    public function social(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        return Social::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
    }
    public function addSocial(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'icon' => 'required',
            'url' => 'required',
            'serial_number' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $social = new Social;
        $social->icon = $request->icon;
        $social->url = $request->url;
        $social->serial_number = $request->serial_number;
        $social->user_id = $user->id;
        $social->save();

        return response()->json(['success' => 'New link added successfully!']);
    }
    public function updateSocial(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'icon' => 'required',
            'url' => 'required',
            'serial_number' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $social = Social::where('id', $request->id)->where('user_id', $user->id)->first();
        $social->icon = $request->icon;
        $social->url = $request->url;
        $social->serial_number = $request->serial_number;
        $social->user_id = $user->id;
        $social->save();

        return response()->json(['success' => 'Social link updated successfully!']);

    }
    public function deleteSocial(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $social = Social::where('id', $request->id)->where('user_id', $user->id)->first();
        $social->delete();

        return response()->json(['success' => 'Social link deleted successfully!']);
    }


    /*
    ===============================
                testimonials
    ===============================
    */
    public function testimonials(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $testimonialContents['languages'] = Language::where('user_id', $user->id)->get();
        $testimonialContents['data'] = TestimonialContent::with(['testimonialUser' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->where('user_id', $user->id)
        ->orderBy('testimonial_id', 'desc')
        ->get();
        return $testimonialContents;
    }
    public function addTestimonials(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'client_image' => 'required',
            'serial_number' => 'required',
            'rating' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $testimonial = new Testimonial();
        $testimonial->client_image = $request->client_image;
        $testimonial->serial_number = $request->serial_number;
        $testimonial->rating = $request->rating;
        $testimonial->user_id = $user->id;
        $testimonial->save();

        $testimonialContent = new TestimonialContent();
        $testimonialContent->language_id = $request->language_id;
        $testimonialContent->testimonial_id = $testimonial->id;
        $testimonialContent->client_name = $request->client_name;
        $testimonialContent->comment = $request->comment;
        $testimonialContent->user_id = $user->id;
        $testimonialContent->save();

        return response()->json(['success' => 'New testimonial added successfully!']);
    }
    public function updateTestimonials(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $rules = [
            'serial_number' => 'required',
            'rating' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $testimonial = Testimonial::where('id', $request->id)->where('user_id', $user->id)->first();

        if($request->client_image != null){
            $testimonial->client_image = $request->client_image;
            if ($testimonial->client_image != null) {
                // Storage::delete($testimonial->client_image);
            }
        }
        $testimonial->serial_number = $request->serial_number;
        $testimonial->rating = $request->rating;
        $testimonial->save();

        $testimonialContent = TestimonialContent::where('testimonial_id', $testimonial->id)->first();
        $testimonialContent->client_name = $request->client_name;
        $testimonialContent->comment = $request->comment;
        $testimonialContent->save();

        return response()->json(['success' => 'Old testimonial updated successfully!']);
    }
    public function deleteTestimonials(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $testimonial = Testimonial::where('id', $request->id)->where('user_id', $user->id)->first();
        $testimonialContent = TestimonialContent::where('testimonial_id', $testimonial->id)->first();
        $testimonial->delete();
        $testimonialContent->delete();
        return response()->json(['success' => 'Testimonial deleted successfully!']);
    }


    /*
    ===============================
                partners
    ===============================
    */
    public function partners(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $information = Brand::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();
        return $information;
    }
    public function addPartners(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $roles = [
            'brand_img' => 'required',
            'brand_url' => 'required',
            'serial_number' => 'required'
        ];
        $messages = [
            'brand_img.required' => 'The brand image field is required.',
            'brand_url.required' => 'The brand url field is required.',
            'serial_number.required' => 'The serial number field is required.'
        ];

        $validator = Validator::make($request->all(), $roles, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $brand = new Brand;
        $brand->brand_img = $request->brand_img;
        $brand->brand_url = $request->brand_url;
        $brand->serial_number = $request->serial_number;
        $brand->user_id = $user->id;
        $brand->save();

        return response()->json(['success' => 'New brand added successfully!']);
    }
    public function updatePartners(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $user = User::find(Crypt::decrypt($crypt));
        $roles = [
            'brand_url' => 'required',
            'serial_number' => 'required'
        ];
        $messages = [
            'brand_url.required' => 'The brand url field is required.',
            'serial_number.required' => 'The serial number field is required.'
        ];

        $validator = Validator::make($request->all(), $roles, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $brand = Brand::where('id', $request->id)->where('user_id', $user->id)->first();
        if($request->brand_img != null){
            $brand->brand_img = $request->brand_img;
            if ($brand->brand_img != null) {
                // Storage::delete($brand->brand_img);
            }
        }
        $brand->brand_url = $request->brand_url;
        $brand->serial_number = $request->serial_number;
        $brand->save();

        return response()->json(['success' => 'Old brand updated successfully!']);
    }
    public function deletePartners(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $brand = Brand::where('id', $request->id)->where('user_id', $user->id)->first();
        if ($brand->brand_img != null) {
            Storage::delete($brand->brand_img);
        }
        $brand->delete();

        return response()->json(['success' => 'Brand deleted successfully!']);
    }
}
