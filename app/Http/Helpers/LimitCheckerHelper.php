<?php

namespace App\Http\Helpers;

use App\Models\Membership;
use App\Models\Package;
use App\Models\User\Language;
use App\Models\User\Post;
use App\Models\User\PostCategory;
use Carbon\Carbon;

class LimitCheckerHelper
{
    public static function postCategoriesLimit(int $user_id)
    {
        $id = Membership::query()->where([
            ['user_id', '=', $user_id],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->pluck('package_id')->first();
        $package = Package::query()->findOrFail($id);
        return $package->post_categories_limit;
    }

    public static function postsLimit(int $user_id)
    {
        $id = Membership::query()->where([
            ['user_id', '=', $user_id],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->pluck('package_id')->first();
        $package = Package::query()->findOrFail($id);
        return $package->posts_limit;
    }

    public static function featurePostsLimit(int $user_id)
    {
        $id = Membership::query()->where([
            ['user_id', '=', $user_id],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->pluck('package_id')->first();
        $package = Package::query()->findOrFail($id);
        return $package->feature_posts_limit;
    }

    public static function languagesLimit(int $user_id)
    {
        $id = Membership::query()->where([
            ['user_id', $user_id],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->pluck('package_id')->first();
        $package = Package::query()->findOrFail($id);
        return $package->language_limit;
    }

    public static function currentPostCategoryCount(int $user_id, $langId): int
    {
        return PostCategory::where('language_id', $langId)->where('user_id', $user_id)->count();
    }

    public static function currentPostsCount(int $user_id): int //not done yet
    {
        return Post::where('user_id', $user_id)->count();
    }

    public static function currentFeaturedPostsCount(int $user_id): int //not done yet
    {
        return Post::query()->where([
            ['user_id', '=', $user_id],
            ['is_featured', 1]
        ])->count();
    }

    public static function currentLanguageCount(int $user_id): int
    {
        return Language::query()->where([
            ['user_id', '=', $user_id],
        ])->count();
    }

    public static function vcardLimitchecker(int $user_id)
    {
        $id = Membership::query()->where([
            ['user_id', '=', $user_id],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->pluck('package_id')->first();
        $package = Package::query()->findOrFail($id);
        return $package->number_of_vcards;
    }
}
