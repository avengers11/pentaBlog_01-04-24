<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Testimonial;
use App\Models\User\TestimonialContent;
use App\Models\User\Language;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        $languageId = Language::where('is_default', 1)->where('user_id', Auth::id())->pluck('id')->first();

        $information['testimonialContents'] = TestimonialContent::with('testimonial')
            ->where('language_id', '=', $languageId)
            ->orderBy('testimonial_id', 'desc')
            ->get();

        return view('user.about-me.testimonials.index', $information);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return
     */
    public function create()
    {
        // get all the languages from db
        $information['languages'] = Language::where('user_id', Auth::id())->get();

        return view('user.about-me.testimonials.create', $information);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param
     * @return
     */
    public function store(Request $request)
    {
        $clientImageURL = $request->client_image;

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
        $fileExtension = $clientImageURL ? $clientImageURL->extension() : null;

        $rules = [
            'client_image' => [
                'required',
                function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                    }
                }
            ],
            'serial_number' => 'required',
            'rating' => 'required'
        ];

        $languages = Language::where('user_id', Auth::id())->get();

        $messages = [];

        foreach ($languages as $language) {
            $rules[$language->code . '_client_name'] = 'required';

            $rules[$language->code . '_comment'] = 'required';

            $messages[$language->code . '_client_name.required'] = 'The client name field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_comment.required'] = 'The client comment field is required for ' . $language->name . ' language.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $testimonial = new Testimonial();

        // set a name for the image and store it to local storage
        $clientImageName = time() . '.' . $fileExtension;
        $directory = public_path('./assets/user/img/testimonials/');

        @mkdir($directory, 0775, true);

        @copy($clientImageURL, $directory . $clientImageName);

        $testimonial->client_image = $clientImageName;
        $testimonial->serial_number = $request->serial_number;
        $testimonial->rating = $request->rating;
        $testimonial->user_id = Auth::id();
        $testimonial->save();

        foreach ($languages as $language) {
            $testimonialContent = new TestimonialContent();
            $testimonialContent->language_id = $language->id;
            $testimonialContent->testimonial_id = $testimonial->id;
            $testimonialContent->client_name = $request[$language->code . '_client_name'];
            $testimonialContent->comment = $request[$language->code . '_comment'];
            $testimonialContent->user_id = Auth::id();
            $testimonialContent->save();
        }

        $request->session()->flash('success', 'New testimonial added successfully!');

        return 'success';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return
     */
    public function edit($id)
    {
        $information['testimonial'] = Testimonial::findOrFail($id);
        // get all the languages from db
        $information['languages'] = Language::where('user_id', Auth::id())->get();

        return view('user.about-me.testimonials.edit', $information);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return
     */
    public function update(Request $request, $id)
    {
        $rules = [];

        if ($request->hasFile('client_image')) {
            $clientImageURL = $request->client_image;

            $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
            $fileExtension = $clientImageURL ? $clientImageURL->extension() : null;

            $rules['client_image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
                }
            };
        }

        $rules['serial_number'] = 'required';
        $rules['rating'] = 'required';

        $languages = Language::where('user_id', Auth::id())->get();

        $messages = [];

        foreach ($languages as $language) {
            $rules[$language->code . '_client_name'] = 'required';

            $rules[$language->code . '_comment'] = 'required';

            $messages[$language->code . '_client_name.required'] = 'The client name field is required for ' . $language->name . ' language.';

            $messages[$language->code . '_comment.required'] = 'The client comment field is required for ' . $language->name . ' language.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $testimonial = Testimonial::findOrFail($id);

        if ($request->hasFile('client_image')) {
            // first, delete the previous image from local storage
            @unlink(public_path('assets/user/img/testimonials/') . $testimonial->client_image);

            // second, set a name for the image and store it to local storage
            $clientImageName = time() . '.' . $fileExtension;
            $directory = public_path('./assets/user/img/testimonials/');

            @copy($clientImageURL, $directory . $clientImageName);
        }

        $testimonial->update([
            'client_image' => $request->hasFile('client_image') ? $clientImageName : $testimonial->client_image,
            'serial_number' => $request->serial_number,
            'rating' => $request->rating
        ]);

        foreach ($languages as $language) {
            $testimonialContent = TestimonialContent::where('language_id', $language->id)
                ->where('user_id', Auth::id())
                ->where('testimonial_id', $id)
                ->first();

            $testimonialContent->update([
                'client_name' => $request[$language->code . '_client_name'],
                'comment' => $request[$language->code . '_comment']
            ]);
        }

        $request->session()->flash('success', 'Testimonial updated successfully!');

        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return
     */
    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        @unlink(public_path('assets/user/img/testimonials/') . $testimonial->client_image);

        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimonial deleted successfully!');
    }
}
