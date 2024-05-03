<?php

namespace App\Http\Controllers\Pentaforce;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\BasicSetting;
use App\Models\User\PostCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\LimitCheckerHelper;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Post;
use App\Models\User\PostContent;
use Mews\Purifier\Facades\Purifier;

class PostsApiController extends Controller
{
    //setting
    public function setting($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data = BasicSetting::where('user_id', $user->id)->select('post_view_type')->first();

        return response()->json($data);
    }
    public function settingUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $rule = ['post_view_type' => 'required'];

        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        // store the view type info into db
        BasicSetting::where('user_id', $user->id)->updateOrInsert(
            ['user_id' => $user->id],
            ['post_view_type' => $request->post_view_type]
        );

        return response()->json(['success' => 'You are successfully update your settings!'], 200);
    }

    // Category
    public function category(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        // first, get the language info from db
        $language = Language::where('user_id', $user->id)->first();
        $information['language'] = $language;

        $information['count'] = LimitCheckerHelper::currentPostCategoryCount($user->id, $language->id);//category added count of selected language
        $information['category_limit'] = LimitCheckerHelper::postCategoriesLimit($user->id);//category limit

        $information['categoryCount'] = PostCategory::where('user_id', $user->id)->count();
        $information['categoryLimit'] = UserPermissionHelper::currentPackagePermission($user->id)->post_categories_limit;

        // then, get the post categories of that language from db
        $information['categories'] = PostCategory::where('language_id', $language->id)
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        // also, get all the languages from db
        $information['langs'] = Language::where('user_id', $user->id)->get();

        return $information;
    }
    public function categoryAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $count = LimitCheckerHelper::currentPostCategoryCount($user->id, $request->user_language_id);//category added count of selected language
        $category_limit = LimitCheckerHelper::postCategoriesLimit($user->id);//category limit
        if($count >= $category_limit){
            return response()->json(['success' => 'Post Category Limit Exceeded!'], 200);
        }

        $theme = BasicSetting::where('user_id', $user->id)->first()->theme_version;

        $rules = [
            'user_language_id' => 'required',
            'name' => 'required',
            'status' => 'required',
            'serial_number' => 'required'
        ];

        if ($theme == 1 || $theme == 6 || $theme == 7) {
            $rules['image'] = 'required';
        }


        $message = [
            'user_language_id.required' => 'The language field is required.',
            'name.required' => 'The category name is required.',
            'status.required' => 'The status field is required.',
            'serial_number.required' => 'The serial number field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        if ($theme == 1 || $theme == 6 || $theme == 7) {
            $imgName = $request->image;
        }

        PostCategory::create($request->except('image','language_id','user_id') + [
                'language_id' => $request->user_language_id,
                'image' => $theme == 1 || $theme == 6 || $theme == 7 ? $imgName : NULL,
                'user_id' => $user->id
            ]);

        return response()->json(['success' => 'New post category added successfully!'], 200);
    }
    public function categoryUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $count = LimitCheckerHelper::currentPostCategoryCount($user->id, $request->user_language_id);//category added count of selected language
        $category_limit = LimitCheckerHelper::postCategoriesLimit($user->id);//category limit
        if($count >= $category_limit){
            return response()->json(['success' => 'Post Category Limit Exceeded!'], 200);
        }

        $theme = BasicSetting::where('user_id', $user->id)->first()->theme_version;

        $rules = [
            'name' => 'required',
            'status' => 'required',
            'serial_number' => 'required'
        ];

        $message = [
            'name.required' => 'The category name is required.',
            'status.required' => 'The status field is required.',
            'serial_number.required' => 'The serial number field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $category = PostCategory::where('user_id', $user->id)->where('id', $request->cat_id)->first();
        if ($theme == 1 || $theme == 6 || $theme == 7) {
            $imgName = $request->image;
            if($request->image != null){
                $imgName = $request->image;
                if ($user->image != null) {
                    Storage::delete($user->image);
                }
            }else{
                $imgName = $category->image;
            }
        }
        $category->image = $theme == 1 || $theme == 6 || $theme == 7 ? $imgName : NULL;
        $category->is_featured = $request->is_featured;
        $category->status = $request->status;
        $category->name = $request->name;
        $category->serial_number = $request->serial_number;
        $category->save();

        return response()->json(['success' => 'Post category updated successfully!'], 200);
    }

    public function categoryDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $category = PostCategory::where('id', $request->id)->where('user_id', $user->id)->first();
        if ($category->postContentList()->count() > 0) {
            return response()->json(['error' => 'First delete all the posts of this category!'], 200);
        } else {
            if ($category->image != null) {
                Storage::delete($category->image);
            }

            $category->delete();
            return response()->json(['success' => 'Post category deleted successfully!'], 200);
        }
    }

    // post
    public function post(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $information['count'] = LimitCheckerHelper::currentPostsCount($user->id);//count of posts
        $information['limit'] = LimitCheckerHelper::postsLimit($user->id);

        $information['featuredCount'] = LimitCheckerHelper::currentFeaturedPostsCount($user->id);//count of current featured posts
        $information['featuredLimit'] = LimitCheckerHelper::featurePostsLimit($user->id);

        $languageId =   Language::where('user_id', $user->id)->pluck('id')->first();;
        $information['posts'] = DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $languageId)
            ->where('post_contents.user_id', '=', $user->id)
            ->orderByDesc('posts.id')
            ->get();

        $information['themeInfo'] = BasicSetting::where('user_id', '=', $user->id)->select('theme_version')->first();
        $information['category'] = PostCategory::where('user_id', $user->id)->get();

        return response()->json($information);
    }
    public function postAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $count = LimitCheckerHelper::currentPostsCount($user->id);

        $limit = LimitCheckerHelper::postsLimit($user->id);
        if($count >= $limit){
            return response()->json(['error' => 'Post Limit Exceeded!'], 200);
        }

        $rules = [
            'serial_number' => 'required'
        ];

        $thumbnailImgURL = $request->thumbnail_image;
        $sliderImgURLs = $request->has('image') ? $request->image : [];

        $thumbnailImgExt = null;

        $sliderImgExts = [];

        // get all the slider images extension
        // if (!empty($sliderImgURLs)) {
        //     foreach ($sliderImgURLs as $sliderImgURL) {
        //         $n = strrpos($sliderImgURL, ".");
        //         $extension = ($n === false) ? "" : substr($sliderImgURL, $n + 1);
        //         array_push($sliderImgExts, $extension);
        //     }
        // }

        $languages = Language::where('user_id', $user->id)->get();

        $messages = [];

        foreach ($languages as $language) {
            $slug = make_slug($request[$language->code . '_title']);
            $rules[$language->code . '_title'] = [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($slug, $language, $user) {
                    $pcs = PostContent::where('user_id', $user->id)->get();
                    foreach ($pcs as $key => $pc) {
                        if (strtolower($slug) == strtolower($pc->slug)) {
                            $fail('The title field must be unique for ' . $language->name . ' language.');
                        }
                    }
                }
            ];
            $rules[$language->code . '_category'] = 'required';
            $rules[$language->code . '_author'] = 'required';
            // $rules[$language->code . '_content'] = 'required|min:15';

            $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

            $messages[$language->code . '_category.required'] = 'The category field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_author.required'] = 'The author field is required for ' . $language->name . ' language.';

            // $messages[$language->code . '_content.required'] = 'The content field is required for ' . $language->name . ' language.';

            // $messages[$language->code . '_content.min'] = 'The content field at least have 15 characters for ' . $language->name . ' language.';
        }
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $post = new Post();
        $post->thumbnail_image = $request->thumbnail_image;
        $post->slider_images = json_encode($request->slider_images);
        $post->serial_number = $request->serial_number;
        $post->user_id = $user->id;
        $post->save();
        foreach ($languages as $language) {
            $postContent = new PostContent();
            $postContent->language_id = $language->id;
            $postContent->user_id = $user->id;
            $postContent->post_category_id = $request[$language->code . '_category'];
            $postContent->post_id = $post->id;
            $postContent->title = $request[$language->code . '_title'];
            $postContent->slug = make_slug($request[$language->code . '_title']);
            $postContent->author = $request[$language->code . '_author'];
            $postContent->content = Purifier::clean($request[$language->code . '_content']);
            $postContent->meta_keywords = $request[$language->code . '_meta_keywords'];
            $postContent->meta_description = $request[$language->code . '_meta_description'];
            $postContent->save();
        }

        return response()->json(['success' => 'Post added successfully!', 'id' => $post->id], 200);
    }

    // postCheck
    public function postCheck(Request $request)
    {
        $content = PostContent::where('post_id', $request->id)->first();
        return response()->json($content, 200);
    }
    // postUpdateContent
    public function postUpdateContent(Request $request)
    {
        $content = PostContent::where('post_id', $request->id)->first();
        $content -> content = $request->textarea;
        $content -> save();
        return response()->json(['success' => 'Your content successfully updated!'], 200);
    }

    public function postDelete(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));


        $post = Post::where('id', $request->id)->where('user_id', $user->id)->first();

        // first, delete the thumbnail image
        // @unlink(public_path('assets/user/img/posts/' . $post->thumbnail_image));

        // second, delete the slider images
        // $postSldImgs = json_decode($post->slider_images, true);

        // if (!empty($postSldImgs)) {
        //     foreach ($postSldImgs as $postSldImg) {
        //         @unlink(public_path('assets/user/img/posts/slider-images/' . $postSldImg));
        //     }
        // }

        // third, delete the slider-post-image
        // @unlink(public_path('assets/user/img/posts/' . $post->slider_post_image));

        // // fourth, delete the featured-post-image
        // @unlink(public_path('assets/user/img/posts/' . $post->featured_post_image));

        $post->delete();
        return response()->json(['success' => 'Post deleted successfully!'], 200);
    }

}
