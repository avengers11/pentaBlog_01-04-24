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

class DashboardApiController extends Controller
{

    // getDashboardData
    public function getDashboardData($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        $data['user'] = $user;
        $langId = Language::where('user_id', $user->id)->where('is_default', 1)->firstOrFail()->id;
        $data['followers'] = Follower::where('following_id', $user->id)->count();
        $data['followings'] = Follower::where('follower_id', $user->id)->count();

        $data['memberships'] = Membership::query()->where('user_id', $user->id)
            ->orderBy('id', 'DESC')
            ->limit(10)->get();


        $data['posts'] = Post::join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $langId)
            ->where('post_contents.user_id', '=', $user->id)
            ->orderByDesc('posts.id')
            ->limit(10)
            ->get();

        $nextPackageCount = Membership::query()->where([
            ['user_id', $user->id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', '<>', 2)->count();
        //current package
        $data['current_membership'] = Membership::query()->where([
            ['user_id', $user->id],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->first();
        if ($data['current_membership']) {
            $countCurrMem = Membership::query()->where([
                ['user_id', $user->id],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])->count();
            if ($countCurrMem > 1) {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', $user->id],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->orderBy('id', 'DESC')->first();
            } else {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', $user->id],
                    ['start_date', '>', $data['current_membership']->expire_date],
                ])->where('status', '<>', 2)->first();
            }
            $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
        }
        $data['current_package'] = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
        $data['package_count'] = $nextPackageCount;
        $data['post_count'] = $user->posts->count();
        $data['featured_post_count'] = $user->posts->where('is_featured', 1)->count();
        $data['post_category_count'] = $user->postCategory->where('language_id', $langId)->count();
        $data['featured_post_category_count'] = $user->postCategory->where('language_id', $langId)->where('is_featured', 1)->count();
        $data['gallery_item_count'] = $user->galleryItem->where('language_id', $langId)->count();
        $data['featured_gallery_item_count'] = $user->galleryItem->where('language_id', $langId)->where('is_featured', 1)->count();
        $data['gallery_category_count'] = $user->galleryCategory->where('language_id', $langId)->count();
        $data['faq_count'] = $user->faq->where('language_id', $langId)->count();
        $data['language_count'] = $user->languages->count();
        $data['advertisement_count'] = $user->advertisements->count();
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

    // Test
    public function Test($crypt)
    {
        $user = User::find(Crypt::decrypt($crypt));
        return $user;
    }
}
