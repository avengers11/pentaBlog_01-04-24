<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Http\Controllers\Controller;
use App\Models\User\ItemSubCategory;
use App\Models\User\UserItemCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User\UserItemSubCategory;
use Illuminate\Support\Facades\Validator;

class ItemSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $lang_id = $lang->id;
        $data['categories'] = UserItemCategory::where('language_id', $lang_id)->where('user_id', Auth::guard('web')->user()->id)->orderBy('name', 'ASC')->get();
        $data['itemsubcategories'] = UserItemSubCategory::where('language_id', $lang_id)->where('user_id', Auth::guard('web')->user()->id)
            ->with('category')
            ->orderBy('id', 'DESC')->paginate(10);
        $data['lang_id'] = $lang_id;
        return view('user.item.subcategory.index', $data);
    }
    public function store(Request $request)
    {
        $messages = [
            'user_language_id.required' => 'The language field is required',
            'category_id.required' => 'The category field is required'
        ];
        $rules = [
            'user_language_id' => 'required',
            'name' => 'required|max:255',
            'category_id' => 'required',
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $data = new UserItemSubCategory;
        $input = $request->all();
        $input['slug'] =  make_slug($request->name);
        $input['user_id'] =  Auth::guard('web')->user()->id;
        $input['language_id'] =  $request->user_language_id;
        $data->create($input);
        Session::flash('success', 'Sub Category added successfully!');
        return "success";
    }


    public function edit($id)
    {
        $data['data'] = UserItemSubCategory::findOrFail($id);
        $lang = Language::where('code', request('language'))->where('user_id', Auth::guard('web')->user()->id)->first();
        $lang_id = $lang->id;
        $data['categories'] = UserItemCategory::where('language_id', $lang_id)->where('user_id', Auth::guard('web')->user()->id)->orderBy('name', 'ASC')->get();

        return view('user.item.subcategory.edit', $data);
    }

    public function update(Request $request)
    {
        $messages = [
            'category_id.required' => 'The category field is required'
        ];
        $rules = [
            'name' => 'required|max:255',
            'status' => 'required',
            'category_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $data = UserItemSubCategory::findOrFail($request->subcategory_id);
        $input = $request->all();
        $input['slug'] =  make_slug($request->name);

        $data->update($input);

        Session::flash('success', 'Sub Category Update successfully!');
        return "success";
    }

    public function delete(Request $request)
    {
        $category = UserItemSubCategory::findOrFail($request->subcategory_id);
        if ($category->items()->count() > 0) {
            Session::flash('warning', 'First, delete all the item under the selected categories!');
            return back();
        }
        $category->delete();

        Session::flash('success', 'Sub Category deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {


        $ids = $request->ids;

        foreach ($ids as $id) {
            $pcategory = UserItemSubCategory::findOrFail($id);
            if ($pcategory->items()->count() > 0) {
                Session::flash('warning', 'First, delete all the item under the selected sub categories!');
                return "success";
            }
        }
        foreach ($ids as $id) {
            $ItemCategory = UserItemSubCategory::findOrFail($id);
            $ItemCategory->delete();
        }

        Session::flash('success', 'Item sub categories deleted successfully!');
        return "success";
    }
}
