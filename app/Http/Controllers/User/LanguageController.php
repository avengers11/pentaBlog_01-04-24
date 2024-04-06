<?php

namespace App\Http\Controllers\User;

use App\Http\Helpers\LimitCheckerHelper;
use App\Models\User\BookmarkPost;
use App\Models\User\Language;
use App\Models\User\Page;
use App\Models\User\PageContent;
use App\Models\User\Post;
use App\Models\User\PostContent;
use App\Models\User\PostView;
use App\Models\User\Testimonial;
use App\Models\User\TestimonialContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Menu;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Validator;
use Session;


class LanguageController extends Controller
{
    public function index($lang = false)
    {
        $data['languages'] = Language::where('user_id', Auth::id())->get();

        $data['langCount'] = Language::where('user_id', Auth::id())->count();
        $data['langLimit'] = UserPermissionHelper::currentPackagePermission(Auth::id())->language_limit;

        return view('user.language.index', $data);
    }

    public function store(Request $request)
    {
        $count = LimitCheckerHelper::currentLanguageCount(Auth::id()); //category added count of current package
        $category_limit = LimitCheckerHelper::languagesLimit(Auth::id()); //category limit count of current package
        if ($count >= $category_limit) {
            Session::flash('warning', 'Language Limit Exceeded');
            return "success";
        }
        $rules = [
            'name' => 'required|max:255',
            'code' => [
                'required',
                function ($attribute, $value, $fail) {
                    $language = Language::where([
                        ['code', $value],
                        ['user_id', Auth::id()]
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
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $deLang = Language::first();
        $in['name'] = $request->name;
        $in['code'] = $request->code;
        $in['rtl'] = $request->direction;
        $in['keywords'] = $deLang->keywords;
        $in['user_id'] = Auth::id();
        if (Language::where([
            ['is_default', 1],
            ['user_id', Auth::id()]
        ])->count() > 0) {
            $in['is_default'] = 0;
        } else {
            $in['is_default'] = 1;
        }
      $language = Language::create($in);
        //language create to default manu create
        $menu = new Menu();
        $menu->user_id = Auth::guard('web')->user()->id;
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

        Session::flash('success', 'Language added successfully!');
        return "success";
    }
    public function edit($id)
    {
        if ($id > 0) {
            $data['language'] = Language::findOrFail($id);
        }
        $data['id'] = $id;
        return view('user.language.edit', $data);
    }
    public function update(Request $request)
    {
        $langCount = Language::where('user_id', Auth::id())->count();
        $langLimit = UserPermissionHelper::currentPackagePermission(Auth::id())->language_limit;
        if ($langCount > $langLimit) {
            Session::flash('warning', "You have to delete " . ($langCount - $langLimit) . " languages to enable Editing Feature of Languages.");
            return "success";
        }
        $language = Language::findOrFail($request->language_id);
        // dd($language->id);

        $rules = [
            'name' => 'required|max:255',
            'code' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $language = Language::where([
                        ['code', $value],
                        ['user_id', Auth::guard('web')->user()->id],
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
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $language->name = $request->name;
        $language->code = $request->code;
        $language->rtl = $request->direction;
        $language->user_id = Auth::id();
        $language->save();
        Session::flash('success', 'Language updated successfully!');
        return "success";
    }

    public function editKeyword($id)
    {
        $data['la'] = Language::findOrFail($id);
        $data['user_keywords'] = json_decode($data['la']->keywords, true);
        return view('user.language.edit-keyword', $data);
    }
    public function updateKeyword(Request $request, $id)
    {
        $langCount = Language::where('user_id', Auth::id())->count();
        $langLimit = UserPermissionHelper::currentPackagePermission(Auth::id())->language_limit;
        if ($langCount > $langLimit) {
            Session::flash('warning', "You have to delete " . ($langCount - $langLimit) . " languages to enable Editing Feature of Languages.");
            return back();
        }
        $lang = Language::findOrFail($id);
        $keywords = $request->except('_token');
        $lang->keywords = json_encode($keywords);
        $lang->save();
        return back()->with('success', 'Kyewords Updated Successfully');
    }


    public function delete($id)
    {

        $la = Language::findOrFail($id);
        if ($la->is_default == 1) {
            return back()->with('warning', 'Default language cannot be deleted!');
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
        $postContents = PostContent::where('language_id', $la->id);
        if ($postContents->count() > 0) {
            foreach ($postContents->get() as $pc) {
                // if this post has no post_contents of other languages except the selected one,
                // then delete the post...
                $otherPcs = PostContent::where('language_id', '<>', $la->id)->where('post_id', $pc->post_id)->count();
                if ($otherPcs == 0) {
                    $post = Post::findOrFail($pc->post_id);
                    @unlink(public_path('assets/user/img/posts/' . $post->thumbnail_image));

                    if (!empty($post->slider_images) && $post->slider_images != '[]') {
                        $sliders = json_decode($post->slider_images, true);
                        foreach ($sliders as $key => $slider) {
                            @unlink(public_path('assets/user/img/posts/slider-images/' . $slider));
                        }
                    }

                    $bookmarks = BookmarkPost::where('post_id', $post->id);
                    if ($bookmarks->count() > 0) {
                        $bookmarks->delete();
                    }

                    $pvs = PostView::where('post_id', $post->id);
                    if ($pvs->count() > 0) {
                        $pvs->delete();
                    }
                    $post->delete();
                }
                $pc->delete();
            }
        }
        // delete the post category images
        if ($la->postCategory()->count() > 0) {
            $categories = $la->postCategory()->get();

            if (count($categories) > 0) {
                foreach ($categories as $category) {
                    @unlink(public_path('assets/user/img/post-categories/' . $category->image));
                    $category->delete();
                }
            }
        }
        // delete the gallery item's images
        $items = $la->galleryItem()->get();

        if (count($items) > 0) {
            foreach ($items as $item) {
                @unlink(public_path('assets/user/img/gallery/' . $item->image));
                $item->delete();
            }
        }
        if ($la->galleryCategory()->count() > 0) {
            $la->galleryCategory()->delete();
        }
        // delete the author's image & video background image
        if ($la->authorInfo()->count() > 0) {
            $info = $la->authorInfo()->first();
            @unlink(public_path('assets/user/img/authors/' . $info->image));
            @unlink(public_path('assets/user/img/' . $info->video_background_image));
            $info->delete();
        }
        // delete the users faq
        if ($la->faq()->count() > 0) {
            $la->faq()->delete();
        }

        $pageContents = PageContent::where('language_id', $la->id)->where('user_id', Auth::id());
        if ($pageContents->count() > 0) {
            foreach ($pageContents->get() as $pc) {
                // if this page has no page_contents of other languages except the selected one,
                // then delete the page...
                $otherPcs = PageContent::where('language_id', '<>', $la->id)->where('page_id', $pc->page_id)->count();
                if ($otherPcs == 0) {
                    $page = Page::findOrFail($pc->page_id);
                    $page->delete();
                }
                $pc->delete();
            }
        }

        // delete the popup images
        if ($la->announcementPopup()->count() > 0) {
            $popups = $la->announcementPopup()->get();
            if (count($popups) > 0) {
                foreach ($popups as $popup) {
                    @unlink(public_path('assets/user/img/popups/' . $popup->image));
                    $popup->delete();
                }
            }
        }
        if ($la->footerQuickLink()->count() > 0) {
            $quickLinks = $la->footerQuickLink()->get();
            if (count($quickLinks) > 0) {
                foreach ($quickLinks as $quickLink) {
                    $quickLink->delete();
                }
            }
        }
        // delete the user's footer text
        if ($la->footerText()->count() > 0) {
            $la->footerText()->delete();
        }

        $testContents = TestimonialContent::where('language_id', $la->id);
        if ($testContents->count() > 0) {
            foreach ($testContents->get() as $tc) {
                // if this testimonial has no testimonial_contents of other languages except the selected one,
                // then delete the testimonial...
                $otherTcs = TestimonialContent::where('language_id', '<>', $la->id)->where('testimonial_id', $tc->testimonial_id)->count();

                if ($otherTcs == 0) {
                    $test = Testimonial::findOrFail($tc->testimonial_id);
                    @unlink(public_path('assets/user/img/testimonials/' . $test->client_image));
                    $test->delete();
                }
                $tc->delete();
            }
        }

        // if the  deletable language is the currently selected language in frontend then forget the selected language from session
        session()->forget('user_lang');
        $la->delete();
        return back()->with('success', 'Language Delete Successfully');
    }


    public function default(Request $request, $id)
    {
        Language::where('is_default', 1)->where('user_id', Auth::user()->id)->update(['is_default' => 0]);
        $lang = Language::find($id);
        $lang->is_default = 1;
        $lang->save();
        return back()->with('success', $lang->name . ' language is set as default.');
    }

    public function rtlcheck($langid)
    {
        if ($langid > 0) {
            $lang = Language::find($langid);
        } else {
            return 0;
        }
        return $lang->rtl;
    }
}
