<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Page;
use App\Models\User\PageContent;
use App\Models\User\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class CustomPageController extends Controller
{

    public function index(Request $request)
    {
        $data['languages'] = Language::query()->where('user_id', Auth::id())->get();
        $languageId = Language::where('is_default', 1)->where('user_id', '=', Auth::id())->pluck('id')->first();
        $data['pages'] = DB::table('user_pages')
            ->join('user_page_contents', 'user_pages.id', '=', 'user_page_contents.page_id')
            ->where('user_page_contents.language_id', '=', $languageId)
            ->where('user_page_contents.user_id', '=', Auth::id())
            ->orderByDesc('user_pages.id')
            ->get();
        return view('user.custom-page.index', $data);
    }

    public function create()
    {
        // get all the languages from db
        $information['languages'] = Language::query()->where('user_id', Auth::id())->get();
        return view('user.custom-page.create', $information);
    }

    public function store(Request $request)
    {
        $rules = ['status' => 'required'];

        $languages = Language::query()->where('user_id', Auth::id())->get();

        $messages = [];

        foreach ($languages as $language) {

            $rules[$language->code . '_title'] = 'required|max:255';

            $rules[$language->code . '_content'] = 'required|min:15';

            $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

            $messages[$language->code . '_content.required'] = 'The content field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $page = new Page();

        $page->status = $request->status;
        $page->user_id = Auth::id();
        $page->save();

        foreach ($languages as $language) {
            $pageContent = new PageContent();
            $pageContent->language_id = $language->id;
            $pageContent->user_id = Auth::id();
            $pageContent->page_id = $page->id;
            $pageContent->title = $request[$language->code . '_title'];
            $pageContent->slug = make_slug($request[$language->code . '_title']);
            $pageContent->content = Purifier::clean($request[$language->code . '_content']);
            $pageContent->meta_keywords = $request[$language->code . '_meta_keywords'];
            $pageContent->meta_description = $request[$language->code . '_meta_description'];
            $pageContent->save();
        }

        $request->session()->flash('success', 'New page added successfully!');

        return 'success';
    }

    public function edit($id)
    {
        $information['page'] = Page::query()
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        // get all the languages from db
        $information['languages'] = Language::query()->where('user_id', Auth::id())->get();

        return view('user.custom-page.edit', $information);
    }

    public function update(Request $request, $id)
    {
        $rules = ['status' => 'required'];

        $languages = Language::query()->where('user_id', Auth::id())->get();

        $messages = [];

        foreach ($languages as $language) {

            $rules[$language->code . '_title'] = 'required|max:255';

            $rules[$language->code . '_content'] = 'required|min:15';

            $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

            $messages[$language->code . '_content.required'] = 'The content field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $page = Page::query()
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        $page->update(['status' => $request->status]);

        foreach ($languages as $language) {
            PageContent::updateOrCreate([
                'page_id' => $id,
                'user_id' => Auth::id(),
                'language_id' => $language->id
            ],[
                'title' => $request[$language->code . '_title'],
                'slug' => make_slug($request[$language->code . '_title']),
                'content' => Purifier::clean($request[$language->code . '_content']),
                'user_id' => Auth::id(),
                'language_id' => $language->id,
                'meta_keywords' => $request[$language->code . '_meta_keywords'],
                'meta_description' => $request[$language->code . '_meta_description']
            ]);
        }

        $request->session()->flash('success', 'Page updated successfully!');
        return 'success';
    }

    public function destroy($id)
    {
        Page::query()->where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Page deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            Page::query()->where('id', $id)->where('user_id', Auth::id())->delete();
        }
        $request->session()->flash('success', 'Pages deleted successfully!');
        return 'success';
    }
}
