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
use App\Models\User\UserCustomDomain;

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
        $language = Language::where('user_id', $user->id)->where('is_default', 1)->first();
        $information['language'] = $language;

        $information['categories'] = PostCategory::where('post_categories.user_id', $user->id)
        ->join('user_languages', 'post_categories.language_id', '=', 'user_languages.id')
        ->orderBy('post_categories.serial_number', 'desc')
        ->get([
            'post_categories.*', 
            DB::raw('user_languages.name as user_languages')
        ]);


        $information['langs'] = Language::where('user_id', $user->id)->get();

        return $information;
    }
    public function categoryAdd(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $theme = BasicSetting::where('user_id', $user->id)->first()->theme_version;

        $maxSerialNumber = PostCategory::where('user_id', $user->id)
        ->orderBy('serial_number', 'desc')
        ->pluck("serial_number")
        ->first();

        $rules = [
            'user_language_id' => 'required',
            'name' => 'required',
            'status' => 'required',
        ];
        if ($theme == 1 || $theme == 6 || $theme == 7) {
            $rules['image'] = 'required';
        }
        $message = [
            'user_language_id.required' => 'The language field is required.',
            'name.required' => 'The category name is required.',
            'status.required' => 'The status field is required.',
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
        
        $request["serial_number"] = $maxSerialNumber+1;
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

    /*
    ===========================
    post
    ===========================
    */
    public function getPostData(Request $req, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $information['posts'] = DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')

            ->where('post_contents.user_id', '=', $user->id)
            // ->where('post_contents.id', '=', $req->post_id)
            ->where('post_contents.id', '=', $req->content_id)
            
            ->orderByDesc('posts.id')
            ->first();

        return response()->json($information);
    }
    public function post($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $languageId = Language::where('is_default', 1)->where('user_id', $user->id)->pluck('id')->first();

        $information['posts'] = DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $languageId)
            ->where('post_contents.user_id', '=', $user->id)
            ->where('posts.is_featured', '!=', 10)
            ->orderByDesc('posts.id')
            ->get();

        return response()->json($information);
    }
    public function totalPost($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $languageId =   Language::where('is_default', 1)->where('user_id', $user->id)->pluck('id')->first();

        return  DB::table('posts')
        ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
        ->where('post_contents.language_id', '=', $languageId)
        ->where('post_contents.user_id', '=', $user->id)
        ->orderByDesc('posts.id')
        ->count();
    }
    public function postAddClear($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        if(Post::where('user_id', $user->id)->where('is_featured', 10)->exists()){
            $post = Post::where('user_id', $user->id)->where('is_featured', 10)->first();

            $post->slider_images = json_encode([]);
            $post->thumbnail_image = 'thumbnail_image.png';
            $post->is_featured = 10;
            $post->user_id = $user->id;
            $post->save();
            
            // post content 
            $newPreview = [
                "title" => "",
                "slug" => make_slug(""),
                "content" => Purifier::clean(""),
                "author" => "",
                "meta_keywords" => null,
                "meta_keyword_ids" => null,
                "meta_description" => null,
                "post_category_id" => null,
                "content_id" => 0,
            ];
            PostContent::where("user_id", $user->id)->where("post_id", $post->id)->update([
                "post_category_id" => 0,
                "title" => 0,
                "slug" => 0,
                "author" => 0,
                "content" => 0,
                "preview" => json_encode($newPreview)
            ]);
        }

        return response()->json(["status" => true]);
    }
    public function postCreate($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $information['languages'] = Language::where('user_id', $user->id)->get();
        $information['feature_language'] = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        $information['categories'] = PostCategory::where('user_id', $user->id) ->where('language_id', $information['feature_language']->id)->get();

        $isNew = false;
        if(!Post::where('user_id', $user->id)->where('is_featured', 10)->exists()){
            $latestPost = Post::where('user_id', $user->id)->latest()->first();
            $isNew = true;

            $post = new Post();
            $post->slider_images = json_encode([]);
            $post->thumbnail_image = 'thumbnail_image.png';
            $post->serial_number =  isset($latestPost->serial_number) ? $latestPost->serial_number + 1 : 1;
            $post->is_featured = 10;
            $post->user_id = $user->id;
            $post->save();
            
            foreach ($information['languages'] as $language) {
                $postContent = new PostContent();
                $postContent->language_id = $language->id;
                $postContent->user_id = $user->id;
                $postContent->post_id = $post->id;
                $postContent->post_category_id = 0;
                $postContent->title = "";
                $postContent->slug = "";
                $postContent->author = "";
                $postContent->content = "";
                $postContent->save();
            }
        }
        $information["post"] = Post::where('user_id', $user->id)->where('is_featured', 10)->first();

        return response()->json(["information" => $information, "isNew" => $isNew]);
    }
    public function postCreatePerticles($crypt, Request $req)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $information['languages'] = Language::where('user_id', $user->id)->get();
        $information['feature_language'] = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        $information["post"] = Post::find($req->post_id);
        // languageId
        $languageId = $req->language_id;
        if($languageId == null){
            $languageId = $information['feature_language']->id;
        }

        // create new  
        if(!PostContent::where('user_id', $user->id)->where('language_id', $languageId)->where('post_id', $information["post"]->id)->exists()){
            $postContent = new PostContent();
            $postContent->language_id = $languageId;
            $postContent->user_id = $user->id;
            $postContent->post_id = $information["post"]->id;
            $postContent->post_category_id = 0;
            $postContent->title = "";
            $postContent->slug = "";
            $postContent->author = "";
            $postContent->content = "";
            $postContent->content_id = "";
            $postContent->save();
        }

        $information['domain'] = UserCustomDomain::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        $information['basic'] = BasicSetting::where('user_id', $user->id)->first();
        $information['user'] = $user;

        
        $information['this_languages'] = Language::where('id', $languageId)->first();
        $information['categories'] = PostCategory::where('user_id', $user->id)->where('language_id', $information['this_languages']->id)->get();
        // content 
        $information["content"] = PostContent::where('user_id', $user->id)->where('language_id', $languageId)->where('post_id', $information["post"]->id)->first();
        $information["type"] = $information["post"]->is_featured == 10 ? 0 : 1;

        return response()->json($information);
    }

    // save 
    public function postSave(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $blog = PostContent::where('user_id', $user->id)->where('language_id', $request->language_id)->where('post_id', $request->post_id)->first();
        $newPreview = [
            "title" => $request->title,
            "slug" => make_slug($request->title),
            "content" => Purifier::clean($request->content),
            "author" => $request->author,
            "meta_keywords" => $request->meta_keywords,
            "meta_keyword_ids" => $request->meta_keyword_ids,
            "meta_description" => $request->meta_description,
            "post_category_id" => $request->post_category_id,
            "content_id" => $request->content_id,
        ];
        $blog->preview = json_encode($newPreview);
        $blog->save();

        return response()->json(["status" => true, 'message' => "Your post successfully saved.", "preview" => json_decode($blog->preview)], 200);
    }
    // postPreviewClear
    public function postPreviewClear(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $blog = PostContent::where('user_id', $user->id)->where('language_id', $request->language_id)->where('post_id', $request->post_id)->first();
        $blog->preview = null;
        $blog->save();

        return response()->json(["status" => true, 'message' => "Your post preview successfully clear."], 200);
    }
    // postAddImagesUpload
    public function postAddImagesUpload(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $post = Post::where('user_id', $user->id)->where('is_featured', 10)->first();

        if($request->thumbnail_image != null){
            $post->thumbnail_image = $request->thumbnail_image;
        }
        if($request->slider_images != null){
            $post->slider_images = json_encode([$request->slider_images]);
        }
        $post->save();

        return response()->json(['status' => true, 'message' => 'Post images successfully updated!'], 200);
    }
    // postAddDelete
    public function postAddDelete($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $post = Post::where('user_id', $user->id)->where('is_featured', 10)->first();
        PostContent::where("post_id", $post->id)->delete();

        return response()->json(['status' => true, 'message' => 'Post successfully deleted!'], 200);
    }
    public function postPublished(Request $request, $crypt)
    {

        // try {
            // DB::transaction(function () use ($crypt, $request) {
                // Decrypt user
                $user = User::find(Crypt::decrypt($crypt));

                // Update the previous featured post
                $post = Post::find($request->post_id);

                // thumbnail_image
                if(empty($post->thumbnail_image) || $post->thumbnail_image == "thumbnail_image.png"){
                    return response()->json(['status' => false, 'message' => 'Thumnail image is required!']);
                }
                // slider_images
                if(empty($post->slider_images) || $post->slider_images == "[]"){
                    return response()->json(['status' => false, 'message' => 'Slider image is required!']);
                }

                $post->is_featured = $post->is_featured == 10 ? 0 : $post->is_featured;
                $post->save();

                // Publish the current content
                $postContent = PostContent::where('user_id', $user->id)
                                        ->where('language_id', $request->language_id)
                                        ->where('post_id', $request->post_id)
                                        ->first();
                
                $preview = $postContent->preview != null ? json_decode($postContent->preview) : null;

                if ($preview != null) {
                    $postContent->post_category_id = $preview->post_category_id;
                    $postContent->title = $preview->title;
                    $postContent->slug = make_slug($preview->title);
                    $postContent->author = $preview->author;
                    $postContent->content = Purifier::clean($preview->content);
                    $postContent->meta_keywords = $preview->meta_keywords;
                    $postContent->meta_keyword_ids = $preview->meta_keyword_ids;
                    $postContent->meta_description = $preview->meta_description;
                    $postContent->preview = null;
                    $postContent->save();
                }

                // Fetch slugs for the post content
                // $postContent = PostContent::where('post_contents.user_id', $user->id)
                //     ->where('post_contents.post_id', $post->id)
                //     ->join('user_languages', 'post_contents.language_id', '=', 'user_languages.id')
                //     ->select('user_languages.code as language_code', 'post_contents.slug')
                //     ->get();

                // Prepare data for response
                $postPassingData = [
                    'post_id' => $post->id,
                    "content_id" => $postContent->id,
                    "title" => $postContent->title,
                    "slug" => $postContent->slug,
                    "keywords" => $postContent->meta_keyword_ids
                ];

                // Return success response
                return response()->json(['status' => true, 'message' => 'Post successfully published!', 'post_data' => $postPassingData], 200);
            // });
        // } catch (\Exception $e) {
        //     return response()->json(['status' => false, 'error' => 'Failed to add the post. Please try again.'], 500);
        // }
    }
    public function postEdit($crypt, Request $request)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $information['languages'] = Language::where('user_id', $user->id)->get();
        $information['feature_language'] = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        $information['categories'] = PostCategory::where('user_id', $user->id) ->where('language_id', $information['feature_language']->id)->get();
        $information["post"] = Post::find($request->post_id);

        return response()->json(["information" => $information]);
    }
    public function postUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $count = LimitCheckerHelper::currentPostsCount($user->id);//count of current package
        $limit = LimitCheckerHelper::postsLimit($user->id);//limit count of current package
        if($count >= $limit){
            return response()->json(['warning' => 'Post Limit Exceeded!'], 200);
        }

        $rules = [
            'serial_number' => 'required'
        ];

        $languages = Language::where('user_id', $user->id)->get();

        $messages = [];
        foreach ($languages as $language) {
            $rules[$language->code . '_title'] = 'required';
            $rules[$language->code . '_category'] = 'required';
            $rules[$language->code . '_author'] = 'required';
            $rules[$language->code . '_content'] = 'required|min:15';
            $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';
            $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';
            $messages[$language->code . '_category.required'] = 'The category field is required for ' . $language->name . ' language.';
            $messages[$language->code . '_author.required'] = 'The author field is required for ' . $language->name . ' language.';
            $messages[$language->code . '_content.required'] = 'The content field is required for ' . $language->name . ' language.';
            $messages[$language->code . '_content.min'] = 'The content field at least have 15 characters for ' . $language->name . ' language.';
        }


        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return response()->json(['error' => $errorMessage], 422);
        }

        $post = Post::where('id', $request->id)->where('user_id', $user->id)->first();
        if($request->thumbnail_image != null){
            $post->thumbnail_image = $request->thumbnail_image;
        }
        if($request->galleries != null){
            $post->slider_images = json_encode([$request->galleries]);
        }
        $post->serial_number = $request->serial_number;
        $post->save();

        foreach ($languages as $language) {
            $postContent = PostContent::where('user_id', $user->id)->where('post_id', $post->id)->where('language_id', $language->id)->first();
            $postContent->post_category_id = $request[$language->code . '_category'];
            $postContent->title = $request[$language->code . '_title'];
            $postContent->slug = make_slug($request[$language->code . '_title']);
            $postContent->author = $request[$language->code . '_author'];
            $postContent->content = Purifier::clean($request[$language->code . '_content']);
            $postContent->meta_keywords = $request[$language->code . '_meta_keywords'];
            $postContent->meta_description = $request[$language->code . '_meta_description'];
            $postContent->save();
        }

        return response()->json(['success' => 'Post updated successfully!', 'id' => $post->id], 200);
    }
    public function postSliderUpdate(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $post = Post::where('id', $request->post_id)->where('user_id', $user->id)->first();
        $post->is_slider = $post->is_slider == 1 ? 0 : 1;
        $post->save();

        return response()->json(['status' => true, 'message' => 'Post slider update successfully!'], 200);
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
        PostContent::where("post_id", $post->id)->delete();
        $post->delete();

        return response()->json(['status' => true, 'message' => 'Post successfully deleted!'], 200);
    }

    // postByCategory
    public function postByCategory(Request $request, $crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $languageId = Language::where('is_default', 1)->where('user_id', $user->id)->pluck('id')->first();

        return PostContent::where("language_id", $languageId)->where("post_category_id", $request->category_id)->get();
    }
}
