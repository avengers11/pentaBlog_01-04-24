<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\User\UserItem;
use App\Models\User\ItemReview;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('setlang');
    }

    public function reviewsubmit(Request $request)
    {


        Validator::make($request->all(), [
            'review' => 'required',
            'comment' => 'required'
        ])->validate();

        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        // // Retrieve the validated input...
        // $validated = $validator->validated();

        // // Retrieve a portion of the validated input...
        // $validated = $validator->safe()->only(['review', 'comment']);
        // $validated = $validator->safe()->except(['review', 'comment']);

        // $rules = [
        //     'review' => 'required',
        //     'comment' => 'required'
        // ];
        // $messages = [
        //     'comment.required' => 'Please say something about this item',
        //     'review.required' => 'you must select a star\'s segment',
        // ];

        // $validator = Validator::make($request->all(), $rules, $messages);
        // if ($validator->fails()) {
        //     $errmsgs = $validator->getMessageBag()->add('error', 'true');
        //     return response()->json($validator->errors());
        // }


        if ($request->review && $request->comment) {
            if (ItemReview::where('customer_id', Auth::guard('customer')->user()->id)->where('item_id', $request->item_id)->exists()) {
                $exists =    ItemReview::where('customer_id', Auth::guard('customer')->user()->id)->where('item_id', $request->item_id)->first();
                if ($request->review) {
                    $exists->update([
                        'review' => $request->review,
                    ]);
                    $avgreview = ItemReview::where('item_id', $request->item_id)->avg('review');
                    UserItem::find($request->item_id)->update([
                        'rating' => $avgreview
                    ]);
                }
                if ($request->comment) {
                    $exists->update([
                        'comment' => $request->comment,
                    ]);
                }
                Session::flash('success', 'Review update successfully');
                return back();
            } else {
                $input = $request->all();
                $input['customer_id'] = Auth::guard('customer')->user()->id;
                $data = new ItemReview();
                $data->create($input);
                $avgreview = ItemReview::where('item_id', $request->item_id)->avg('review');
                UserItem::find($request->item_id)->update([
                    'rating' => $avgreview
                ]);
                Session::flash('success', 'Review submit successfully');
                return back();
            }
        } else {
            Session::flash('error', 'Review submit not succesfull');
            return back();
        }
    }

    public function authcheck()
    {
        if (!Auth::guard('customer')->user()) {
            Session::put('link', url()->current());
            return redirect(route('customer.login', getParam()));
        }
    }
}
