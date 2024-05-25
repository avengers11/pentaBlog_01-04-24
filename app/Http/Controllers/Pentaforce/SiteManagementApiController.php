<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\GalleryItem;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use App\Models\User\GalleryCategory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SiteManagementApiController extends Controller
{
    /*
    ==============================
    Settings
    ==============================
    */
    public function gallery(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $data['gallery'] = BasicSetting::where('user_id', $user->id)
            ->select('gallery_bg', 'gallery_category_status')
            ->first();

        // Gallary settings
        $languageId =   Language::where('user_id', $user->id)->pluck('id')->first();
        $information['items'] = GalleryItem::with('itemCategory')
                                    ->where('language_id', $languageId)
                                    ->where('user_id', $user->id)
                                    ->orderBy('id', 'desc')
                                    ->get();


        $information['langs'] = Language::where('user_id', $user->id)->get();

        // category
        $category = GalleryCategory::where('user_id', $user->id)->get();

        return response()->json(["settings" => $data, "gallery" => $information, "category" => $category]);
    }
    public function gallerySettings(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        // store data into db
        $bs = BasicSetting::where('user_id', $user->id)->first();

        // gallery_img
        if($request->gallery_bg != null){
            $gallery_img = $request->gallery_bg;
            if ($bs->gallery_bg != null) {
                Storage::delete($bs->gallery_bg);
            }
        }else{
            $gallery_img = $bs->gallery_bg;
        }


        $bs->gallery_bg = $gallery_img;
        $bs->gallery_category_status = $request->gallery_category_status;
        $bs->save();

        return response()->json(['success' => 'Gallery settings updated successfully!'], 200);
    }
    /*
    ==============================
    Category
    ==============================
    */
    public function galleryCategory(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $languageId =   Language::where('user_id', $user->id)->pluck('id')->first();
        $information['categories'] = GalleryCategory::where('language_id', $languageId)
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();
        $information['langs'] = Language::where('user_id', $user->id)->get();

        return response()->json([$information]);
    }
    public function galleryCategoryAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'user_language_id' => 'required',
            'name' => 'required',
            'status' => 'required',
            'serial_number' => 'required'
        ];

        $message = [
            'user_language_id.required' => 'The language field is required.',
            'name.required' => 'The name field is required.',
            'status.required' => 'The status field is required.',
            'serial_number.required' => 'The serial number field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $dataType['user_id'] = $user->id;
        $dataType['language_id'] = $request->user_language_id;
        $dataType['name'] = $request->name;
        $dataType['status'] = $request->status;
        $dataType['serial_number'] = $request->serial_number;

        GalleryCategory::create($dataType);

        return response()->json(['success' => 'New gallery category added successfully!'], 200);
    }
    public function galleryCategoryUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $rules = [
            'name' => 'required',
            'status' => 'required',
            'serial_number' => 'required'
        ];

        $messages = [
            'name.required' => 'The name field is required',
            'status.required' => 'The status field is required',
            'serial_number.required' => 'The serial number field is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }
        $dataType['name'] = $request->name;
        $dataType['status'] = $request->status;
        $dataType['serial_number'] = $request->serial_number;
        GalleryCategory::findOrFail($request->id)->update($dataType);

        return response()->json(['success' => 'Gallery category updated successfully!'], 200);
    }
    public function galleryCategoryDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $category = GalleryCategory::where('id', $request->id)->where('user_id', $user->id)->first();

        if ($category->imgVid()->count() > 0) {
            return response()->json(['error' => 'First delete all the items of this category!'], 200);
        } else {
            $category->delete();
            return response()->json(['success' => 'Gallery category deleted successfully!'], 200);
        }
    }


    /*
    ==============================
    Gallery
    ==============================
    */
    public function galleryAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'video_link' => 'required_if:item_type,video',
            'language_id' => 'required',
            'title' => 'required',
            'serial_number' => 'required',
            'image' => 'required',
        ];

        $messages = [
            'language_id.required' => 'The language field is required.',
            'video_link.required_if' => 'The video link field is required.',
            'title.required' => 'The title field is required.',
            'serial_number.required' => 'The serial number field is required.',
            'image.required' => 'The image field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        // format video link
        if ($request->filled('video_link')) {
            $link = $request->video_link;

            if (strpos($link, '&') != 0) {
                $link = substr($link, 0, strpos($link, '&'));
            }
        }

        GalleryItem::create([
            'item_type' => $request->item_type == 'image' ? 'image' : 'video',
            'image' => $request->image,
            'video_link' => $request->filled('video_link') ? $link : null,
            'user_id' => $user->id,
            'language_id' => $request->language_id,
            'serial_number' => $request->serial_number,
            'gallery_category_id' => $request->gallery_category_id,
            'title' => $request->title,
            "is_featured" => $request->is_featured
        ]);

        return response()->json(['success' => 'New gallery item added successfully!'], 200);
    }
    public function galleryUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'video_link' => 'required_if:item_type,video',
            'title' => 'required',
            'serial_number' => 'required',
        ];

        $messages = [
            'video_link.required_if' => 'The video link field is required.',
            'title.required' => 'The title field is required.',
            'serial_number.required' => 'The serial number field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        // format video link
        if ($request->filled('video_link')) {
            $link = $request->video_link;

            if (strpos($link, '&') != 0) {
                $link = substr($link, 0, strpos($link, '&'));
            }
        }

        $galleryItem =  GalleryItem::where('id', $request->id)->where('user_id', $user->id)->first();
        if($request->image != null){
            $img = $request->image;
            if ($galleryItem->image != null) {
                // Storage::delete($galleryItem->image);
            }
        }else{
            $img = $galleryItem->image;
        }

        $galleryItem->item_type = $request->item_type == 'image' ? 'image' : 'video';
        $galleryItem->image = $img;
        $galleryItem->video_link = $request->filled('video_link') ? $link : null;
        $galleryItem->serial_number = $request->serial_number;
        $galleryItem->gallery_category_id = $request->gallery_category_id;
        $galleryItem->title = $request->title;
        $galleryItem->is_featured = $request->is_featured;
        $galleryItem->save();

        return response()->json(['success' => 'Gallery item updated successfully!'], 200);
    }
    public function galleryDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $item = GalleryItem::where('id', $request->id)->where('user_id', $user->id)->first();
        if ($item->image != null) {
            // Storage::delete($item->image);
        }
        $item->delete();

        return response()->json(['success' => 'Gallery items deleted successfully!'], 200);

    }

}
