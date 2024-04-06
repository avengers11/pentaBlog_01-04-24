<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\UserCoupon;
use App\Http\Controllers\Controller;
use App\Models\User\UserShopSetting;
use App\Models\User\UserItemCategory;
use App\Models\User\UserShippingCharge;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User\UserItemSubCategory;

class ShopApiController extends Controller
{
    //settings
    public function settings(User $user)
    {
        $data = UserShopSetting::where('user_id', $user->id)->first();
        return response()->json($data);
    }

    //updateSettings
    public function updateSettings(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'is_shop' => 'required',
            'tax' => 'required',
            'item_rating_system' => 'required',
        ]);

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
    public function charge(Request $request, User $user)
    {
        $lang = Language::where('code', $request->language)->first();
        $lang_id = $lang->id;
        $data['shippings'] = UserShippingCharge::where('user_id', $user->id)
            // ->where('language_id', $lang_id)
            ->orderBy('id', 'DESC')
            ->get();
        $data['lang_id'] = $lang_id;

        return response()->json($data);
    }
    public function deleteCharge(Request $request, User $user)
    {
        $data = UserShippingCharge::where('id', $request->shipping_id)->where('user_id', $user->id)->first();
        $data->delete();
        return response()->json(['success' => 'Shipping charge delete successfully!'], 200);
    }
    public function addCharge(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'user_language_id' => 'required',
            'title' => 'required',
            'text' => 'required|max:255',
            'charge' => 'required',
        ], [
            'user_language_id.required' => 'Language is required!.',
        ]);

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
    public function updateCharge(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'text' => 'required|max:255',
            'charge' => 'required',
        ]);

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
    public function coupon(User $user)
    {
        $data = UserCoupon::where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        return response()->json($data);
    }
    public function deleteCoupon(Request $request, User $user)
    {
        $data = UserCoupon::where('id', $request->coupon_id)->where('user_id', $user->id)->first();
        $data->delete();

        return response()->json(['success' => 'Coupon deleted successfully!'], 200);
    }
    public function addCoupon(Request $request, User $user)
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
    public function updateCoupon(Request $request, User $user)
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
    public function itemCategory(Request $request, User $user)
    {
        // {
        //     "language" : "en"
        // }
        $lang = Language::where('code', $request->language)->where('user_id', $user->id)->first();
        $lang_id = $lang->id;
        $data['itemcategories'] = UserItemCategory::where('language_id', $lang_id)->where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $data['lang_id'] = $lang_id;

        return response()->json($data);
    }
    public function itemCategoryAdd(Request $request, User $user)
    {
        // {
        //     "user_language_id" : 162,
        //     "name" : "Name",
        //     "status" : 1,
        //     "image" : "img-txt"
        // }
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
    public function itemCategoryUpdate(Request $request, User $user)
    {
        // {
        //     "user_language_id" : 162,
        //     "name" : "Name",
        //     "status" : 1,
        //     "image" : "img-txt",
        //     "category_id" : 81,
        // }
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
    public function itemCategoryDelete(Request $request, User $user)
    {
        // {
        //     "category_id" : 80
        // }
        $category = UserItemCategory::where('id', $request->category_id)->where('user_id', $user->id)->first();
        if ($category->items()->count() > 0) {
            return response()->json(['success' => 'First, delete all the item under the selected categories!'], 200);
        }

        if ($category->image != null) {
            Storage::delete($category->image);
        }

        $category->delete();
        $category->subcategories()->delete();

        return response()->json(['success' => 'Category successfully deleted!'], 200);
    }
    public function itemCategoryFeature(Request $request, User $user)
    {
        // {
        //     "category_id" : 79,
        //     "is_feature" : 0
        // }
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
    public function itemSubcategory(Request $request, User $user)
    {
        // {
        //     "language" : "en"
        // }
        $lang = Language::where('code', $request->language)->where('user_id', $user->id)->first();
        $lang_id = $lang->id;
        $data['categories'] = UserItemCategory::where('language_id', $lang_id)->where('user_id', $user->id)->orderBy('name', 'ASC')->get();
        $data['itemsubcategories'] = UserItemSubCategory::where('language_id', $lang_id)->where('user_id', $user->id)
            ->with('category')
            ->orderBy('id', 'DESC')->get();
        $data['lang_id'] = $lang_id;

        return response()->json($data);
    }
    public function itemSubcategoryAdd(Request $request, User $user)
    {
        // {
        //     "user_language_id" : 162,
        //     "name" : "Sub-cat",
        //     "category_id" : 79,
        //     "status": 1
        // }
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
    public function itemSubcategoryUpdate(Request $request, User $user)
    {
        // {
        //     "name" : "Sub-cat2333",
        //     "category_id" : 79,
        //     "status": 1,
        //     "subcategory_id" : 129
        // }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required',
            'status' => 'required',
        ], [
            'category_id.required' => 'The category field is required'
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
    public function itemSubcategoryDelete(Request $request, User $user)
    {
        // {
        //     "subcategory_id" : 129
        // }
        $category = UserItemSubCategory::where('id', $request->subcategory_id)->where('user_id', $user->id)->first();
        if ($category->items()->count() > 0) {
            return response()->json(['error', 'First, delete all the item under the selected categories!'], 200);
        }
        $category->delete();

        return response()->json(['success', 'Sub Category deleted successfully!'], 200);
    }


    // Add Item
}
