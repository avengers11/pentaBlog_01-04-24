<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\User\UserItem;
use App\Models\User\ItemReview;
use App\Models\BasicSetting as BS;
use Illuminate\Support\Facades\DB;
use App\Models\BasicExtended as BE;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\User\UserItemContent;
use App\Models\User\UserShopSetting;
use Illuminate\Support\Facades\Auth;
use App\Models\User\CustomerWishList;
use App\Models\User\UserItemCategory;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Language as UserLanguage;

class ShopController extends Controller
{
    public function __construct()
    {
        $bs = BS::first();
        $be = BE::first();
    }


    public function shop(Request $request, $domain)
    {
        $user = getUser();
        $userShop = UserShopSetting::where('user_id', $user->id)->first();
        if (!empty($userShop) && ($userShop->is_shop == 0)) {
            return redirect()->route('front.user.detail.view', getParam());
        }
        $data['userShopSetting'] = $userShop;
        $data['bgImg'] = $this->getUserBreadcrumb($user->id);
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $search = $request->search;
        $minprice = $request->minprice;
        $maxprice = $request->maxprice;
        $category_id = $request->category_id;
        $subcategory_id = $request->subcategory_id;
        if ($request->type) {
            $type = $request->type;
        } else {
            $type = 'new';
        }
        $limit = 6;
        if (Auth::guard('customer')->check()) {
            $data['myWishlist'] = CustomerWishList::where('customer_id', Auth::guard('customer')->user()->id)->pluck('item_id')->toArray();
        } else {
            $data['myWishlist'] = [];
        }
        $data['categories'] = UserItemCategory::where('user_id', $user->id)->where('language_id', $userCurrentLang->id)->with('subcategories')->orderBy('name', 'asc')->get();
        $data['items'] = DB::table('user_items')->where('user_items.user_id', $user->id)
            ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_contents.category_id', '=', 'user_item_categories.id')
            ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.*',  'user_item_categories.name AS category')
            ->when($category_id, function ($query, $category_id) {
                return $query->where('user_item_contents.category_id', $category_id);
            })
            ->when($subcategory_id, function ($query, $subcategory_id) {
                return $query->where('user_item_contents.subcategory_id', $subcategory_id);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('user_item_contents.title', 'like', '%' . $search . '%')
                        ->orWhere('user_item_contents.tags', '=',  'like', '%' . $search . '%');
                });
            })
            ->when($minprice, function ($query, $minprice) {
                return $query->where('user_items.current_price', '>=', $minprice);
            })
            ->when($maxprice, function ($query, $maxprice) {
                return $query->where('user_items.current_price', '<=', $maxprice);
            })
            ->when($type, function ($query, $type) {
                if ($type == 'new') {
                    return $query->orderBy('user_items.id', 'DESC');
                } elseif ($type == 'old') {
                    return $query->orderBy('user_items.id', 'ASC');
                } elseif ($type == 'high-to-low') {
                    return $query->orderBy('user_items.current_price', 'DESC');
                } elseif ($type == 'low-to-high') {
                    return $query->orderBy('user_items.current_price', 'ASC');
                }
            })
            ->where('user_item_contents.language_id', '=', $userCurrentLang->id)
            ->where('user_item_categories.language_id', '=', $userCurrentLang->id)
            ->where('user_items.status', 1)
            ->paginate($limit);
        // dd($data['items']);
        $data['max_price'] = UserItem::where('user_id', $user->id)->max('current_price');
        $data['min_price'] = UserItem::where('user_id', $user->id)->min('current_price');
        $data['all_items'] = UserItem::where('user_id', $user->id)->where('status', 1)->count();
        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang, $user->id);
        return view('user-front.common.shop', $data);
    }

    public function adDetails(Request $request, $domain, $slug)
    {
        $user = getUser();
        $userShop = UserShopSetting::where('user_id', $user->id)->first();
        if (!empty($userShop) && ($userShop->is_shop == 0)) {
            return redirect()->route('front.user.detail.view', getParam());
        }

        $data['userShopSetting'] = $userShop;

        $data['bgImg'] = $this->getUserBreadcrumb($user->id);
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        $data['ad_details'] = UserItemContent::where('language_id', $userCurrentLang->id)->with('item.sliders', 'variations')->where('slug', $slug)->firstOrFail();
        // dd($data['ad_details']);
        $data['relateditems'] =  $data['user_items'] = DB::table('user_items')->where('user_items.user_id', '=', $user->id)
            ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_contents.category_id', '=', 'user_item_categories.id')
            ->select('user_items.*', 'user_item_contents.*', 'user_item_categories.name AS category')
            ->where('user_items.id', '!=', $data['ad_details']->item->id)
            ->where('user_item_categories.language_id', '=', $userCurrentLang->id)
            ->where('user_item_contents.language_id', '=', $userCurrentLang->id)
            ->limit(6)
            ->get();
        $data['user'] = $user;
        $data['reviews'] = ItemReview::where('item_id', $data['ad_details']->item_id)->get();
        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang, $user->id);


        return view('user-front.common.item-details', $data);
    }
}
