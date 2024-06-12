<?php

namespace App\Http\Controllers\Pentaforce;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\BasicExtended;
use App\Models\User\Language;
use App\Models\User\UserItem;
use App\Models\User\UserOrder;
use App\Models\User\UserCoupon;
use App\Models\User\UserItemImage;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\Controller;
use App\Models\User\UserItemContent;
use App\Models\User\UserShopSetting;
use App\Models\User\UserItemCategory;
use Illuminate\Support\Facades\Crypt;
use App\Models\User\UserOfflineGateway;
use App\Models\User\UserPaymentGeteway;
use App\Models\User\UserShippingCharge;
use Illuminate\Support\Facades\Storage;
use App\Models\User\UserItemSubCategory;
use Illuminate\Support\Facades\Validator;

class ShopApiController extends Controller
{
    //settings
    public function settings($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data = UserShopSetting::where('user_id', $user->id)->first();
        return response()->json($data);
    }

    //updateSettings
    public function updateSettings(Request $request, $crypt)
    {
        $validator = Validator::make($request->all(), [
            'is_shop' => 'required',
            'tax' => 'required',
            'item_rating_system' => 'required',
        ]);

        $user = User::find(Crypt::decrypt($crypt));

        // If validation fails, return error response
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $shopsettings = UserShopSetting::where('user_id', $user->id)->first();
        if (!$shopsettings) {
            $shopsettings  = new UserShopSetting();
        }
        $shopsettings->user_id = $user->id;
        $shopsettings->is_shop = $request->is_shop;
        $shopsettings->item_rating_system = $request->item_rating_system;
        $shopsettings->tax = $request->tax ? $request->tax : 0.00;
        $shopsettings->save();
        return response()->json(['success' => 'Shop settings updated successfully'], 200);
    }

    /*
    =================================
    charge
    =================================
    */
    public function charge(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $languageId = Language::where('user_id', $user->id)->where('is_default', 1)->pluck('id')->first();
        $data['shippings'] = UserShippingCharge::where('user_id', $user->id)
            // ->where('language_id', $lang_id)
            ->orderBy('id', 'DESC')
            ->get();
        $data['lang_id'] = $languageId;

        return response()->json($data);
    }
    public function deleteCharge(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $data = UserShippingCharge::where('id', $request->shipping_id)->where('user_id', $user->id)->first();
        $data->delete();
        return response()->json(['success' => 'Shipping charge delete successfully!'], 200);
    }
    public function addCharge(Request $request, $crypt)
    {
        $validator = Validator::make($request->all(), [
            'user_language_id' => 'required',
            'title' => 'required',
            'text' => 'required|max:255',
            'charge' => 'required',
        ], [
            'user_language_id.required' => 'Language is required!.',
        ]);

        $user = User::find(Crypt::decrypt($crypt));

        // If validation fails, return error response
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $input = $request->all();
        $input['language_id'] = $request->user_language_id;
        $input['user_id'] = $user->id;

        $data = new UserShippingCharge();
        $data->create($input);

        return response()->json(['success' => 'Shipping Charge added successfully!'], 200);
    }
    public function updateCharge(Request $request, $crypt)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'text' => 'required|max:255',
            'charge' => 'required',
        ]);

        $user = User::find(Crypt::decrypt($crypt));

        // If validation fails, return error response
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $data = UserShippingCharge::where('id', $request->shipping_id)->where('user_id', $user->id)->first();
        $data->update($request->all());

        return response()->json(['success' => 'Shipping Charge Update successfully!'], 200);
    }

    /*
    =================================
    coupon
    =================================
    */
    public function coupon($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data = UserCoupon::where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        return response()->json($data);
    }
    public function deleteCoupon(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data = UserCoupon::where('id', $request->coupon_id)->where('user_id', $user->id)->first();
        $data->delete();

        return response()->json(['success' => 'Coupon deleted successfully!'], 200);
    }
    public function addCoupon(Request $request, $crypt)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'code' => 'required',
            'value' => 'required',
            'minimum_spend' => 'nullable|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $user = User::find(Crypt::decrypt($crypt));

        // If validation fails, return error response
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        if(UserCoupon::where('user_id', $user->id)->where('code', $request->code)->exists()){
            return response()->json(['error' => 'You are already use this coupon!'], 422);
        }

        $input = $request->all();
        $input['user_id'] = $user->id;
        $data = new UserCoupon();
        $data->create($input);

        return response()->json(['success' => 'Coupon added successfully!'], 200);
    }
    public function updateCoupon(Request $request, $crypt)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'code' => 'required|unique:user_coupons,code,' . $request->coupon_id,
            'type' => 'required',
            'value' => 'required',
            'minimum_spend' => 'nullable|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $user = User::find(Crypt::decrypt($crypt));

        // If validation fails, return error response
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }
        $data = UserCoupon::where('user_id', $user->id)->where('id', $request->coupon_id)->first();

        if($data->code != $request->code && UserCoupon::where('user_id', $user->id)->where('code', $request->code)->exists()){
            return response()->json(['error' => 'You are already use this coupon!'], 422);
        }

        $data->update($request->all());

        return response()->json(['success' => 'Coupon updated successfully'], 200);
    }



    /*
    ===========================
    Manage Items
    ===========================
    */

    // itemCategory
    public function itemCategory(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data["langs"] = Language::where('user_id', $user->id)->orderBy('is_default', 'desc')->get();
        $data['itemcategories'] = UserItemCategory::where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        return response()->json($data);
    }
    public function itemCategoryAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $validator = Validator::make($request->all(), [
            'user_language_id' => 'required',
            'name' => 'required|max:255',
            'status' => 'required',
        ], [
            'user_language_id.required' => 'The language field is required'
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $data = new UserItemCategory;
        $input = $request->all();
        $input['slug'] =  make_slug($request->name);
        $input['user_id'] =  $user->id;
        $input['language_id'] =  $request->user_language_id;

        $data->create($input);

        return response()->json(['success' => 'Category successfully created!'], 200);
    }
    public function itemCategoryUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $validator = Validator::make($request->all(), [
            'user_language_id' => 'required',
            'name' => 'required|max:255',
            'status' => 'required',
        ], [
            'user_language_id.required' => 'The language field is required'
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $data = UserItemCategory::where('id', $request->category_id)->where('user_id', $user->id)->first();
        $input = $request->all();
        $input['slug'] =  make_slug($request->name);
        $input['user_id'] =  $user->id;
        $input['language_id'] =  $request->user_language_id;

        $data->update($input);
        return response()->json(['success' => 'Category successfully updated!'], 200);
    }
    public function itemCategoryDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $category = UserItemCategory::where('id', $request->category_id)->where('user_id', $user->id)->first();
        if ($category->items()->count() > 0) {
            return response()->json(['success' => 'First, delete all the item under the selected categories!'], 200);
        }

        if ($category->image != null) {
            // Storage::delete($category->image);
        }

        $category->delete();
        $category->subcategories()->delete();

        return response()->json(['success' => 'Category successfully deleted!'], 200);
    }
    public function itemCategoryFeature(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $category = UserItemCategory::where('id', $request->category_id)->where('user_id', $user->id)->first();
        $category->is_feature = $request->is_feature;
        $category->save();

        if ($request->is_feature == 1) {
            return response()->json(['success' => 'Category featured successfully!'], 200);
        } else {
            return response()->json(['success' => 'Category unfeatured successfully!'], 200);
        }
    }


    // itemSubcategory
    public function itemSubcategory(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $data['categories'] = UserItemCategory::where('user_id', $user->id)->orderBy('name', 'ASC')->get();
        $data['itemsubcategories'] = UserItemSubCategory::where('user_id', $user->id)
            ->with('category')
            ->orderBy('id', 'DESC')->get();
        $data["langs"] = Language::where('user_id', $user->id)->get();

        return response()->json($data);
    }
    public function itemSubcategoryAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $validator = Validator::make($request->all(), [
            'user_language_id' => 'required',
            'name' => 'required|max:255',
            'category_id' => 'required',
            'status' => 'required',
        ], [
            'user_language_id.required' => 'The language field is required',
            'category_id.required' => 'The category field is required'
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $data = new UserItemSubCategory;
        $input = $request->all();
        $input['slug'] =  make_slug($request->name);
        $input['user_id'] =  $user->id;
        $input['language_id'] =  $request->user_language_id;
        $data->create($input);

        return response()->json(['success' => 'Sub Category added successfully!'], 200);
    }
    public function itemSubcategoryUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required',
            'user_language_id' => 'required',
            'status' => 'required',
        ], [
            'category_id.required' => 'The category field is required',
            'user_language_id.required' => 'The language field is required'
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $data = UserItemSubCategory::where('id', $request->subcategory_id)->where('user_id', $user->id)->first();
        $input = $request->all();
        $input['slug'] =  make_slug($request->name);
        $data->update($input);

        return response()->json(['success' => 'Sub Category added successfully!'], 200);
    }
    public function itemSubcategoryDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $category = UserItemSubCategory::where('id', $request->subcategory_id)->where('user_id', $user->id)->first();
        if ($category->items()->count() > 0) {
            return response()->json(['error' => 'First, delete all the item under the selected categories!'], 200);
        }
        $category->delete();

        return response()->json(['success' => 'Sub Category deleted successfully!'], 200);
    }


    /*
    Add Item
    ==========================
    */
    public function itemShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data['digitalCount'] = UserItem::where('type', 'digital')->where('user_id', $user->id)->count();
        $data['physicalCount'] = UserItem::where('type', 'physical')->where('user_id', $user->id)->count();

        return $data;
    }
    public function itemProduct(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $languageId = Language::where('user_id', $user->id)->where('is_default', 1)->pluck('id')->first();
        $data['items'] = DB::table('user_items')->where('user_items.user_id', $user->id)
            ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_contents.category_id', '=', 'user_item_categories.id')
            ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.*', 'user_item_categories.name AS category')
            ->orderBy('user_items.id', 'DESC')
            ->where('user_item_contents.language_id', '=', $languageId)
            ->where('user_item_categories.language_id', '=', $languageId)
            ->get();
        $data['lang_id'] = $languageId;

        return response()->json($data);
    }


    public function itemProductAdd($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data["langs"] = Language::where('user_id', $user->id)->orderBy('is_default', 'desc')->get();

        $categories = [];
        foreach ($data["langs"] as $value) {
            $categories[$value->code] = UserItemCategory::where('language_id', $value->id)->where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        }
        $data['categories'] = $categories;

        return response()->json($data);
    }
    public function itemProductAddSubmit(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        // $messages = [];
        // $rules = [];
        // $rules['image'] = 'required';
        // $rules['status'] = 'required';
        // $rules['current_price'] = 'required|numeric';
        // $rules['previous_price'] = 'nullable|numeric';

        // // if product type is 'physical'
        // if ($request->type == 'physical') {
        //     $rules['sku'] = 'required';
        // }

        // $validator = Validator::make($request->all(), $rules, $messages);
        // if ($validator->fails()) {
        //     $errors = $validator->errors()->all();
        //     $errorMessage = implode(', ', $errors);
        //     return response()->json(['error' => $errorMessage], 422);
        // }

        // // if the type is digital && 'upload file' method is selected, then store the downloadable file
        // if ($request->type == 'digital' && $request->file_type == 'upload') {
        //     if ($request->hasFile('download_file')) {
        //         $filename = $request->download_file;
        //     }
        // }

        // $language = Language::find($request->user_language_id);

        // $item = new UserItem();
        // $thumbnailImgName = $request->thumbnail;
        // $item->user_id = $user->id;
        // $item->stock = $request->stock;
        // $item->sku = $request->sku;
        // $item->thumbnail = $thumbnailImgName;
        // $item->status = $request->status;
        // $item->current_price = $request->current_price;
        // $item->previous_price = $request->previous_price ?? 0.00;
        // $item->type = $request->type;
        // $item->download_file = $filename ?? null;
        // $item->download_link = $request->download_link;
        // $item->save();
        // foreach ($request->image as $value) {
        //     UserItemImage::create([
        //         'item_id' => $item->id,
        //         'image' => $value,
        //     ]);
        // }

        // $adContent = new UserItemContent();
        // $adContent->item_id = $item->id;
        // $adContent->language_id = $language->id;
        // $adContent->category_id = $request->category;
        // $adContent->subcategory_id = $request->subcategory;
        // $adContent->title = $request->title;
        // $adContent->slug = make_slug($request->sku);
        // $adContent->summary = $request->summary;
        // $adContent->tags = $request->tags;
        // $adContent->description = Purifier::clean($request->description);
        // $adContent->meta_keywords = $request->keyword;
        // $adContent->meta_description = $request->keyword;
        // $adContent->save();

        // return response()->json(['success' => 'Item added successfully!'], 200);


        $languages = Language::where('user_id', $user->id)->get();
        $messages = [];
        $rules = [];
        $rules['status'] = 'required';
        $rules['current_price'] = 'required|numeric';
        $rules['previous_price'] = 'nullable|numeric';

        foreach ($languages as $language) {
            $rules[$language->code . '_title'] = 'required';
            $rules[$language->code . '_category'] = 'required';
            $rules[$language->code . '_subcategory'] = 'required';
            $messages[$language->code . '_category.required'] = 'The Category field is required for ' . $language->name . ' language.';
            $messages[$language->code . '_subcategory.required'] = 'The Subcategory field is required for ' . $language->name . ' language.';
            $messages[$language->code . '_title.required'] = 'The Title field is required for ' . $language->name . ' language.';
        }


        // if product type is 'physical'
        if ($request->type == 'physical') {
            $rules['sku'] = 'required';
        }

        // if product type is 'digital'
        if ($request->type == 'digital') {
            $rules['file_type'] = 'required';
            // if 'file upload' is chosen
            if ($request->has('file_type') && $request->file_type == 'upload') {
                $rules['download_file'] = 'required';
            }
            // if 'file donwload link' is chosen
            elseif ($request->has('file_type') && $request->file_type == 'link') {
                $rules['download_link'] = 'required';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $item = new UserItem();
        // set a name for the thumbnail image and store it to local storage
        $item->user_id = $user->id;
        $item->stock = $request->stock;
        $item->sku = $request->sku;
        $item->thumbnail = $request->thumbnail;
        $item->status = $request->status;
        $item->current_price = $request->current_price;
        $item->previous_price = $request->previous_price ?? 0.00;
        $item->type = $request->type;
        $item->download_file = $request->download_file ?? null;
        $item->download_link = $request->download_link;
        $item->save();
        foreach ($request->galleries as $value) {
            UserItemImage::create([
                'item_id' => $item->id,
                'image' => $value,
            ]);
        }
        // store varations as json
        foreach ($languages as $language) {
            $adContent = new UserItemContent();
            $adContent->item_id = $item->id;
            $adContent->language_id = $language->id;
            $adContent->category_id = $request[$language->code . '_category'];
            $adContent->subcategory_id = $request[$language->code . '_subcategory'];
            $adContent->title = $request[$language->code . '_title'];
            $adContent->slug = make_slug($request[$language->code . '_title']);
            $adContent->summary = $request[$language->code . '_summary'];
            $adContent->tags = $request[$language->code . '_tags'];
            $adContent->description = Purifier::clean($request[$language->code . '_description']);
            $adContent->meta_keywords = $request[$language->code . '_keyword'];
            $adContent->meta_description = $request[$language->code . '_meta_keyword'];
            $adContent->save();
        }

        return response()->json(['success' => 'Item added successfully!'], 200);
    }
    public function itemProductEdit(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data["langs"] = Language::where('user_id', $user->id)->orderBy('is_default', 'desc')->get();
        $data['items'] = UserItem::find($request->products_id);

        $content = [];
        $categories = [];
        foreach ($data["langs"] as $value) {
            $language = UserItemContent::where('item_id', $request->products_id)->where('language_id', $value->id)->first();
            $subcategory = UserItemSubCategory::where('category_id', $language->category_id)->first();

            $content[$value->code] = [
                $language,
                $subcategory
            ];

            $categories[$value->code] = UserItemCategory::where('language_id', $value->id)->where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        }
        $data['categories'] = $categories;
        $data['content'] = $content;

        $data['galleries'] = UserItemImage::where('item_id', $request->products_id)->orderBy('id', 'DESC')->get();

        return response()->json($data);
    }
    public function itemProductUpdateSubmit(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $languages = Language::where('user_id', $user->id)->get();
        $messages = [];
        $rules = [];
        $rules['status'] = 'required';
        $rules['current_price'] = 'required|numeric';
        $rules['previous_price'] = 'nullable|numeric';

        foreach ($languages as $language) {
            $rules[$language->code . '_title'] = 'required';
            $rules[$language->code . '_category'] = 'required';
            $rules[$language->code . '_subcategory'] = 'required';
            $messages[$language->code . '_category.required'] = 'The Category field is required for ' . $language->name . ' language.';
            $messages[$language->code . '_subcategory.required'] = 'The Subcategory field is required for ' . $language->name . ' language.';
            $messages[$language->code . '_title.required'] = 'The Title field is required for ' . $language->name . ' language.';
        }


        // if product type is 'physical'
        if ($request->type == 'physical') {
            $rules['sku'] = 'required';
        }

        // if product type is 'digital'
        if ($request->type == 'digital') {
            $rules['file_type'] = 'required';
            // if 'file upload' is chosen
            if ($request->has('file_type') && $request->file_type == 'upload') {
                $rules['download_file'] = 'required';
            }
            // if 'file donwload link' is chosen
            elseif ($request->has('file_type') && $request->file_type == 'link') {
                $rules['download_link'] = 'required';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $item = UserItem::where('id', $request->item_id)->where('user_id', $user->id)->first();
        $item->stock = $request->stock;
        $item->sku = $request->sku;
        if($request->thumbnail != null){
            $item->thumbnail = $request->thumbnail;
        }
        $item->status = $request->status;
        $item->current_price = $request->current_price;
        $item->previous_price = $request->previous_price ?? 0.00;
        $item->type = $request->type;
        $item->download_file = $request->download_file ?? null;
        $item->download_link = $request->download_link;
        $item->save();

        if($request->galleries != null){
            $allGalleryImg = UserItemImage::where('item_id', $item->id)->get();
            foreach ($allGalleryImg as $value) {
                Storage::url($value);
                $value->delete();
            }
            foreach ($request->galleries as $value) {
                UserItemImage::create([
                    'item_id' => $item->id,
                    'image' => $value,
                ]);
            }
        }


        // store varations as json
        foreach ($languages as $language) {
            $adContent = UserItemContent::where('item_id', $request->item_id)->where('language_id', $language->id)->first();
            $adContent->category_id = $request[$language->code . '_category'];
            $adContent->subcategory_id = $request[$language->code . '_subcategory'];
            $adContent->title = $request[$language->code . '_title'];
            $adContent->slug = make_slug($request[$language->code . '_title']);
            $adContent->summary = $request[$language->code . '_summary'];
            $adContent->tags = $request[$language->code . '_tags'];
            $adContent->description = Purifier::clean($request[$language->code . '_description']);
            $adContent->meta_keywords = $request[$language->code . '_keyword'];
            $adContent->meta_description = $request[$language->code . '_meta_keyword'];
            $adContent->save();
        }

        return response()->json(['success' => 'Item updated successfully!'], 200);
    }
    public function itemProductSubCategory(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        return UserItemSubCategory::where('user_id', $user->id)->where('category_id', $request->category_id)->get();
    }
    public function itemDigitalProductsubCategroy($id)
    {
        return UserItemSubCategory::where('category_id', $id)->orderBy('name', 'ASC')->get();
    }

    public function itemDigitalProductSingleShow(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $data['items'] = DB::table('user_items')
            ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_contents.category_id', '=', 'user_item_categories.id')
            ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.*', 'user_item_categories.name AS category')
            ->where('user_items.id', $request->products_id)
            ->orderBy('user_items.id', 'DESC')
            ->first();
        $data['itemcategories'] = UserItemCategory::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $data['galleries'] = UserItemImage::where('item_id', $data['items']->item_id)->orderBy('id', 'DESC')->get();

        return response()->json($data);
    }
    public function itemDigitalProductUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $messages = [];
        $rules = [];
        $rules['status'] = 'required';
        $rules['current_price'] = 'required|numeric';
        $rules['previous_price'] = 'nullable|numeric';

        // if product type is 'physical'
        if ($request->type == 'physical') {
            $rules['sku'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        // if the type is digital && 'upload file' method is selected, then store the downloadable file
        if ($request->type == 'digital' && $request->file_type == 'upload') {
            $filename = $request->download_file;
        }

        $item = UserItem::where('id', $request->item_id)->where('user_id', $user->id)->first();
        $item->user_id = $user->id;
        $item->stock = $request->stock;
        $item->sku = $request->sku;

        if($request->thumbnail != null){
            Storage::delete($item->thumbnail);
            $item->thumbnail = $request->thumbnail;
        }

        $item->status = $request->status;
        $item->current_price = $request->current_price;
        $item->previous_price = $request->previous_price ?? 0.00;
        $item->type = $request->type;
        $item->download_file = $filename ?? null;
        $item->download_link = $request->download_link;
        $item->save();

        if($request->image != null){
            $allGalleryImg = UserItemImage::where('item_id', $item->id)->get();
            foreach ($allGalleryImg as $value) {
                Storage::url($value);
                $value->delete();
            }
            foreach ($request->image as $value) {
                UserItemImage::create([
                    'item_id' => $item->id,
                    'image' => $value,
                ]);
            }
        }

        // store varations as json
        $adContent = UserItemContent::where('item_id', $request->item_id)->first();
        $adContent->item_id = $item->id;
        $adContent->category_id = $request->category;
        $adContent->subcategory_id = $request->subcategory;
        $adContent->title = $request->title;
        $adContent->slug = make_slug($request->sku);
        $adContent->summary = $request->summary;
        $adContent->tags = $request->tags;
        $adContent->description = Purifier::clean($request->description);
        $adContent->meta_keywords = $request->keyword;
        $adContent->meta_description = $request->keyword;
        $adContent->save();

        return response()->json(['success' => 'Item update successfully!'], 200);
    }
    public function itemDigitalProductDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $item = UserItem::where('id', $request->item_id)->where('user_id', $user->id)->first();

        Storage::url($item->thumbnail);

        foreach ($item->sliders as $key => $image) {
            Storage::url($image->image);
            $image->delete();
        }

        // remove this item's order // if exist
        if ($item->orderItems->count()) {
            foreach ($item->orderItems as  $value) {
                $order = UserOrder::findOrFail($value->user_order_id);
                if ($order->orderitems->count() > 1) {
                } else {
                    $order->delete();
                }
            }
            $item->orderItems()->delete();
        }

        // remove this item from wishlist // if exist
        $item->wishlist()->delete();
        $item->itemContents()->delete();
        $item->delete();
        return response()->json(['success' => 'Item deleted successfully!'], 200);
    }


    /*
    Item - Manage Orders
    ==========================
    */
    public function itemOrderDetails($id)
    {
        $order = UserOrder::findOrFail($id);
        return response()->json([$order], 200);
    }
    public function itemOrderStatus(Request $request, $crypt)
    {
        // $user = User::find(Crypt::decrypt($crypt));

        $po = UserOrder::find($request->order_id);

        $po->order_status = $request->order_status;
        $po->save();

        $user = Customer::findOrFail($po->customer_id);

        $be = BasicExtended::first();

        $sub = 'Order Status Update';

        $to = $user->email;
        // Send Mail to Buyer
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($user->email, $user->fname);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $user->fname . '</strong>,<br/>Your order status is ' . $request->order_status . '.<br/>Thank you.';
                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        } else {
            try {

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($user->email, $user->fname);


                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $user->fname . '</strong>,<br/>Your order status is ' . $request->order_status . '.<br/>Thank you.';

                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        }


        return response()->json(['success' => 'Order status changed successfully!'], 200);
    }
    public function itemOrderPaymentStatus(Request $request, User $user)
    {
        $po = UserOrder::find($request->order_id);
        $po->payment_status = $request->payment_status;

        $user = Customer::findOrFail($po->customer_id);
        $be = BasicExtended::first();
        $sub = 'Payment Status Updated';
        $po->save();

        // Send Mail to Buyer
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($user->email, $user->fname);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $user->username . '</strong>,<br/>
                 Your Payment status is ' . $request->payment_status . '.<br/>
                 Your Order number is ' . $po->order_number . '.<br/>
                 See Orders: <a href="' . route('customer.orders-details', ['id' => $po->id, $user->username]) . '">' . route('customer.orders-details', ['id' => $po->id, $user->username]) . '"</a>" <br/>
                 Thank you.';
                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        } else {
            try {
                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($user->email, $user->fname);
                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $user->username . '</strong>,<br/>
                 Your Payment status is ' . $request->payment_status . '.<br/>
                 Your Order number is ' . $po->order_number . '.<br/>
                 See Orders: <a href="' . route('customer.orders-details', ['id' => $po->id, $user->username]) . '">' . route('customer.orders-details', ['id' => $po->id, $user->username]) . '"</a>" <br/>
                 Thank you.';
                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        }

        return response()->json(['success', 'Payment status changed successfully!'], 200);
    }
    public function itemOrderDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $order = UserOrder::where('id', $request->order_id)->where('user_id', $user->id)->first();

        // @unlink(public_path('assets/front/invoices/' . $order->invoice_number));
        // @unlink(public_path('assets/front/receipt/' . $order->receipt));

        if(count($order->orderitems) > 0){
            foreach ($order->orderitems as $item) {
                $item->delete();
            }
        }
        $order->delete();

        return response()->json(['success' => 'Item order deleted successfully!'], 200);
    }
    public function itemOrderAll(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $search = $request->search;
        $data['orders'] =
            UserOrder::when($search, function ($query, $search) {
                return $query->where('order_number', $search);
            })
            ->where('user_id', $user->id)
            ->orderBy('id', 'DESC')->get();

        return response()->json([$data], 200);
    }
    public function itemOrderPending(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $search = $request->search;
        $data['orders'] = UserOrder::when($search, function ($query, $search) {
            return $query->where('order_number', $search);
        })
        ->where('user_id', $user->id)
        ->where('order_status', 'pending')->orderBy('id', 'DESC')->get();

        return response()->json([$data], 200);
    }
    public function itemOrderProcessing(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $search = $request->search;

        $data['orders'] = UserOrder::when($search, function ($query, $search) {
            return $query->where('order_number', $search);
        })
        ->where('user_id', $user->id)
        ->where('order_status', 'processing')->orderBy('id', 'DESC')->get();

        return response()->json([$data], 200);
    }
    public function itemOrderCompleted(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $search = $request->search;
        $data['orders'] = UserOrder::when($search, function ($query, $search) {
            return $query->where('order_number', $search);
        })
        ->where('user_id', $user->id)
        ->where('order_status', 'completed')->orderBy('id', 'DESC')->get();

        return response()->json([$data], 200);
    }
    public function itemOrderRejected(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $search = $request->search;
        $data['orders'] = UserOrder::when($search, function ($query, $search) {
            return $query->where('order_number', $search);
        })
        ->where('user_id', $user->id)
        ->where('order_status', 'rejected')->orderBy('id', 'DESC')->get();

        return response()->json([$data], 200);
    }
    public function itemOrderReport(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $paymentStatus = $request->payment_status;
        $orderStatus = $request->order_status;
        $paymentMethod = $request->payment_method;

        if (!empty($fromDate) && !empty($toDate)) {
            $orders = UserOrder::when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
            })->when($toDate, function ($query, $toDate) {
                return $query->whereDate('created_at', '<=', Carbon::parse($toDate));
            })->when($paymentMethod, function ($query, $paymentMethod) {
                return $query->where('method', $paymentMethod);
            })->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })->when($orderStatus, function ($query, $orderStatus) {
                return $query->where('order_status', $orderStatus);
            })->select('order_number', 'billing_fname', 'billing_email', 'billing_number', 'billing_city', 'billing_country', 'shpping_fname', 'shpping_email', 'shpping_number', 'shpping_city', 'shpping_country', 'method', 'shipping_method', 'cart_total', 'discount', 'tax', 'shipping_charge', 'total', 'created_at', 'payment_status', 'order_status')
                ->orderBy('id', 'DESC');
            $data['orders'] = $orders->paginate(10);
        } else {
            $data['orders'] = [];
        }

        $data['onPms'] = UserPaymentGeteway::where('status', 1)->get();
        $data['offPms'] = UserOfflineGateway::where('item_checkout_status', 1)->get();


        return response()->json([$data], 200);
    }
}
