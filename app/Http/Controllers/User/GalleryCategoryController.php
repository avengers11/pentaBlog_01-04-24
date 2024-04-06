<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;

use App\Models\User\GalleryCategory;
use App\Models\User\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class GalleryCategoryController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)
            ->where('user_id', Auth::id())
            ->first();

        $information['language'] = $language;

        // then, get the gallery categories of that language from db
        $information['categories'] = GalleryCategory::where('language_id', $language->id)
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        // also, get all the languages from db
        $information['langs'] = Language::where('user_id', Auth::id())->get();

        return view('user.gallery.categories', $information);
    }

    public function store(Request $request)
    {
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
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $request['user_id'] = Auth::id();
        $request['language_id'] = $request->user_language_id;

        GalleryCategory::create($request->all());

        $request->session()->flash('success', 'New gallery category added successfully!');

        return 'success';
    }

    public function update(Request $request)
    {
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
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        GalleryCategory::findOrFail($request->id)->update($request->all());

        $request->session()->flash('success', 'Gallery category updated successfully!');

        return 'success';
    }

    public function destroy($id)
    {
        $category = GalleryCategory::findOrFail($id);

        if ($category->imgVid()->count() > 0) {
            return redirect()->back()->with('warning', 'First delete all the items of this category!');
        } else {
            $category->delete();

            return redirect()->back()->with('success', 'Gallery category deleted successfully!');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $category = GalleryCategory::findOrFail($id);

            if ($category->imgVid()->count() > 0) {
                $request->session()->flash('warning', 'First delete all the items of those categories!');

                return 'success';
            } else {
                $category->delete();
            }
        }

        $request->session()->flash('success', 'Gallery categories deleted successfully!');

        return 'success';
    }

    public function getCategories($id)
    {
        if (!is_null($id)) {
            $categories = GalleryCategory::where('language_id', $id)
                ->where('user_id', Auth::id())
                ->where('status', 1)
                ->orderByDesc('id')
                ->get();

            return response()->json(['successData' => $categories]);
        } else {
            return response()->json(['errorData' => 'Sorry, an error has occurred!'], 400);
        }
    }
}

