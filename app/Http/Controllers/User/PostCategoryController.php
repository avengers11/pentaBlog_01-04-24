<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\LimitCheckerHelper;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\BasicSetting;
use App\Models\User\PostCategory;
use App\Models\User\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PostCategoryController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', Auth::id())->first();
        $information['language'] = $language;

        $information['count'] = LimitCheckerHelper::currentPostCategoryCount(Auth::id(), $language->id);//category added count of selected language
        $information['category_limit'] = LimitCheckerHelper::postCategoriesLimit(Auth::id());//category limit count of current package

        $information['categoryCount'] = PostCategory::where('user_id', Auth::id())->count();
        $information['categoryLimit'] = UserPermissionHelper::currentPackagePermission(Auth::id())->post_categories_limit;

        // then, get the post categories of that language from db
        $information['categories'] = PostCategory::where('language_id', $language->id)
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        // also, get all the languages from db
        $information['langs'] = Language::where('user_id', Auth::id())->get();

        return view('user.post.categories', $information);
    }

    public function store(Request $request)
    {
        $count = LimitCheckerHelper::currentPostCategoryCount(Auth::id(), $request->user_language_id);//category added count of selected language
        $category_limit = LimitCheckerHelper::postCategoriesLimit(Auth::id());//category limit count of current package

        if($count >= $category_limit){
            Session::flash('warning', 'Post Category Limit Exceeded');
            return "success";
        }

        $theme = BasicSetting::where('user_id', Auth::id())->first()->theme_version;

        $rules = [
            'user_language_id' => 'required',
            'name' => 'required',
            'status' => 'required',
            'serial_number' => 'required'
        ];

        if ($theme == 1 || $theme == 6 || $theme == 7) {
            $imgURL = $request->image;
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
            $imgExt = $imgURL ? $imgURL->extension() : null;

            $rules['image'] = [
                'required',
                function ($attribute, $value, $fail) use ($allowedExtensions, $imgExt) {
                    if (!in_array($imgExt, $allowedExtensions)) {
                        $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                    }
                }
            ];
        }


        $message = [
            'user_language_id.required' => 'The language field is required.',
            'name.required' => 'The category name is required.',
            'status.required' => 'The status field is required.',
            'serial_number.required' => 'The serial number field is required.'
        ];

        if ($theme == 1 || $theme == 6 || $theme == 7) {
            $message['image.required'] = ['The image field is required.'];
        }

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        if ($theme == 1 || $theme == 6 || $theme == 7) {
            // set a name for the image and store it to local storage
            $imgName = time() . '.' . $imgExt;
            $imgDir = public_path('./assets/user/img/post-categories/');

            @mkdir($imgDir, 0775, true);

            @copy($imgURL, $imgDir . $imgName);
        }

        PostCategory::create($request->except('image','language_id','user_id') + [
                'language_id' => $request->user_language_id,
                'image' => $theme == 1 || $theme == 6 || $theme == 7 ? $imgName : NULL,
                'user_id' => Auth::id()
            ]);

        $request->session()->flash('success', 'New post category added successfully!');

        return 'success';
    }

    public function updateFeatured(Request $request, $id)
    {
        $category = PostCategory::findOrFail($id);
        if ($request->is_featured == 1) {
            $category->update(['is_featured' => 1]);
            $request->session()->flash('success', 'Category featured successfully!');
        } else {
            $category->update(['is_featured' => 0]);
            $request->session()->flash('success', 'Category unfeatured successfully!');
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $category = PostCategory::findOrFail($request->id);
        $count = LimitCheckerHelper::currentPostCategoryCount(Auth::id(), $category->language_id);//category added count of selected language
        $category_limit = LimitCheckerHelper::postCategoriesLimit(Auth::id());//category limit count of current package

        if($count > $category_limit){
            Session::flash('warning', 'You have to delete ' . ($count - $category_limit) . ' post categories to enable Editing Feature of Post Categories.');
            return "success";
        }
        $theme = BasicSetting::where('user_id', Auth::id())->first()->theme_version;

        $rules = [
            'name' => 'required',
            'status' => 'required',
            'serial_number' => 'required'
        ];

        if ($theme == 1 || $theme == 6 || $theme == 7) {
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');

            if ($request->has('image')) {
                $imgURL = $request->image;
                $imgExt = $imgURL->extension();

                $rules['image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $imgExt) {
                    if (!in_array($imgExt, $allowedExtensions)) {
                        $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                    }
                };
            }
        }

        $message = [
            'name.required' => 'The category name is required.',
            'status.required' => 'The status field is required.',
            'serial_number.required' => 'The serial number field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        if ($theme == 1 || $theme == 6 || $theme == 7 && $request->has('image')) {
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/post-categories/' . $category->image));

            // second, set a name for the image and store it to local storage
            $imgName = time() . '.' . $imgExt;
            $imgDir = public_path('./assets/user/img/post-categories/');
            @copy($imgURL, $imgDir . $imgName);
        }

        $category->update($request->except('image') + [
                'image' => $theme == 1 || $theme == 6 || $theme == 7 && $request->has('image') ? $imgName : $category->image
            ]);
        $request->session()->flash('success', 'Post category updated successfully!');

        return 'success';
    }

    public function destroy($id)
    {
        $category = PostCategory::findOrFail($id);
        if ($category->postContentList()->count() > 0) {
            return redirect()->back()->with('warning', 'First delete all the posts of this category!');
        } else {
            // first, delete the image
            @unlink(public_path('assets/user/img/post-categories/' . $category->image));
            $category->delete();
            return redirect()->back()->with('success', 'Post category deleted successfully!');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $category = PostCategory::findOrFail($id);

            if ($category->postContentList()->count() > 0) {
                $request->session()->flash('warning', 'First delete all the posts of those categories!');
                return 'success';
            } else {
                // first, delete the image
                @unlink(public_path('assets/user/img/post-categories/' . $category->image));
                $category->delete();
            }
        }

        $request->session()->flash('success', 'Post categories deleted successfully!');

        return 'success';
    }
}
