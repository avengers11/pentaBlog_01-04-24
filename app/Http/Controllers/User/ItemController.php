<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\UserItem;
use App\Http\Helpers\Uploader;
use App\Models\User\UserItemImage;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\Controller;
use App\Models\User\UserItemContent;
use Illuminate\Support\Facades\Auth;
use App\Models\User\UserItemCategory;
use Illuminate\Support\Facades\Session;
use App\Models\User\UserItemSubCategory;
use App\Models\User\UserItemVariation;
use App\Models\User\UserOrder;
use App\Models\User\UserShopSetting;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $lang_id = $lang->id;
        $data['items'] = DB::table('user_items')->where('user_items.user_id', Auth::guard('web')->user()->id)
            ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_contents.category_id', '=', 'user_item_categories.id')
            ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.*', 'user_item_categories.name AS category')
            ->orderBy('user_items.id', 'DESC')
            ->where('user_item_contents.language_id', '=', $lang_id)
            ->where('user_item_categories.language_id', '=', $lang_id)
            ->get();
        $data['lang_id'] = $lang_id;
        return view('user.item.index', $data);
    }


    public function type(Request $request)
    {
        $data['digitalCount'] = UserItem::where('type', 'digital')->where('user_id', Auth::guard('web')->user()->id)->count();
        $data['physicalCount'] = UserItem::where('type', 'physical')->where('user_id', Auth::guard('web')->user()->id)->count();
        return view('user.item.type', $data);
    }


    public function create(Request $request)
    {
        $data['lang'] = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $data['languages'] = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        return view('user.item.create', $data);
    }
    public function uploadUpdate(Request $request, $id)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'slider']);
        }
        $product = UserItem::findOrFail($id);
        if ($request->hasFile('file')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $request->file('file')->move(public_path('assets/front/img/product/featured/'), $filename);
            @unlink(public_path('assets/front/img/product/featured/' . $product->feature_image));
            $product->feature_image = $filename;
            $product->save();
        }
        return response()->json(['status' => "success", "image" => "Product image", 'product' => $product]);
    }
    public function getCategory($langid)
    {
        $category = UserItemCategory::where('language_id', $langid)->get();
        return $category;
    }

    public function store(Request $request)
    {

        $languages = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        $messages = [];
        $rules = [];
        $thumbnailImgURL = $request->thumbnail;
        $sliderImgURLs = $request->has('image') ? $request->image : [];
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
        $thumbnailImgExt = $thumbnailImgURL ? $thumbnailImgURL->extension() : null;
        $sliderImgExts = [];
        // pplimorp
        // $rules['thumbnail'] = 'required|mimes: jpeg,jpg,svg,png';

        $rules['image'] = [
            'required',
            function ($attribute, $value, $fail) use ($allowedExtensions, $sliderImgExts) {
                if (!empty($sliderImgExts)) {
                    foreach ($sliderImgExts as $sliderImgExt) {
                        if (!in_array($sliderImgExt, $allowedExtensions)) {
                            $fail('Only .jpg, .jpeg, .png and .svg file is allowed for slider image.');
                            break;
                        }
                    }
                }
            }
        ];
        $rules['thumbnail'] = [
            'required',
            function ($attribute, $value, $fail) use ($allowedExtensions, $thumbnailImgExt) {
                if (!in_array($thumbnailImgExt, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed for thumbnail image.');
                }
            }
        ];
        $messages['image.required'] = 'The slider Image is required.';
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
            $allowedExts = array('zip');
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
                $allowedExts = array('zip');
                $rules['download_file'] = [
                    'required',
                    function ($attribute, $value, $fail) use ($request, $allowedExts) {
                        $file = $request->file('download_file');
                        $ext = $file->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only zip file is allowed");
                        }
                    }
                ];
            }
            // if 'file donwload link' is chosen
            elseif ($request->has('file_type') && $request->file_type == 'link') {
                $rules['download_link'] = 'required';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        // if the type is digital && 'upload file' method is selected, then store the downloadable file
        if ($request->type == 'digital' && $request->file_type == 'upload') {
            if ($request->hasFile('download_file')) {
                $digitalFile = $request->file('download_file');
                $filename = time() . '-' . uniqid() . "." . $digitalFile->extension();
                $directory = public_path('core/storage/digital_products/');
                @mkdir($directory, 0775, true);
                $digitalFile->move($directory, $filename);
            }
        }

        $item = new UserItem();
        // set a name for the thumbnail image and store it to local storage
        $thumbnailImgName = time() . '.' . $thumbnailImgExt;
        $thumbnailDir = public_path('./assets/front/img/user/items/thumbnail/');
        @mkdir($thumbnailDir, 0775, true);
        @copy($thumbnailImgURL, $thumbnailDir . $thumbnailImgName);
        $sliderDir = public_path('./assets/front/img/user/items/slider-images/');
        @mkdir($sliderDir, 0775, true);
        $item->user_id = Auth::guard('web')->user()->id;
        $item->stock = $request->stock;
        $item->sku = $request->sku;
        $item->thumbnail = $thumbnailImgName;
        $item->status = $request->status;
        $item->current_price = $request->current_price;
        $item->previous_price = $request->previous_price ?? 0.00;
        $item->type = $request->type;
        $item->download_file = $filename ?? null;
        $item->download_link = $request->download_link;
        $item->save();
        foreach ($request->image as $value) {
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
        Session::flash('success', 'Item added successfully!');
        return "success";
    }
    public function edit(Request $request, $id)
    {
        $lang = Language::where('code', $request->language)->first();
        $data['languages'] = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        $data['item'] = UserItem::findOrFail($id);
        return view('user.item.edit', $data);
    }

    public function update(Request $request)
    {
        $item = UserItem::findOrFail($request->item_id);
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
        if ($request->hasFile('thumbnail')) {
            $thumbnailImgURL = $request->thumbnail;
            $thumbnailImgExt = $thumbnailImgURL ? $thumbnailImgURL->extension() : null;
            $rules['thumbnail'] = function ($attribute, $value, $fail) use ($allowedExtensions, $thumbnailImgExt) {
                if (!in_array($thumbnailImgExt, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed for thumbnail image.');
                }
            };
        }
        $sliderImgURLs = array_key_exists("image", $request->all()) && count($request->image) > 0 ? $request->image : [];
        $sliderImgExts = [];
        // get all the slider images extension
        if (!empty($sliderImgURLs)) {
            foreach ($sliderImgURLs as $sliderImgURL) {
                $n = strrpos($sliderImgURL, ".");
                $extension = ($n === false) ? "" : substr($sliderImgURL, $n + 1);
                array_push($sliderImgExts, $extension);
            }
        }
        if (array_key_exists("image", $request->all()) && count($request->image) > 0) {
            $rules['image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $sliderImgExts) {
                foreach ($sliderImgExts as $sliderImgExt) {
                    if (!in_array($sliderImgExt, $allowedExtensions)) {
                        $fail('Only .jpg, .jpeg, .png and .svg file is allowed for slider image.');
                        break;
                    }
                }
            };
        }
        $languages = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        $messages = [];
        foreach ($languages as $language) {
            $rules[$language->code . '_title'] = 'required';
            $rules[$language->code . '_category'] = 'required';
            $messages[$language->code . '_category.required'] = 'The category field is required for ' . $language->name . ' language.';
            $messages[$language->code . '_title.required'] = 'The Title field is required for ' . $language->name . ' language.';
            $allowedExts = array('zip');
        }
        // if product type is 'physical'
        if ($item->type == 'physical') {
            $rules['stock'] = 'required';
            $rules['sku'] = 'required';
        }
        // if product type is 'digital'
        if ($item->type == 'digital') {
            // if 'file upload' is chosen
            if ($request->has('file_type') && $request->file_type == 'upload') {
                if (empty($item->download_file)) {
                    $rules['download_file'][] = 'required';
                }
                $rules['download_file'][] = function ($attribute, $value, $fail) use ($item, $request) {
                    $allowedExts = array('zip');
                    if ($request->hasFile('download_file')) {
                        $file = $request->file('download_file');
                        $ext = $file->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only zip file is allowed");
                        }
                    }
                };
            }
            // if 'file donwload link' is chosen
            elseif ($request->has('file_type') && $request->file_type == 'link') {
                $rules['download_link'] = 'required';
            }
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if (!empty($sliderImgURLs)) {
            foreach ($sliderImgURLs as $sliderImgURL) {
                $n = strrpos($sliderImgURL, ".");
                $extension = ($n === false) ? "" : substr($sliderImgURL, $n + 1);
                array_push($sliderImgExts, $extension);
            }
        }
        // if the type is digital && 'upload file' method is selected, then store the downloadable file
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        if ($request->hasFile('thumbnail')) {
            $thumbnailImgURL = $request->thumbnail;
            // first, delete the previous image from local storage
            @unlink(public_path('assets/front/img/user/items/thumbnail/' . $item->thumbnail));

            // second, set a name for the image and store it to local storage
            $thumbnailImgName = time() . '.' . $thumbnailImgExt;
            $thumbnailDir = public_path('./assets/front/img/user/items/thumbnail/');

            @copy($thumbnailImgURL, $thumbnailDir . $thumbnailImgName);
        }
        //file upload
        if ($request->type == 'digital' && $request->file_type == 'upload') {
            if ($request->hasFile('download_file')) {
                //unlink files
                @unlink(public_path('core/storage/digital_products/' . $item->download_file));
                $digitalFile = $request->file('download_file');
                $filename = time() . '-' . uniqid() . "." . $digitalFile->extension();
                $directory = public_path('core/storage/digital_products/');
                @mkdir($directory, 0775, true);
                $digitalFile->move($directory, $filename);
            }
        }


        $item->stock = $request->stock;
        $item->sku = $request->sku;
        $item->status = $request->status;
        $item->thumbnail = $request->hasFile('thumbnail') ? $thumbnailImgName : $item->thumbnail;
        $item->current_price = $request->current_price;
        $item->previous_price = $request->previous_price ?? 0.00;
        $item->type = $request->type;
        $item->download_file = $filename ?? null;
        $item->download_link = $request->download_link ?? null;
        $item->save();
        if ($request->image) {
            foreach ($request->image as $value) {
                UserItemImage::create([
                    'item_id' => $item->id,
                    'image' => $value,
                ]);
            }
        }
        foreach ($languages as $language) {
            $adContent = UserItemContent::where('item_id', $request->item_id)
                ->where('language_id', $language->id)
                ->first();
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
        Session::flash('success', 'Item updated successfully!');
        return "success";
    }

    public function delete(Request $request)
    {
        $item = UserItem::findOrFail($request->item_id);
        @unlink(public_path('assets/front/img/user/items/thumbnail/' . $item->thumbnail));
        foreach ($item->sliders as $key => $image) {
            @unlink(public_path('assets/front/img/user/items/slider-images/' . $image->image));
            $image->delete();
        }
        // remove this item from session cart // if exist
        if(Session::has('cart')){

            $cart = Session::get('cart');
            foreach ($cart as $key => $value) {
                if ($value['id'] == $request->item_id) {
                    unset($cart[$key]);
                }
                Session::put('cart', $cart);
            }

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
        Session::flash('success', 'Item deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $item = UserItem::findOrFail($id);
            @unlink(public_path('assets/front/img/user/items/thumbnail/' . $item->thumbnail));
            foreach ($item->sliders as $key => $image) {
                @unlink(public_path('assets/front/img/user/items/slider-images/' . $image->image));
                $image->delete();
            }
            // remove this item from session cart // if exist
            $cart = Session::get('cart');
            foreach ($cart as $key => $value) {
                if ($value['id'] == $id) {
                    unset($cart[$key]);
                }
                Session::put('cart', $cart);
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
        }
        Session::flash('success', 'Product deleted successfully!');
        return "success";
    }
    public function variants($pid)
    {
        $variations = UserItemVariation::where('item_id', $pid)->get();
        $variants = [];
        $i = 0;
        foreach ($variations as $key => $value) {
            $variants[$i] = [
                'name' => str_replace("_", " ", $value->variant_name),
                'uniqid' => uniqid(),
            ];
            $option_names = json_decode($value->option_name);
            $option_prices = json_decode($value->option_price);
            $option_stocks = json_decode($value->option_stock);
            $j = 0;
            foreach ($option_names as $okey => $val) {
                $variants[$i]['options'][$j]['name'] = $val;
                $variants[$i]['options'][$j]['price'] = $option_prices[$okey];
                $variants[$i]['options'][$j]['stock'] = $option_stocks[$okey];
                $j++;
            }
            $i++;
        }
        return response()->json($variants);
    }
    public function variations($id)
    {
        $data['item_id'] = $id;
        $data['ins'] = UserItemVariation::where('item_id', $id)->groupBy('indx')->select('indx')->get();

        $variations = [];

        foreach ($data['ins'] as $key => $value) {
            $variations[] = UserItemVariation::where('item_id', $id)->where('indx', $value->indx)->get();
        }

        $data['variations'] = $variations;
        $data['languages'] = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        return view('user.item.variation', $data);
    }

    public function variationStore(Request $request)
    {

        $request->validate([
            'variation_helper' => 'required',
            'options1*' => 'required',
            'options2*' => 'required',
            'options3*' => 'required',
        ]);
        $languages = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        $check_var = UserItemVariation::where('item_id', $request->item_id)->delete();
        foreach ($request->variation_helper as $key => $v_helper) {
            foreach ($languages as $lkey => $value) {
                UserItemVariation::create([
                    'item_id' => $request->item_id,
                    'language_id' => $value->id,
                    'variant_name' => $request[$value->code . '_variation_' . $key],
                    'option_name' => json_encode($request[$value->code . '_options1' . '_' .  $key]),
                    'option_price' => json_encode($request['options2' . '_' .  $key]),
                    'option_stock' => json_encode($request['options3' . '_' .  $key]),
                    'indx' =>  $key

                ]);
            }
        }
        // deleting null data
        UserItemVariation::where('item_id', $request->item_id)->where('variant_name', null)->delete();
        Session::flash('success', 'Variations added successfully!');
        return "success";
    }

    public function settings()
    {
        $data['shopsettings'] = UserShopSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        return view('user.item.settings', $data);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'is_shop' => 'required',
            'tax' => 'required',
            'item_rating_system' => 'required',
        ]);

        $shopsettings = UserShopSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        if (!$shopsettings) {
            $shopsettings  = new UserShopSetting();
        }
        $shopsettings->user_id = Auth::guard('web')->user()->id;
        $shopsettings->is_shop = $request->is_shop;
        $shopsettings->item_rating_system = $request->item_rating_system;
        $shopsettings->tax = $request->tax ? $request->tax : 0.00;
        $shopsettings->save();

        $request->session()->flash('success', 'Shop setting updated successfully!');
        return "success";
    }

    public function slider(Request $request)
    {
        $filename = null;
        $request->validate([
            'file' => 'mimes:jpg,jpeg,png|required',
        ]);
        if ($request->hasFile('file')) {
            $filename = Uploader::upload_picture(public_path('assets/front/img/user/items/slider-images'), $request->file('file'));
        }
        return response()->json(['status' => 'success', 'file_id' => $filename]);
    }

    public function sliderRemove(Request $request)
    {
        if (file_exists(public_path('./assets/front/img/user/items/slider-images/' . $request->value))) {
            unlink(public_path('./assets/front/img/user/items/slider-images/' . $request->value));
            return response()->json(['status' => 200, 'message' => 'success']);
        } else {
            return response()->json(['status' => 404, 'message' => 'error']);
        }
    }

    public function dbSliderRemove(Request $request)
    {

        $img = UserItemImage::findOrFail($request->id);
        @unlink(public_path('./assets/front/img/user/items/slider-images/' . $img->image));
        $img->delete();
        return response()->json(['status' => 200, 'message' => 'success']);
    }

    public function subcatGetter(Request $request)
    {
        $data['subcategories'] = UserItemSubCategory::where('category_id', $request->category_id)->get();
        return $data;
    }
}
