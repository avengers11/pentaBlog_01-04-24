<?php

namespace App\Providers;

use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\CookieAlert;
use App\Models\User\FooterQuickLink;
use App\Models\User\FooterText;
use App\Models\User\HomeSection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\Social;
use App\Models\Language;
use App\Models\User\Language as UserLanguage;
use App\Models\Menu;
use App\Models\User\Menu as UserMenu;
use App\Models\User;
use App\Models\User\BasicSetting;
use App\Models\User\PostCategory;
use App\Models\User\SEO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        if (!(app()->runningInConsole())) {
            $socials = Social::orderBy('serial_number', 'ASC')->get();
            $langs = Language::all();

            View::composer('*', function ($view)
            {
                if (session()->has('lang')) {
                    $currentLang = Language::where('code', session()->get('lang'))->first();
                } else {
                    $currentLang = Language::where('is_default', 1)->first();
                }
                $bs = $currentLang->basic_setting;
                $be = $currentLang->basic_extended;

                if (Menu::where('language_id', $currentLang->id)->count() > 0) {
                    $menus = Menu::where('language_id', $currentLang->id)->first()->menus;
                } else {
                    $menus = json_encode([]);
                }

                if ($currentLang->rtl == 1) {
                    $rtl = 1;
                } else {
                    $rtl = 0;
                }

                $view->with('bs', $bs );
                $view->with('be', $be );
                $view->with('currentLang', $currentLang );
                $view->with('menus', $menus );
                $view->with('rtl', $rtl );
            });


            View::composer(['user.*'], function ($view)
            {
                if (Auth::guard('web')->check()) {
                    $userId = Auth::guard('web')->user()->id;
                    $userBs = DB::table('user_basic_settings')->where('user_id', Auth::user()->id)->first();
                    $view->with('userBs', $userBs );

                    if (request()->has('language')) {
                        $lang = UserLanguage::where([
                            ['code', request('language')],
                            ['user_id', $userId]
                        ])->first();
                        session()->put('currentLangCode', request('language'));
                    } else {
                        $lang = UserLanguage::where([
                            ['is_default', 1],
                            ['user_id', $userId]
                        ])->first();
                        session()->put('currentLangCode', $lang->code);
                    }
                    $keywords = json_decode($lang->keywords, true);

                    $view->with('keywords', $keywords);
                }
            });

            View::composer(['user-front.*'], function ($view)
            {
                $user = getUser();
                $theme = BasicSetting::where('user_id',$user->id)->select('theme_version')->first();

                if (session()->has('user_lang')) {
                    $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
                    if (empty($userCurrentLang)) {
                        $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                        session()->put('user_lang', $userCurrentLang->code);
                    }
                } else {
                    $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                }
                $keywords = json_decode($userCurrentLang->keywords, true);
                $userBs = BasicSetting::where('user_id', $user->id)->first();
                $user_details = User::where('id', $user->id)->first();
                $seo = $user_details->seos()->first() ?? null;
                $social_medias = $user_details->social_media()->get() ?? collect([]);

                $userLangs = UserLanguage::where('user_id', $user->id)->get();

                $packagePermissions = UserPermissionHelper::packagePermission($user->id);
                $packagePermissions = json_decode($packagePermissions, true);

                $footerData = FooterText::where('language_id', $userCurrentLang->id)
                                        ->where('user_id',$user->id)
                                        ->first();

                $footerQuickLinks = FooterQuickLink::where('language_id', $userCurrentLang->id)
                    ->where('user_id',$user->id)
                    ->orderBy('serial_number', 'asc')
                    ->get();

                $home_sections = HomeSection::where('user_id', $user->id)->first();

                $cookieAlert = CookieAlert::where('user_id', $user->id)
                    ->where('language_id', $userCurrentLang->id)
                    ->first();

                $recentPosts = [];
                if ($theme->theme_version == 4 || $theme->theme_version == 5 || $theme->theme_version == 6 ||$theme->theme_version == 7 ) {
                    $recentPosts = DB::table('posts')
                        ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
                        ->where('post_contents.language_id', '=', $userCurrentLang->id)
                        ->orderByDesc('posts.created_at')
                        ->limit(3)
                        ->get();
                }

                if (UserMenu::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)->count() > 0) {
                    $userMenus = UserMenu::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)->first()->menus;
                } else {
                    $userMenus = json_encode([]);
                }
                if($theme->theme_version == 1 || $theme->theme_version == 2 || $theme->theme_version == 5 || $theme->theme_version == 6 ||$theme->theme_version == 7){
                $postCategories= PostCategory::query()
                ->where('language_id', $userCurrentLang->id)
                ->where('status', 1)
                ->where('user_id', $user->id)
                ->orderBy('serial_number', 'ASC')
                ->get();
                }

                $websiteInfo = DB::table('user_basic_settings')
                    ->where('user_id', $user->id)
                    ->select('favicon', 'preloader', 'preloader_status', 'website_title', 'logo', 'support_email', 'support_contact', 'address', 'primary_color', 'whatsapp_status', 'whatsapp_number', 'whatsapp_header_title', 'whatsapp_popup_status', 'whatsapp_popup_message', 'breadcrumb_overlay_color', 'breadcrumb_overlay_opacity', 'theme_version', 'measurement_id', 'analytics_status')
                    ->first();
                $popups = User\Popup::where('user_id',$user->id)
                    ->where('language_id',$userCurrentLang->id)
                    ->where('status', 1)
                    ->orderBy('serial_number', 'ASC')
                    ->get();

                $view->with('themeInfo', $theme);
                if($theme->theme_version == 1 || $theme->theme_version == 2 || $theme->theme_version == 5 || $theme->theme_version == 6 ||$theme->theme_version == 7){
                   $view->with('postCategories', $postCategories);
                }
                $view->with('popupInfos', $popups);
                $view->with('user', $user);
                $view->with('hs', $home_sections);
                $view->with('seo', $seo );
                $view->with('userBs', $userBs);
                $view->with('userMenus', $userMenus);
                $view->with('quickLinkInfos', $footerQuickLinks);
                $view->with('footerInfo', $footerData);
                $view->with('socialLinkInfos', $social_medias);
                $view->with('currentLanguageInfo', $userCurrentLang);
                $view->with('allLanguageInfos', $userLangs );
                $view->with('keywords', $keywords);
                $view->with('packagePermissions', $packagePermissions);
                $view->with('cookieAlertInfo', $cookieAlert);
                $view->with('websiteInfo', $websiteInfo);
                if ($theme->theme_version == 4 || $theme->theme_version == 5 || $theme->theme_version == 6 ||$theme->theme_version == 7 ) {
                    $view->with('recentPostInfos', $recentPosts);
                }
            });

            View::share('langs', $langs);
            View::share('socials', $socials);
        }

    }
}
