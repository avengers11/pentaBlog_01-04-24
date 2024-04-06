<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\User\GalleryCategory;
use App\Models\User\GalleryItem;
use App\Models\User\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class GalleryItemController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)
                            ->where('user_id', Auth::id())
                            ->first();
        $information['language'] = $language;

        // then, get the gallery items of that language from db
        $information['items'] = GalleryItem::where('language_id', $language->id)
                                            ->where('user_id', Auth::id())
                                            ->orderBy('id', 'desc')
                                            ->get();

        // also, get all the languages from db
        $information['langs'] = Language::where('user_id', Auth::id())->get();

        return view('user.gallery.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'video_link' => 'required_if:item_type,video',
            'user_language_id' => 'required',
            'title' => 'required',
            'serial_number' => 'required'
        ];

        $imageURL = $request->image;

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
        $fileExtension = $imageURL ? $imageURL->extension() : null;

        $rules['image'] = [
            'required',
            function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                }
            }
        ];

        $messages = [
            'user_language_id.required' => 'The language field is required.',
            'video_link.required_if' => 'The video link field is required.',
            'title.required' => 'The title field is required.',
            'serial_number.required' => 'The serial number field is required.',
            'image.required' => 'The image field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        // set a name for the image and store it to local storage
        $imageName = time() . '.' . $fileExtension;
        $directory = public_path('./assets/user/img/gallery/');

        @mkdir($directory, 0775, true);

        @copy($imageURL, $directory . $imageName);

        // format video link
        if ($request->filled('video_link')) {
            $link = $request->video_link;

            if (strpos($link, '&') != 0) {
                $link = substr($link, 0, strpos($link, '&'));
            }
        }

        GalleryItem::create($request->except('item_type', 'image', 'video_link', 'user_id','language_id') + [
                'item_type' => $request->item_type == 'image' ? 'image' : 'video',
                'image' => $imageName,
                'video_link' => $request->filled('video_link') ? $link : null,
                'user_id' => Auth::id(),
                'language_id' => $request->user_language_id
            ]);

        $request->session()->flash('success', 'New gallery item added successfully!');

        return 'success';
    }

    public function updateFeatured(Request $request, $id)
    {
        $item = GalleryItem::findOrFail($id);

        if ($request->is_featured == 1) {
            $item->update(['is_featured' => 1]);

            $request->session()->flash('success', 'Gallery item featured successfully!');
        } else {
            $item->update(['is_featured' => 0]);

            $request->session()->flash('success', 'Gallery item unfeatured successfully!');
        }

        return redirect()->back();
    }

    public function getCategories($code)
    {
        if (!is_null($code)) {
            $language = Language::where('code', $code)->where('user_id', Auth::id())->first();

            $categories = GalleryCategory::where('language_id', $language->id)
                ->where('user_id', Auth::id())
                ->where('status', 1)
                ->orderByDesc('id')
                ->get();

            return response()->json(['successData' => $categories]);
        } else {
            return response()->json(['errorData' => 'Sorry, an error has occurred!'], 400);
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'video_link' => 'required_if:edit_item_type,video',
            'title' => 'required',
            'serial_number' => 'required'
        ];

        if ($request->hasFile('image')) {
            $imageURL = $request->image;

            $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
            $fileExtension = $imageURL ? $imageURL->extension() : null;

            $rules['image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                }
            };
        }

        $message = [
            'video_link.required_if' => 'The video link field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $item = GalleryItem::findOrFail($request->id);

        if ($request->hasFile('image')) {
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/gallery/') . $item->image);

            // second, set a name for the image and store it to local storage
            $imageName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/gallery/');

            @copy($imageURL, $directory . $imageName);
        }

        // format video link
        if ($request->has('video_link')) {
            $link = $request->video_link;

            if (strpos($link, '&') != 0) {
                $link = substr($link, 0, strpos($link, '&'));
            }
        }

        $item->update($request->except('edit_item_type', 'image', 'video_link', 'user_id') + [
                'item_type' => $request->edit_item_type == 'image' ? 'image' : 'video',
                'image' => $request->hasFile('image') ? $imageName : $item->image,
                'video_link' => $request->has('video_link') ? $link : $item->video_link,
                'user_id' => Auth::id()
            ]);

        $request->session()->flash('success', 'Gallery item updated successfully!');

        return 'success';
    }

    public function destroy($id)
    {
        $item = GalleryItem::findOrFail($id);
        @unlink(public_path('assets/user/img/gallery/' . $item->image));
        $item->delete();
        return redirect()->back()->with('success', 'Gallery item deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $item = GalleryItem::findOrFail($id);
            @unlink(public_path('assets/user/img/gallery/' . $item->image));
            $item->delete();
        }
        $request->session()->flash('success', 'Gallery items deleted successfully!');
        return 'success';
    }
}
