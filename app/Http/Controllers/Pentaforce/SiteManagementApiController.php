<?php

namespace App\Http\Controllers\Pentaforce;

use App\Models\User;
use App\Models\User\FAQ;
use App\Models\User\Menu;
use App\Models\User\Page;
use App\Models\User\Post;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\PostView;
use App\Models\User\UserVcard;
use App\Models\User\GalleryItem;
use App\Models\User\PageContent;
use App\Models\User\PostContent;
use App\Models\User\Testimonial;
use App\Models\User\BasicSetting;
use App\Models\User\BookmarkPost;
use App\Models\User\Advertisement;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\Controller;
use App\Models\User\GalleryCategory;
use Illuminate\Support\Facades\Crypt;
use App\Models\User\TestimonialContent;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\LimitCheckerHelper;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\BasicExtended;
use App\Models\User\UserCustomDomain;

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
        $languageId = Language::where('user_id', $user->id)->where('is_default', 1)->pluck('id')->first();
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

        $languageId =   Language::where('user_id', $user->id)->where('is_default', 1)->pluck('id')->first();
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



    /*
    ==============================
    faq
    ==============================
    */
    public function faq(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $languageId =   Language::where('user_id', $user->id)->where('is_default', 1)->pluck('id')->first();

        // then, get the faqs of that language from db
        $information['faqs'] = FAQ::where('language_id', $languageId)
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        // also, get all the languages from db
        $information['langs'] = Language::where('user_id', $user->id)->get();

        return response()->json($information);
    }
    public function faqAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'question' => 'required',
            'user_language_id' => 'required',
            'answer' => 'required',
            'serial_number' => 'required'
        ];

        $messages = [
            'user_language_id.required' => 'The language field is required.',
            'question.required' => 'The question field is required',
            'answer.required' => 'The answer field is required',
            'serial_number.required' => 'The serial number field is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        FAQ::create([
            'language_id' => $request->user_language_id,
            'user_id' => $user->id,
            'question' => $request->question,
            'answer' => $request->answer,
            'serial_number' => $request->serial_number
        ]);

        return response()->json(['success' => 'New FAQ added successfully!'], 200);
    }
    public function faqUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'question' => 'required',
            'answer' => 'required',
            'serial_number' => 'required'
        ];

        $messages = [
            'question.required' => 'The question field is required',
            'answer.required' => 'The answer field is required',
            'serial_number.required' => 'The serial number field is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $faq = FAQ::where('user_id', $user->id)->where('id', $request->id)->first();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->serial_number = $request->serial_number;
        $faq->save();

        return response()->json(['success' => 'FAQ updated successfully!'], 200);
    }
    public function faqDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        FAQ::where('user_id', $user->id)->where('id', $request->id)->delete();

        return response()->json(['success' => 'FAQ deleted successfully!'], 200);
    }


    /*
    ==============================
    advertisement
    ==============================
    */
    public function advertisement(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data =BasicSetting::where('user_id', $user->id)
                ->select('adsense_publisher_id')
                ->first();
        $ads = Advertisement::where('user_id', $user->id)->orderBy('id', 'desc')->get();

        return response()->json(["publisher_id" => $data, "ads" => $ads]);
    }
    public function advertisementSettingUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $validator = Validator::make($request->all(), [
            'adsense_publisher_id' => 'required'
        ],[
            'adsense_publisher_id.required' => 'The publisher field is required'
         ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        BasicSetting::where('user_id', $user->id)->update(['adsense_publisher_id' => $request->adsense_publisher_id]);
        return response()->json(['success' => 'Settings updated successfully!'], 200);
    }
    public function advertisementAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $validator = Validator::make($request->all(), [
            'url' => 'required_if:ad_type,==,banner',
            'ad_slot' => 'required_if:ad_type,==,script',
            'image' => 'required_if:ad_type,==,banner'
        ], [
            'url.required_if' => 'The URL field is required',
            'ad_slot.required_if' => 'The ad slot field is required',
            'image.required_if' => 'The image field is required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        Advertisement::create([
            'user_id' => $user->id,
            'image' => $request->image,
            'ad_type' => $request->ad_type,
            'resolution_type' => $request->resolution_type,
            'url' => $request->url,
            'ad_slot' => $request->ad_slot,
        ]);

        return response()->json(['success' => 'New advertisement added successfully!'], 200);
    }
    public function advertisementUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $validator = Validator::make($request->all(), [
            'url' => 'required_if:ad_type,==,banner',
            'ad_slot' => 'required_if:ad_type,==,script',
        ], [
            'url.required_if' => 'The URL field is required',
            'ad_slot.required_if' => 'The ad slot field is required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $adv = Advertisement::where('user_id', $user->id)->where('id', $request->id)->first();
        if($request->image != null){
            $img = $request->image;
            if ($adv->image != null) {
                Storage::delete($adv->image);
            }
        }else{
            $img = $adv->image;
        }

        $adv->image = $img;
        $adv->ad_type = $request->ad_type;
        $adv->resolution_type = $request->resolution_type;
        $adv->url = $request->url;
        $adv->ad_slot = $request->ad_slot;
        $adv->save();

        return response()->json(['success' => 'Advertisement update successfully!'], 200);
    }    public function advertisementDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $item = Advertisement::where('id', $request->id)->where('user_id', $user->id)->first();
        if ($item->image != null) {
            Storage::delete($item->image);
        }
        $item->delete();

        return response()->json(['success' => 'Gallery items deleted successfully!'], 200);
    }


    /*
    ==============================
    advertisement
    ==============================
    */
    public function language(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data['languages'] = Language::where('user_id', $user->id)->get();

        $data['langCount'] = Language::where('user_id', $user->id)->count();
        $data['langLimit'] = UserPermissionHelper::currentPackagePermission($user->id)->language_limit;

        return response()->json($data);
    }
    public function languageAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $count = LimitCheckerHelper::currentLanguageCount($user->id); //category added count of current package
        $category_limit = LimitCheckerHelper::languagesLimit($user->id); //category limit count of current package
        if ($count >= $category_limit) {
            return response()->json(['error' => 'Language Limit Exceeded'], 200);
        }
        $rules = [
            'name' => 'required|max:255',
            'code' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    $language = Language::where([
                        ['code', $value],
                        ['user_id', $user->id]
                    ])->get();
                    if ($language->count() > 0) {
                        $fail(':attribute already taken');
                    }
                },
            ],
            'direction' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $deLang = Language::first();
        $in['name'] = $request->name;
        $in['code'] = $request->code;
        $in['rtl'] = $request->direction;
        $in['keywords'] = $deLang->keywords;
        $in['user_id'] = $user->id;
        if (Language::where([
            ['is_default', 1],
            ['user_id', $user->id]
        ])->count() > 0) {
            $in['is_default'] = 0;
        } else {
            $in['is_default'] = 1;
        }
      $language = Language::create($in);
        //language create to default manu create
        $menu = new Menu();
        $menu->user_id = $user->id;
        $menu->language_id = $language->id;
        $menu->menus = '[
            {"text":"Home","href":"","icon":"empty","target":"_self","title":"","type":"home"},
            {"text":"About","href":"","icon":"empty","target":"_self","title":"","type":"about"},
            {"text":"Posts","href":"","icon":"empty","target":"_self","title":"","type":"posts"},
            {"text":"Gallery","href":"","icon":"empty","target":"_self","title":"","type":"gallery"},
            {"text":"FAQs","href":"","icon":"empty","target":"_self","title":"","type":"faq"},
            {"text":"Contact","href":"","icon":"empty","target":"_self","title":"","type":"contact"}
            ]';
        $menu->save();

        return response()->json(['success' => 'LLanguage added successfully!'], 200);
    }
    public function languageUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $langCount = Language::where('user_id', $user->id)->count();
        $langLimit = UserPermissionHelper::currentPackagePermission($user->id)->language_limit;
        if ($langCount > $langLimit) {
            return response()->json(['error' => "You have to delete " . ($langCount - $langLimit) . " languages to enable Editing Feature of Languages."], 200);
        }
        $language = Language::findOrFail($request->language_id);
        // dd($language->id);

        $rules = [
            'name' => 'required|max:255',
            'code' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $user) {
                    $language = Language::where([
                        ['code', $value],
                        ['user_id', $user->id],
                        ['id', '<>', $request->language_id]
                    ])->get();
                    if ($language->count() > 0) {
                        $fail(':attribute already taken');
                    }
                }
            ],
            'direction' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return
            response()->json(['error' => $errorMessage], 422);
        }

        $language->name = $request->name;
        $language->code = $request->code;
        $language->rtl = $request->direction;
        $language->user_id = $user->id;
        $language->save();
        return response()->json(['success' => "Language updated successfully!"], 200);
    }
    public function languageDefault(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        Language::where('is_default', 1)->where('user_id', $user->id)->update(['is_default' => 0]);
        $lang = Language::find($request->language_id);
        $lang->is_default = 1;
        $lang->save();
        return response()->json(['success' => $lang->name . ' language is set as default.'], 200);
    }
    public function languageDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $id = $request->id;

        $la = Language::findOrFail($id);

        if ($la->is_default == 1) {
            return response()->json(['error' => "Default language cannot be deleted!"], 200);
        }
        if (session()->get('user_lang') == $la->code) {
            session()->forget('user_lang');
        }
        // variation delete
        if ($la->variations()->count() > 0) {
            $la->variations()->delete();
        }
        // user_item_contacts delete
        if ($la->user_item_contacts()->count() > 0) {
            $la->user_item_contacts()->delete();
        }
        //deleting page heading for corresponding language
        if ($la->pageHeading()->count() > 0) {
            $la->pageHeading()->delete();
        }
        // deleting seos for corresponding language
        if ($la->seos()->count() > 0) {
            $la->seos()->delete();
        }
        // deleting cookie alert for corresponding language
        if ($la->cookieAlert()->count() > 0) {
            $la->cookieAlert()->delete();
        }
        if ($la->menus()->count() > 0) {
            $la->menus()->delete();
        }
        // deleting post and post contents
        // $postContents = PostContent::where('language_id', $la->id);
        // if ($postContents->count() > 0) {
        //     foreach ($postContents->get() as $pc) {
        //         // if this post has no post_contents of other languages except the selected one,
        //         // then delete the post...
        //         $otherPcs = PostContent::where('language_id', '<>', $la->id)->where('post_id', $pc->post_id)->count();
        //         if ($otherPcs == 0) {
        //             $post = Post::findOrFail($pc->post_id);
        //             @unlink(public_path('assets/user/img/posts/' . $post->thumbnail_image));

        //             if (!empty($post->slider_images) && $post->slider_images != '[]') {
        //                 $sliders = json_decode($post->slider_images, true);
        //                 foreach ($sliders as $key => $slider) {
        //                     @unlink(public_path('assets/user/img/posts/slider-images/' . $slider));
        //                 }
        //             }

        //             $bookmarks = BookmarkPost::where('post_id', $post->id);
        //             if ($bookmarks->count() > 0) {
        //                 $bookmarks->delete();
        //             }

        //             $pvs = PostView::where('post_id', $post->id);
        //             if ($pvs->count() > 0) {
        //                 $pvs->delete();
        //             }
        //             $post->delete();
        //         }
        //         $pc->delete();
        //     }
        // }
        // // delete the post category images
        // if ($la->postCategory()->count() > 0) {
        //     $categories = $la->postCategory()->get();

        //     if (count($categories) > 0) {
        //         foreach ($categories as $category) {
        //             @unlink(public_path('assets/user/img/post-categories/' . $category->image));
        //             $category->delete();
        //         }
        //     }
        // }
        // // delete the gallery item's images
        // $items = $la->galleryItem()->get();

        // if (count($items) > 0) {
        //     foreach ($items as $item) {
        //         @unlink(public_path('assets/user/img/gallery/' . $item->image));
        //         $item->delete();
        //     }
        // }
        // if ($la->galleryCategory()->count() > 0) {
        //     $la->galleryCategory()->delete();
        // }
        // // delete the author's image & video background image
        // if ($la->authorInfo()->count() > 0) {
        //     $info = $la->authorInfo()->first();
        //     @unlink(public_path('assets/user/img/authors/' . $info->image));
        //     @unlink(public_path('assets/user/img/' . $info->video_background_image));
        //     $info->delete();
        // }
        // // delete the users faq
        // if ($la->faq()->count() > 0) {
        //     $la->faq()->delete();
        // }

        // $pageContents = PageContent::where('language_id', $la->id)->where('user_id', $user->id);
        // if ($pageContents->count() > 0) {
        //     foreach ($pageContents->get() as $pc) {
        //         // if this page has no page_contents of other languages except the selected one,
        //         // then delete the page...
        //         $otherPcs = PageContent::where('language_id', '<>', $la->id)->where('page_id', $pc->page_id)->count();
        //         if ($otherPcs == 0) {
        //             $page = Page::findOrFail($pc->page_id);
        //             $page->delete();
        //         }
        //         $pc->delete();
        //     }
        // }

        // // delete the popup images
        // if ($la->announcementPopup()->count() > 0) {
        //     $popups = $la->announcementPopup()->get();
        //     if (count($popups) > 0) {
        //         foreach ($popups as $popup) {
        //             @unlink(public_path('assets/user/img/popups/' . $popup->image));
        //             $popup->delete();
        //         }
        //     }
        // }
        // if ($la->footerQuickLink()->count() > 0) {
        //     $quickLinks = $la->footerQuickLink()->get();
        //     if (count($quickLinks) > 0) {
        //         foreach ($quickLinks as $quickLink) {
        //             $quickLink->delete();
        //         }
        //     }
        // }
        // // delete the user's footer text
        // if ($la->footerText()->count() > 0) {
        //     $la->footerText()->delete();
        // }

        // $testContents = TestimonialContent::where('language_id', $la->id);
        // if ($testContents->count() > 0) {
        //     foreach ($testContents->get() as $tc) {
        //         // if this testimonial has no testimonial_contents of other languages except the selected one,
        //         // then delete the testimonial...
        //         $otherTcs = TestimonialContent::where('language_id', '<>', $la->id)->where('testimonial_id', $tc->testimonial_id)->count();

        //         if ($otherTcs == 0) {
        //             $test = Testimonial::findOrFail($tc->testimonial_id);
        //             @unlink(public_path('assets/user/img/testimonials/' . $test->client_image));
        //             $test->delete();
        //         }
        //         $tc->delete();
        //     }
        // }
        $la->delete();
        return response()->json(['success' => "Language Delete Successfully"], 200);
    }
    public function languageKeywords(Request $request)
    {
        $data['la'] = Language::findOrFail($request->id);
        $data['user_keywords'] = json_decode($data['la']->keywords, true);

        return $data;
    }
    public function languageKeywordsUpdate(Request $request, $id, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $langCount = Language::where('user_id', $user->id)->count();
        $langLimit = UserPermissionHelper::currentPackagePermission($user->id)->language_limit;
        if ($langCount > $langLimit) {
            return response()->json(['warning' => "You have to delete " . ($langCount - $langLimit) . " languages to enable Editing Feature of Languages."], 200);
        }
        $lang = Language::findOrFail($id);
        $keywords = $request->all();
        $lang->keywords = json_encode($keywords[0]);
        $lang->save();

        return response()->json(['success' => "Kyewords Updated Successfully"], 200);
    }



    /*
    ==============================
    vCard
    ==============================
    */
    public function vCard($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $data['vcards'] = UserVcard::where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        return response()->json($data);
    }
    public function vCardAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $vcard_limit =  LimitCheckerHelper::vcardLimitchecker($user->id);
        $vcards = UserVcard::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        if ($vcards->count() >= $vcard_limit) {
            return response()->json(['success' => "maximum limit exceeded"], 200);
        }

        $profileImg = $request->file('profile_image');

        $coverImg = $request->file('cover_image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'vcard_name' => 'required|max:255',
            'template' => 'required',
            'direction' => 'required',
            'name' => 'nullable|max:255',
            'occupation' => 'nullable|max:255',
            'profile_image' => [
                'required',
                function ($attribute, $value, $fail) use ($profileImg, $allowedExts) {
                    if (!empty($profileImg)) {
                        $ext = $profileImg->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                        $size = $profileImg->getSize();
                        if ($size > 200000) {
                            return $fail("Image size cannot be greater than 200 KB");
                        }
                    }
                },
            ],
            'cover_image' => [
                function ($attribute, $value, $fail) use ($coverImg, $allowedExts) {
                    if (!empty($coverImg)) {
                        $ext = $coverImg->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
            'icons.*' => 'required',
            'colors.*' => 'required',
            'labels.*' => 'required',
            'values.*' => 'required',
        ];

        $messages = [
            'icons.*.required' => 'The Icon field cannot be empty',
            'colors.*.required' => 'The Color field cannot be empty',
            'labels.*.required' => 'The Label field cannot be empty',
            'values.*.required' => 'The Value field cannot be empty'
        ];


        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $vcard = new UserVcard();
        $vcard->user_id = $user->id;
        $vcard->vcard_name = $request->vcard_name;
        $vcard->direction = $request->direction;
        $vcard->template = $request->template;
        $vcard->profile_image = $request->profile_image;
        $vcard->cover_image = $request->cover_image;
        $vcard->name = $request->name;
        $vcard->occupation = $request->occupation;
        $vcard->company = $request->company;
        $vcard->email = $request->email;
        $vcard->phone = $request->phone;
        $vcard->address = $request->address;
        $vcard->website_url = $request->website_url;
        $vcard->introduction = $request->introduction;
        // language keywords
        $data = file_get_contents(resource_path('lang/') . 'vcard.json');
        $vcard->keywords = $data;
        $vcard->preferences = '["Call","Whatsapp","Mail","Add to Contact","Share vCard","Information","About Us","Video","Services","Projects","Testimonials","Enquiry Form"]';
        $infoArr = [];
        $labels = $request->labels ? $request->labels : [];
        $values = $request->values ? $request->values : [];
        $icons = $request->icons ? $request->icons : [];
        $colors = $request->colors ? $request->colors : [];
        $links = $request->links ? $request->links : [];

        foreach ($labels as $key => $label) {
            $info = [
                'icon' => $icons["$key"],
                'color' => $colors["$key"],
                'label' => $labels["$key"],
                'link' => in_array($key, $links) ? 1 : 0,
                'value' => $values["$key"]
            ];
            $infoArr[] = $info;
        }

        $vcard->information = json_encode($infoArr);
        $vcard->save();

        return response()->json(['success' => "Vcard added successfully"], 200);
    }
    public function vCardUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $vcards = UserVcard::where('user_id', $user->id)->where('id', $request->id)->first();
        return response()->json($vcards);
    }
    public function vCardUpdateSubmit(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rules = [
            'vcard_name' => 'required|max:255',
            'template' => 'required',
            'direction' => 'required',
            'name' => 'nullable|max:255',
            'occupation' => 'nullable|max:255',
            'icons.*' => 'required',
            'colors.*' => 'required',
            'labels.*' => 'required',
            'values.*' => 'required',
        ];

        $messages = [
            'icons.*.required' => 'The Icon field cannot be empty',
            'colors.*.required' => 'The Color field cannot be empty',
            'labels.*.required' => 'The Label field cannot be empty',
            'values.*.required' => 'The Value field cannot be empty'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $vcard = UserVcard::where('user_id', $user->id)->where('id', $request->id)->first();
        $vcard->user_id = $user->id;
        $vcard->vcard_name = $request->vcard_name;
        $vcard->direction = $request->direction;
        $vcard->template = $request->template;
        // profile_image
        if($request->profile_image != null){
            $vcard->profile_image = $request->profile_image;
            if ($vcard->profile_image != null) {
                // Storage::delete($user->profile_image);
            }
        }
        // cover_image
        if($request->cover_image != null){
            $vcard->cover_image = $request->cover_image;
            if ($vcard->cover_image != null) {
                // Storage::delete($user->cover_image);
            }
        }
        $vcard->name = $request->name;
        $vcard->occupation = $request->occupation;
        $vcard->company = $request->company;
        $vcard->email = $request->email;
        $vcard->phone = $request->phone;
        $vcard->address = $request->address;
        $vcard->website_url = $request->website_url;
        $vcard->introduction = $request->introduction;
        // language keywords
        $infoArr = [];
        $labels = $request->labels ? $request->labels : [];
        $values = $request->values ? $request->values : [];
        $icons = $request->icons ? $request->icons : [];
        $colors = $request->colors ? $request->colors : [];
        $links = $request->links ? $request->links : [];

        foreach ($labels as $key => $label) {
            $info = [
                'icon' => $icons["$key"],
                'color' => $colors["$key"],
                'label' => $labels["$key"],
                'link' => in_array($key, $links) ? 1 : 0,
                'value' => $values["$key"]
            ];
            $infoArr[] = $info;
        }

        $vcard->information = json_encode($infoArr);
        $vcard->save();

        return response()->json(['success' => "Vcard updated successfully"], 200);
    }

    // vCardDelete
    public function vCardDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $vcards = UserVcard::where('user_id', $user->id)->where('id', $request->id)->first();
        if ($vcards->profile_image != null) {
            // Storage::delete($vcards->profile_image);
        }
        // cover_image
        if ($vcards->cover_image != null) {
            // Storage::delete($user->cover_image);
        }
        $vcards->delete();

        return response()->json(['success' => "Vcard delete successfully"], 200);
    }



    /*
    ==============================
    Pages
    ==============================
    */
    public function page($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data['languages'] = Language::query()->where('user_id', $user->id)->get();
        $languageId = Language::where('is_default', 1)->where('user_id', '=', $user->id)->pluck('id')->first();
        $data['pages'] = DB::table('user_pages')
            ->join('user_page_contents', 'user_pages.id', '=', 'user_page_contents.page_id')
            ->where('user_page_contents.language_id', '=', $languageId)
            ->where('user_page_contents.user_id', '=', $user->id)
            ->orderByDesc('user_pages.id')
            ->get();

        return $data;
    }
    public function pageStore(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $language = Language::find($request->language_id);

        $messages = [];
        $rules = ['status' => 'required'];
        $rules['title'] = 'required|max:255';
        $messages['title.required'] = 'The title field is required for ' . $language->name . ' language.';
        $messages['title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $page = new Page();
        $page->status = $request->status;
        $page->user_id = $user->id;
        $page->save();

        $pageContent = new PageContent();
        $pageContent->language_id = $language->id;
        $pageContent->user_id = $user->id;
        $pageContent->page_id = $page->id;
        $pageContent->title = $request['title'];
        $pageContent->slug = make_slug($request['title']);
        $pageContent->content = $request->content != "Content" ? "" : $request->content;
        $pageContent->meta_keywords = $request['meta_keywords'];
        $pageContent->meta_description = $request['meta_description'];
        $pageContent->save();

        return response()->json(['success' => "New page added successfully!"], 200);
    }
    public function pageEdit($crypt, Request $request)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $information['languages'] = Language::where('user_id', $user->id)
        ->orderByRaw('id = ? DESC', [$user->default_language_id])
        ->latest()
        ->get();

        $information['pages'] = DB::table('user_pages')
        ->join('user_page_contents', 'user_pages.id', '=', 'user_page_contents.page_id')
        ->where('user_page_contents.user_id', '=', $user->id)
        ->where('user_pages.id', '=', $request->id)
        ->first();

        return $information;
    }
    public function pageUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $messages = [];
        $rules = ['status' => 'required'];
        $rules['title'] = 'required|max:255';
        $rules['content'] = 'required';
        $messages['title.required'] = 'The title field is required!';
        $messages['title.max'] = 'The title field cannot contain more than 255 characters!';
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }
        $page = Page::where('user_id', $user->id)->where('id', $request->id)->first();
        $page->status = $request->status;
        $page->save();

        $pageContent = PageContent::where('page_id', $page->id)->first();
        $pageContent->title = $request['title'];
        $pageContent->slug = make_slug($request['title']);
        $pageContent->content = $request->content;
        $pageContent->meta_keywords = $request['meta_keywords'];
        $pageContent->meta_description = $request['meta_description'];
        $pageContent->save();

        return response()->json(['success' => "New page added successfully!"], 200);
    }
    public function pageDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        Page::query()->where('id', $request->id)->where('user_id', $user->id)->delete();
        return response()->json(['success' => 'Page deleted successfully!'], 200);
    }


    // public function pageAdd(Request $request, $crypt)
    // {
    //     $user = User::find(Crypt::decrypt($crypt));

    //     $rules = ['status' => 'required'];
    //     $language = Language::where('user_id', $user->id)->where('is_default', 1)->first();

    //     $messages = [];
    //     $rules['title'] = 'required|max:255';
    //     $messages['title.required'] = 'The title field is required for ' . $language->name . ' language.';
    //     $messages['title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

    //     $validator = Validator::make($request->all(), $rules, $messages);
    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->all();
    //         $errorMessage = implode(', ', $errors);
    //         return response()->json(['error' => $errorMessage], 422);
    //     }

    //     $page = new Page();
    //     $page->status = $request->status;
    //     $page->user_id = $user->id;
    //     $page->save();

    //     $pageContent = new PageContent();
    //     $pageContent->language_id = $language->id;
    //     $pageContent->user_id = $user->id;
    //     $pageContent->page_id = $page->id;
    //     $pageContent->title = $request['title'];
    //     $pageContent->slug = make_slug($request['title']);
    //     $pageContent->content = "You can write anything here!";
    //     $pageContent->meta_keywords = $request['meta_keywords'];
    //     $pageContent->meta_description = $request['meta_description'];
    //     $pageContent->save();

    //     return response()->json(['success' => "New page added successfully!", 'id' => $pageContent->id], 200);
    // }
    // public function pageCheck(Request $request)
    // {
    //     $content = PageContent::where('id', $request->id)->first();
    //     return response()->json($content, 200);
    // }
    // public function pageUpdateContent(Request $request)
    // {
    //     $content = PageContent::where('id', $request->id)->first();
    //     $content -> content = $request->textarea;
    //     $content -> save();
    //     return response()->json(['success' => 'Your content successfully updated!'], 200);
    // }


    /*
    ==============================
    Domain
    ==============================
    */
    public function domainShow($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $rcDomain = UserCustomDomain::where('status', '<>', 2)->where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        
        return response()->json(['domain' => $rcDomain, 'user' => $user]);
    }
    public function isValidDomain($domain_name) {
        return (preg_match("/^([a-zd](-*[a-zd])*)(.([a-zd](-*[a-zd])*))*$/i", $domain_name) //valid characters check
        && preg_match("/^.{1,253}$/", $domain_name) //overall length check
        && preg_match("/^[^.]{1,63}(.[^.]{1,63})*$/", $domain_name) ); //length of every label
    }
    public function domainAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $be = BasicExtended::select('domain_request_success_message', 'cname_record_section_title')->first();

        $rules = [
            'custom_domain' => [
                'required',
                function ($attribute, $value, $fail) use ($be, $user) {
                    // if user requests the current domain
                    if (getCdomain($user) == $value) {
                        $fail('You cannot request your current domain.');
                    }
                    // check if domain is valid
                    if (!$this->isValidDomain($value)) {
                        $fail('Domain format is not valid');
                    }
                }
            ]
        ];
        $messages = [
            'custom_domain.required' => 'The custom domain field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $cdomain = new UserCustomDomain;
        $cdomain->user_id = $user->id;
        $cdomain->requested_domain = $request->custom_domain;
        $cdomain->current_domain = getCdomain($user);
        $cdomain->status = 0;
        $cdomain->save();

        return response()->json(['success' => $be->domain_request_success_message], 200);
    }
}
