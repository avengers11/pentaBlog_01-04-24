<?php

namespace App\Http\Controllers\Pentaforce;

use App\Http\Controllers\Controller;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use App;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
use App\Models\User\Follower;
use App\Models\User\Language;
use App\Models\User\Post;
use Carbon\Carbon;
use Crypt;
use Session;
use App\Models\User\BasicSetting;
use App\Models\Customer;
use App\Models\User\Subscriber;

class DashboardApiController extends Controller
{

    // getDashboardData
    public function getDashboardData($crypt)
    {
        return Crypt::decrypt($crypt);
        $user = User::find(Crypt::decrypt($crypt));
        
        $data['user'] = $user;
        $langId = Language::where('user_id', $user->id)->where('is_default', 1)->firstOrFail()->id;

        $data['subscs'] = Subscriber::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $data['subscs_count'] = Subscriber::where('user_id', $user->id)->count();

        $data['posts'] = Post::join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $langId)
            ->where('post_contents.user_id', '=', $user->id)
            ->orderByDesc('posts.id')
            ->limit(10)
            ->get();

        $data['post_count'] = $user->posts->count();
        $data['featured_post_count'] = $user->posts->where('is_featured', 1)->count();
        $data['theme'] = BasicSetting::where('user_id', $user->id)
        ->select('theme_version')
        ->first();

        return $data;
    }

    public function getPaymentLogs()
    {
        $user = auth()->user();

        $memberships = Membership::where('user_id', $user->id)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get();

        // Assuming you want to return payment logs data in a structured format
        // You can format this data according to your needs

        return response()->json(['data' => $memberships]);
    }

    public function getLatestPosts()
    {
        $user = auth()->user();

        $langId = $user->languages()->where('is_default', 1)->firstOrFail()->id;

        $posts = Post::join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $langId)
            ->where('post_contents.user_id', '=', $user->id)
            ->orderByDesc('posts.id')
            ->limit(10)
            ->get();

        // Assuming you want to return latest posts data in a structured format
        // You can format this data according to your needs

        return response()->json(['data' => $posts]);
    }

    // processFile
    public function processFile($file, $file_path = 'attachments')
    {
        $name = time() . $file->getClientOriginalName();
        $file->move(public_path($file_path), $name);

        return $name;
    }

    // getUserData
    public function getUserData($crypt)
    {
        $data['user'] = User::find(Crypt::decrypt($crypt));
        $data['basic'] = BasicSetting::where('user_id', $data['user']->id)->first();

        return $data;
    }

    // Test
    public function Test($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        return $user;
    }
}
