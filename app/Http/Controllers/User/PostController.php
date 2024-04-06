<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\LimitCheckerHelper;
use App\Http\Helpers\Uploader;
use App\Models\User\BasicSetting;
use App\Models\User\Language;
use App\Models\User\Post;
use App\Models\User\PostContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {
        $information['count'] = LimitCheckerHelper::currentPostsCount(Auth::id());//count of posts
        $information['limit'] = LimitCheckerHelper::postsLimit(Auth::id());//limit count of current package

        $information['featuredCount'] = LimitCheckerHelper::currentFeaturedPostsCount(Auth::id());//count of current featured posts
        $information['featuredLimit'] = LimitCheckerHelper::featurePostsLimit(Auth::id());//limit count of current package

        $languageId =   Language::where('code', $request->language)->where('user_id', Auth::id())->pluck('id')->first();;
        $information['posts'] = DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $languageId)
            ->where('post_contents.user_id', '=', Auth::id())
            ->orderByDesc('posts.id')
            ->get();

        $information['themeInfo'] = BasicSetting::where('user_id', '=', Auth::id())->select('theme_version')->first();

        return view('user.post.posts', $information);
    }

    /**
     * Show the form for creating a new resource.
     *
     *
     */
    public function create()
    {
        // get all the languages from db
        $information['languages'] = Language::where('user_id', Auth::id())->get();
        return view('user.post.create-post', $information);
    }

    public function store(Request $request)
    {
        $count = LimitCheckerHelper::currentPostsCount(Auth::id());//count of current package
        $limit = LimitCheckerHelper::postsLimit(Auth::id());//limit count of current package
        if($count >= $limit){
            Session::flash('warning', 'Post Limit Exceeded');
            return "success";
        }

        $rules = [
            'serial_number' => 'required'
        ];

        $thumbnailImgURL = $request->thumbnail_image;
        $sliderImgURLs = $request->has('image') ? $request->image : [];

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
        $thumbnailImgExt = $thumbnailImgURL ? $thumbnailImgURL->extension() : null;

        $sliderImgExts = [];


        // get all the slider images extension
        if (!empty($sliderImgURLs)) {
            foreach ($sliderImgURLs as $sliderImgURL) {
                $n = strrpos($sliderImgURL, ".");
                $extension = ($n === false) ? "" : substr($sliderImgURL, $n + 1);
                array_push($sliderImgExts, $extension);
            }
        }

        $rules['thumbnail_image'] = [
            'required',
            function ($attribute, $value, $fail) use ($allowedExtensions, $thumbnailImgExt) {
                if (!in_array($thumbnailImgExt, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed for thumbnail image.');
                }
            }
        ];

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

        $languages = Language::where('user_id', Auth::id())->get();

        $messages = [];

        foreach ($languages as $language) {
            $slug = make_slug($request[$language->code . '_title']);
            $rules[$language->code . '_title'] = [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($slug, $language) {
                    $pcs = PostContent::where('user_id', Auth::id())->get();
                    foreach ($pcs as $key => $pc) {
                        if (strtolower($slug) == strtolower($pc->slug)) {
                            $fail('The title field must be unique for ' . $language->name . ' language.');
                        }
                    }
                }
            ];
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
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $post = new Post();

        // set a name for the thumbnail image and store it to local storage
        $thumbnailImgName = time() . '.' . $thumbnailImgExt;
        $thumbnailDir = public_path('./assets/user/img/posts/');

        @mkdir($thumbnailDir, 0775, true);

        @copy($thumbnailImgURL, $thumbnailDir . $thumbnailImgName);

        $sliderImgs = [];

        $sliderDir = public_path('./assets/user/img/posts/slider-images/');

        @mkdir($sliderDir, 0775, true);

        $post->thumbnail_image = $thumbnailImgName;
        $post->slider_images = json_encode($request->image);
        $post->serial_number = $request->serial_number;
        $post->user_id = Auth::id();
        $post->save();

        foreach ($languages as $language) {
            $postContent = new PostContent();
            $postContent->language_id = $language->id;
            $postContent->user_id = Auth::id();
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

        $request->session()->flash('success', 'New post added successfully!');

        return 'success';
    }

    /**
     * Update the slider-post status of specified resource.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return
     */
    public function updateSliderPost(Request $request)
    {
        if ($request->is_slider == 1) {
            $sldPostImgURL = $request->hasFile('slider_post_image') ? $request->slider_post_image : null;
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
            $sldPostImgExt = $request->hasFile('slider_post_image') ? $sldPostImgURL->extension() : null;

            $rules = [
                'slider_post_image' => [
                    'required',
                    function ($attribute, $value, $fail) use ($allowedExtensions, $sldPostImgExt) {
                        if (!in_array($sldPostImgExt, $allowedExtensions)) {
                            $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                        }
                    }
                ]
            ];

            $message = [
                'slider_post_image.required' => 'The image field is required.'
            ];

            $validator = Validator::make($request->all(), $rules, $message);

            if ($validator->fails()) {
                return Response::json([
                    'errors' => $validator->getMessageBag()->toArray()
                ], 400);
            }

            // set a name for the image and store it to local storage
            $sldPostImgName = time() . '.' . $sldPostImgExt;
            $sldPostImgDir = public_path('./assets/user/img/posts/');

            @copy($sldPostImgURL, $sldPostImgDir . $sldPostImgName);

            // update data in db
            $post = Post::findOrFail($request->id);

            $post->update([
                'is_slider' => 1,
                'slider_post_image' => $sldPostImgName
            ]);

            $request->session()->flash('success', 'Post added for slider!');

            return 'success';
        } else {
            $post = Post::findOrFail($request->id);

            // first, delete the image
            @unlink(public_path('assets/user/img/posts/' . $post->slider_post_image));

            // then, update data in db
            $post->update([
                'is_slider' => 0,
                'slider_post_image' => null
            ]);

            $request->session()->flash('success', 'Post removed from slider!');

            return response()->json(['data' => 'successful']);
        }
    }


    /**
     * update the Hero section status of specified resource
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return
    */


    public function updateHeroPost(Request $request){
        if ($request->is_hero_post == 1) {
            $HeroPostImgURL = $request->hasFile('hero_post_image') ? $request->hero_post_image : null;
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
            $sldPostImgExt = $request->hasFile('hero_post_image') ? $HeroPostImgURL->extension() : null;
            $rules = [
                'hero_post_image' => [
                    'required',
                    function ($attribute, $value, $fail) use ($allowedExtensions, $sldPostImgExt) {
                        if (!in_array($sldPostImgExt, $allowedExtensions)) {
                            $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                        }
                    }
                ],
                'image_size_type' => 'required',
            ];
            $message = [
                'hero_post_image.required' => 'The image field is required.'
            ];
            $validator = Validator::make($request->all(), $rules, $message);
            if ($validator->fails()) {
                return Response::json([
                    'errors' => $validator->getMessageBag()->toArray()
                ], 400);
            }

            // set a name for the image and store it to local storage
            $heroPostImgName = time() . '.' . $sldPostImgExt;
            $heroPostImgDir = public_path('./assets/user/img/posts/');

            @copy($HeroPostImgURL, $heroPostImgDir . $heroPostImgName);

            // update data in db
            $post = Post::findOrFail($request->id);
            $post->update([
                'is_hero_post' => 1,
                'hero_post_image' => $heroPostImgName,
                'image_size_type' =>$request->image_size_type
            ]);
            $request->session()->flash('success', 'Post added for Hero  Post!');
            return 'success';
        }else {
            $post = Post::findOrFail($request->id);
            // first, delete the image
            @unlink(public_path('assets/user/img/posts/' . $post->hero_post_image));
            // then, update data in db
            $post->update([
                'is_hero_post' => 0,
                'hero_post_image' => null,
                'image_size_type' =>null
            ]);

            $request->session()->flash('success', 'Post removed from hero Section!');

            return response()->json(['data' => 'successful']);
        }

    }

    /**
     * Update the featured-post status of specified resource.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return
     */
    public function updateFeaturedPost(Request $request)
    {
        if ($request->is_featured == 1) {
            $count = LimitCheckerHelper::currentFeaturedPostsCount(Auth::id());//count of current package
            $limit = LimitCheckerHelper::featurePostsLimit(Auth::id());//limit count of current package
            if($count >= $limit){
                Session::flash('warning', 'Feature Post Limit Exceeded');
                return "success";
            }

            $featPostImgURL = $request->hasFile('featured_post_image') ? $request->featured_post_image : null;
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');

            $featPostImgExt = $request->hasFile('featured_post_image') ? $featPostImgURL->extension() : null;

            $rules = [
                'featured_post_image' => [
                    'required',
                    function ($attribute, $value, $fail) use ($allowedExtensions, $featPostImgExt) {
                        if (!in_array($featPostImgExt, $allowedExtensions)) {
                            $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                        }
                    }
                ]
            ];

            $message = [
                'featured_post_image.required' => 'The image field is required.'
            ];

            $validator = Validator::make($request->all(), $rules, $message);

            if ($validator->fails()) {
                return Response::json([
                    'errors' => $validator->getMessageBag()->toArray()
                ], 400);
            }

            // set a name for the image and store it to local storage
            $featPostImgName = time() . '.' . $featPostImgExt;
            $featPostImgDir = public_path('./assets/user/img/posts/');

            @copy($featPostImgURL, $featPostImgDir . $featPostImgName);

            // update data in db
            $post = Post::findOrFail($request->id);

            $post->update([
                'is_featured' => 1,
                'featured_post_image' => $featPostImgName
            ]);

            $request->session()->flash('success', 'Post featured successfully!');

            return 'success';

        } else {
            $post = Post::findOrFail($request->id);

            // first, delete the image
            @unlink(public_path('assets/user/img/posts/' . $post->featured_post_image));

            // then, update data in db
            $post->update([
                'is_featured' => 0,
                'featured_post_image' => null
            ]);

            $request->session()->flash('success', 'Post unfeatured successfully!');

            return response()->json(['data' => 'successful'], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $information['post'] = Post::findOrFail($id);

        // get all the languages from db
        $information['languages'] = Language::where('user_id', Auth::id())->get();

        return view('user.post.edit-post', $information);
    }

    /**
     * Get all the slider images of specified resource.
     *
     * @param int $id
     * @return
     */
    public function slider(Request $request)
    {
        $filename = null;
        $request->validate([
            'file' => 'mimes:jpg,jpeg,png|required',
        ]);
        if ($request->hasFile('file')) {
            $filename = Uploader::upload_picture(public_path('assets/user/img/posts/slider-images'), $request->file('file'));
        }
        return response()->json(['status' => 'success', 'file_id' => $filename]);
    }

    public function sliderRemove(Request $request)
    {
        if (file_exists(public_path('./assets/user/img/posts/slider-images/' . $request->value))) {
            unlink(public_path('./assets/user/img/posts/slider-images/' . $request->value));
            return response()->json(['status' => 200, 'message' => 'success']);
        } else {
            return response()->json(['status' => 404, 'message' => 'error']);
        }
    }

    public function dbSliderRemove(Request $request)
    {
        $post = Post::findOrFail($request->id);
        $images = json_decode($post->slider_images);
        $imageName = $images[$request->key];
        array_splice($images, $request->key, 1);
        $post->slider_images = json_encode($images);
        $post->save();
        if (file_exists(public_path('./assets/user/img/posts/slider-images/' . $imageName))) {
            unlink(public_path('./assets/user/img/posts/slider-images/' . $imageName));
            return response()->json(['status' => 200, 'message' => 'success']);
        } else {
            return response()->json(['status' => 404, 'message' => 'error']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return
     */
    public function update(Request $request, $id)
    {
        $count = LimitCheckerHelper::currentPostsCount(Auth::id());//count of current posts
        $limit = LimitCheckerHelper::postsLimit(Auth::id());//limit count of current package

        if($count > $limit){
            Session::flash('warning', 'You have to delete ' . ($count - $limit) . ' posts to enable Editing Feature of Posts.');
            return "success";
        }

        $featuredCount = LimitCheckerHelper::currentFeaturedPostsCount(Auth::id());//count of current featured posts
        $featuredLimit = LimitCheckerHelper::featurePostsLimit(Auth::id());//limit count of current package
        if($featuredCount > $featuredLimit){
            Session::flash('warning', 'You have to unfeature ' . ($featuredCount - $featuredLimit) . ' posts to enable Editing Feature of Posts.');
            return "success";
        }

        $rules = ['serial_number' => 'required'];

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');

        if ($request->hasFile('thumbnail_image')) {
            $thumbnailImgURL = $request->thumbnail_image;
            $thumbnailImgExt = $thumbnailImgURL ? $thumbnailImgURL->extension() : null;
            $rules['thumbnail_image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $thumbnailImgExt) {
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

        $languages = Language::where('user_id', Auth::id())->get();

        $messages = [];

        foreach ($languages as $language) {
            $slug = make_slug($request[$language->code . '_title']);
            $rules[$language->code . '_title'] = [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($slug, $id, $language) {
                    $pcs = PostContent::where('post_id', '<>', $id)->where('user_id', Auth::id())->get();
                    foreach ($pcs as $key => $pc) {
                        if (strtolower($slug) == strtolower($pc->slug)) {
                            $fail('The title field must be unique for ' . $language->name . ' language.');
                        }
                    }
                }
            ];
            $rules[$language->code . '_category'] = 'required';
            $rules[$language->code . '_author'] = 'required';
            $rules[$language->code . '_content'] = 'required|min:15';

            $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

            $messages[$language->code . '_category.required'] = 'The category field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_author.required'] = 'The author field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_content.required'] = 'The content field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        $post = Post::findOrFail($id);

        if ($request->hasFile('thumbnail_image')) {
            $thumbnailImgURL = $request->thumbnail_image;
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/posts/' . $post->thumbnail_image));

            // second, set a name for the image and store it to local storage
            $thumbnailImgName = time() . '.' . $thumbnailImgExt;
            $thumbnailDir = public_path('./assets/user/img/posts/');

            @copy($thumbnailImgURL, $thumbnailDir . $thumbnailImgName);
        }
        $post->update([
            'thumbnail_image' => $request->hasFile('thumbnail_image') ? $thumbnailImgName : $post->thumbnail_image,
            'slider_images' => array_key_exists("image", $request->all()) && count($request->image) > 0 ? json_encode(array_merge(json_decode($post->slider_images), $request->image)) : $post->slider_images,
            'serial_number' => $request->serial_number
        ]);

        foreach ($languages as $language) {

            PostContent::updateOrCreate([
                'post_id' => $id,
                'language_id' => $language->id,
                'user_id' => Auth::id()
            ],[
                'post_category_id' => $request[$language->code . '_category'],
                'title' => $request[$language->code . '_title'],
                'slug' => make_slug($request[$language->code . '_title']),
                'author' => $request[$language->code . '_author'],
                'content' => Purifier::clean($request[$language->code . '_content']),
                'meta_keywords' => $request[$language->code . '_meta_keywords'],
                'meta_description' => $request[$language->code . '_meta_description']
            ]);
        }

        $request->session()->flash('success', 'Post updated successfully!');

        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // first, delete the thumbnail image
        @unlink(public_path('assets/user/img/posts/' . $post->thumbnail_image));

        // second, delete the slider images
        $postSldImgs = json_decode($post->slider_images, true);

        if (!empty($postSldImgs)) {
            foreach ($postSldImgs as $postSldImg) {
                @unlink(public_path('assets/user/img/posts/slider-images/' . $postSldImg));
            }
        }

        // third, delete the slider-post-image
        @unlink(public_path('assets/user/img/posts/' . $post->slider_post_image));

        // fourth, delete the featured-post-image
        @unlink(public_path('assets/user/img/posts/' . $post->featured_post_image));

        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully!');
    }

    /**
     * Remove the selected or all resources from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return String
     */
    public function bulkDestroy(Request $request): string
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $post = Post::findOrFail($id);

            // first, delete the thumbnail image
            @unlink(public_path('assets/user/img/posts/' . $post->thumbnail_image));

            // second, delete the slider images
            $postSldImgs = json_decode($post->slider_images);

            foreach ($postSldImgs as $postSldImg) {
                @unlink(public_path('assets/user/img/posts/slider-images/' . $postSldImg));
            }

            // third, delete the slider-post-image
            @unlink(public_path('assets/user/img/posts/' . $post->slider_post_image));

            // fourth, delete the featured-post-image
            @unlink(public_path('assets/user/img/posts/' . $post->featured_post_image));

            $post->delete();
        }

        $request->session()->flash('success', 'Posts deleted successfully!');

        return 'success';
    }
}

