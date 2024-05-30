<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
  public function index(Request $request)
  {
    $searchKey = null;

    if ($request->filled('info')) {
      $searchKey = $request['info'];
    }

    $users = Customer::when($searchKey, function ($query, $searchKey) {
      return $query->where('username', 'like', '%' . $searchKey . '%')
        ->orWhere('email', 'like', '%' . $searchKey . '%');
    })
    ->where('user_id', Auth::id())
    ->orderBy('id', 'desc')
    ->paginate(10);

    return view('user.registered-users.index', compact('users'));
  }

  public function customerSecretLogin(Request $request) {

    $customer = Customer::where('id', $request->user_id)->first();
    $user = $customer->user;

    if ($customer) {
        Auth::guard('customer')->login($customer, true);
        return redirect()->route('customer.dashboard',$user->username)
            ->withSuccess('You have Successfully loggedin');
    }
    Session::flash('warning','Opps You provide Invalid Credentials !');
    return back();
  }
  public function updateAccountStatus(Request $request, $id)
  {
    $user = Customer::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

    if ($request['account_status'] == 1) {
      $user->update(['status' => 1]);
    } else {
      $user->update(['status' => 0]);
    }

    $request->session()->flash('success', 'Account status updated successfully!');

    return redirect()->back();
  }

  public function show($id)
  {
    $userInfo = Customer::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    $information['userInfo'] = $userInfo;

    return view('user.registered-users.details', $information);
  }

  public function changePassword($id)
  {
    $userInfo = Customer::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

    return view('user.registered-users.change-password', compact('userInfo'));
  }

  public function updatePassword(Request $request, $id)
  {
    $rules = [
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $messages = [
      'new_password.confirmed' => 'Password confirmation does not match.',
      'new_password_confirmation.required' => 'The confirm new password field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $user = Customer::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

    $user->update([
      'password' => Hash::make($request->new_password)
    ]);

    $request->session()->flash('success', 'Password updated successfully!');

    return 'success';
  }

  public function destroy($id)
  {
    $user = Customer::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

    // delete attachments & invoices
    if ($user->bookmarkList()->count() > 0) {
      $user->bookmarkList()->delete();
    }

    // delete user image
    @unlink(public_path('assets/user/img/users/' . $user->image));

    $user->delete();

    return redirect()->back()->with('success', 'User info deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $user = Customer::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

      // delete attachments & invoices
      if ($user->bookmarkList()->count() > 0) {
        $user->bookmarkList()->delete();
      }

      // delete user image
      @unlink(public_path('assets/user/img/users/' . $user->image));

      $user->delete();
    }

    $request->session()->flash('success', 'Users info deleted successfully!');

    return 'success';
  }


  public function emailStatus(Request $request)
  {
      $user = Customer::where('id', $request->user_id)->where('user_id', Auth::id())->firstOrFail();
      $user->update([
          'email_verified_at' => $request->email_verified == 1 ? Carbon::now() : NULL,
      ]);

      $request->session()->flash('success', 'Email status updated for ' . $user->username);
      return back();
  }
}
