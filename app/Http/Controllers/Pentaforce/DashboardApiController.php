<?php

namespace App\Http\Controllers\Pentaforce;

use App;
use Auth;
use Crypt;
use Session;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Package;
use App\Models\Customer;
use App\Models\User\Post;
use App\Models\Membership;
use App\Models\User\Follower;
use App\Models\User\Language;
use App\Models\User\Subscriber;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User\UserCustomDomain;

class DashboardApiController extends Controller
{

    // getDashboardData
    public function getDashboardData($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));

        $data['rcDomain'] = UserCustomDomain::where('status', '<>', 2)->where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        $data['user'] = $user;
        $langId = Language::where('user_id', $user->id)->where('is_default', 1)->firstOrFail()->id;
        $data['subscs'] = Subscriber::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $data['subscs_count'] = Subscriber::where('user_id', $user->id)->count();

        $data['posts'] = Post::join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('posts.is_featured', '!=', 10)
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

        $data['language_keywords'] = defaultLanguage($user->id);

        return response()->json($data);
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
            ->where('posts.is_featured', '!=', 10)
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
        $data['lang'] = Language::where('is_default', 1)->where('user_id', $data['user']->id)->first();

        return $data;
    }

    // Test
    public function Test($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        return $user;
    }
}
