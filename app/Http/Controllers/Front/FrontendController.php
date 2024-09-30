<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\OfflineGateway;
use App\Models\Package;
use App\Models\Partner;
use App\Models\PaymentGateway;
use App\Models\User;
use App\Models\User\BookmarkPost;
use App\Models\User\GalleryCategory;
use App\Models\User\GalleryItem;
use App\Models\User\Information;
use App\Models\User\PostCategory;
use App\Models\User\SliderImage;
use App\Models\User\UserCustomDomain;
use App\Models\User\UserVcard;
use DB;
use Illuminate\Http\Request;
use App\Models\Process;
use App\Models\Feature;
use App\Models\Language;
use App\Models\Subscriber;
use App\Models\User\Language as UserLanguage;
use App\Models\Testimonial;
use App\Models\BasicSetting as BS;
use App\Models\Bcategory;
use App\Models\Blog;
use App\Models\BasicExtended as BE;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Seo;
use App\Models\User\Advertisement;
use App\Models\User\BasicSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Config;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Validator;
use JeroenDesloovere\VCard\VCard;

class FrontendController extends Controller
{
    public function __construct()
    {
        $bs = BS::first();
        $be = BE::first();

        Config::set('captcha.sitekey', $bs->google_recaptcha_site_key);
        Config::set('captcha.secret', $bs->google_recaptcha_secret_key);
        Config::set('mail.host', $be->smtp_host);
        Config::set('mail.port', $be->smtp_port);
        Config::set('mail.username', $be->smtp_username);
        Config::set('mail.password', $be->smtp_password);
        Config::set('mail.encryption', $be->encryption);
    }

    public function index()
    {

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $lang_id = $currentLang->id;
        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;

        $data['processes'] = Process::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['features'] = Feature::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['featured_users'] = User::where([
            ['featured', 1],
            ['status', 1],
            ['online_status', 1]
        ])
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->get();


        $data['templates'] = User::where([
            ['preview_template', 1],
            ['status', 1],
            ['online_status', 1]
        ])
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->orderBy('template_serial_number', 'ASC')->get();



        $data['testimonials'] = Testimonial::where('language_id', $lang_id)
            ->orderBy('serial_number', 'ASC')
            ->get();
        $data['blogs'] = Blog::where('language_id', $lang_id)->orderBy('id', 'DESC')->take(2)->get();

        $data['packages'] = Package::query()->where('status', '1')->where('featured', '1')->get();
        $data['partners'] = Partner::where('language_id', $lang_id)
            ->orderBy('serial_number', 'ASC')
            ->get();

        $data['seo'] = Seo::where('language_id', $lang_id)->first();

        $terms = [];
        if (Package::query()->where('status', '1')->where('featured', '1')->where('term', 'monthly')->count() > 0) {
            $terms[] = 'Monthly';
        }
        if (Package::query()->where('status', '1')->where('featured', '1')->where('term', 'yearly')->count() > 0) {
            $terms[] = 'Yearly';
        }
        if (Package::query()->where('status', '1')->where('featured', '1')->where('term', 'lifetime')->count() > 0) {
            $terms[] = 'Lifetime';
        }
        $data['terms'] = $terms;

        $be = BE::select('package_features')->first();
        $allPfeatures = $be->package_features ? $be->package_features : "[]";
        $data['allPfeatures'] = json_decode($allPfeatures, true);

        return view('front.index', $data);
    }

    public function subscribe(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:subscribers'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $subscriber = new Subscriber;
        $subscriber->email = $request->email;
        $subscriber->save();

        return "success";
    }

    public function loginView()
    {
        return view('front.login');
    }

    public function step1($status, $id)
    {
        if (Auth::check()) {
            return redirect()->route('user.plan.extend.index');
        }
        $data['status'] = $status;
        $data['id'] = $id;
        $data['package'] = Package::findOrFail($id);
        return view('front.step', $data);
    }

    public function step2(Request $request)
    {
        $data = $request->session()->get('data');
        return view('front.checkout', $data);
    }

    public function checkout(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|alpha_num|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $seo = Seo::where('language_id', $currentLang->id)->first();
        $be = $currentLang->basic_extended;
        $data['bex'] = $be;
        $data['username'] = $request->username;
        $data['email'] = $request->email;
        $data['password'] = $request->password;
        $data['status'] = $request->status;
        $data['id'] = $request->id;
        $online = PaymentGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->get();
        $data['offline'] = $offline;
        $data['payment_methods'] = $online->merge($offline);
        $data['package'] = Package::query()->findOrFail($request->id);
        $data['seo'] = $seo;
        $request->session()->put('data', $data);
        return redirect()->route('front.registration.step2');
    }

    // packages start
    public function pricing(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();

        $data['bex'] = BE::first();
        $data['abs'] = BS::first();

        $terms = [];
        if (
            Package::query()
            ->where('status', '1')
            ->where('term', 'monthly')
            ->count() > 0
        ) {
            $terms[] = 'Monthly';
        }
        if (
            Package::query()
            ->where('status', '1')
            ->where('term', 'yearly')
            ->count() > 0
        ) {
            $terms[] = 'Yearly';
        }
        if (
            Package::query()
            ->where('status', '1')
            ->where('term', 'lifetime')
            ->count() > 0
        ) {
            $terms[] = 'Lifetime';
        }
        $data['terms'] = $terms;

        $be = BE::select('package_features')->firstOrFail();
        $allPfeatures = $be->package_features ? $be->package_features : "[]";
        $data['allPfeatures'] = json_decode($allPfeatures, true);

        return view('front.pricing', $data);
    }


    // blog section start
    public function blogs(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();

        $data['currentLang'] = $currentLang;

        $lang_id = $currentLang->id;

        $category = $request->category;
        if (!empty($category)) {
            $data['category'] = Bcategory::findOrFail($category);
        }
        $term = $request->term;

        $data['bcats'] = Bcategory::where('language_id', $lang_id)->where('status', 1)->orderBy('serial_number', 'ASC')->get();


        $data['blogs'] = Blog::when($category, function ($query, $category) {
            return $query->where('bcategory_id', $category);
        })
            ->when($term, function ($query, $term) {
                return $query->where('title', 'like', '%' . $term . '%');
            })
            ->when($currentLang, function ($query, $currentLang) {
                return $query->where('language_id', $currentLang->id);
            })->orderBy('serial_number', 'ASC')->paginate(3);
        return view('front.blogs', $data);
    }

    public function blogdetails($slug, $id)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $lang_id = $currentLang->id;

        $data['blog'] = Blog::findOrFail($id);
        $data['bcats'] = Bcategory::where('status', 1)->where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        return view('front.blog-details', $data);
    }

    public function contactView()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();

        return view('front.contact', $data);
    }

    public function faqs()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();

        $lang_id = $currentLang->id;
        $data['faqs'] = Faq::where('language_id', $lang_id)
            ->orderBy('serial_number', 'DESC')
            ->take(4)
            ->get();
        return view('front.faq', $data);
    }

    public function dynamicPage($slug)
    {
        $data['page'] = Page::where('slug', $slug)->firstOrFail();

        return view('front.dynamic', $data);
    }

    public function users(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();


        $userIds = [];

        $data['users'] = null;
        $users = User::where('online_status', 1)->where('listing_page', 1)
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })
            ->when($request->search, function ($q) use ($request) {
                return $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%');
            })
            ->when($request->designation, function ($q) use ($request) {
                return $q->where('designation', 'like', '%' . $request->designation . '%');
            })
            ->when($request->keywords, function ($q) use ($request) {
                return $q->where('keywords', 'like', '%' . $request->keywords . '%');
            })
            ->when($userIds, function ($q) use ($userIds) {
                return $q->whereIn('id', $userIds);
            })
            ->orderBy('id', 'DESC')
            ->paginate(9);

        $data['users'] = $users;
        return view('front.users', $data);
    }

    public function userDetailView($domain)
    {
        $user = getUser();
        $data['user'] = $user;

        if (Auth::check() && Auth::user()->id != $user->id && $user->online_status != 1) {
            return redirect()->route('front.index');
        } elseif (!Auth::check() && $user->online_status != 1) {
            return redirect()->route('front.index');
        }

        $package = UserPermissionHelper::userPackage($user->id);
        if (is_null($package)) {
            Session::flash('warning', 'User membership is expired');
            if (Auth::check()) {
                return redirect()->route('user-dashboard')->with('error', 'User membership is expired');
            } else {
                return redirect()->route('front.user.view');
            }
        }

        if (session()->has('user_lang')) {
            $language = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($language)) {
                $language = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $language->code);
            }
        } else {
            $language = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        $data = User\BasicSetting::query()
            ->where('user_id', $user->id)
            ->select('theme_version', 'gallery_bg')
            ->first();

        $queryResult['sliderPosts'] = DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $language->id)
            ->where('is_slider', '=', 1)
            ->where('posts.user_id', '=', $user->id)
            ->where('posts.is_featured', '!=', 10)
            ->orderBy('posts.serial_number', 'ASC')
            ->get();

        if($data->theme_version ==7){
            $queryResult['heroPostsType_1'] = DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $language->id)
            ->where('is_hero_post', '=', 1)
            ->where('image_size_type','=','side_post')
            ->where('posts.is_featured', '!=', 10)
            ->where('posts.user_id', '=', $user->id)
            ->orderBy('posts.serial_number', 'ASC')
            ->get();
            $queryResult['heroPostsType_2'] = DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $language->id)
            ->where('is_hero_post', '=', 1)
            ->where('image_size_type', '=', 'middle_post')
            ->where('posts.user_id', '=', $user->id)
            ->orderBy('posts.serial_number', 'ASC')
            ->get();

        }
        if ($data->theme_version != 3) {
            $queryResult['featuredPosts'] = DB::table('posts')
                ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
                ->where('post_contents.language_id', '=', $language->id)
                ->where('is_featured', '=', 1)
                ->where('posts.is_featured', '!=', 10)
                ->where('posts.user_id', '=', $user->id)
                ->orderBy('posts.serial_number', 'ASC')
                ->get();
        }
        if ($data->theme_version == 2) {
            $limit = 4;
        } else {
            $limit = 6;
        }

        $queryResult['latestPosts'] = DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $language->id)
            ->where('posts.user_id', '=', $user->id)
            ->where('posts.is_featured', '!=', 10)
            ->orderByDesc('posts.created_at')
            ->limit($limit)
            ->get();

        if ($data->theme_version != 3) {
            $queryResult['galleryInfo'] =  $data;
            $queryResult['galleryItems'] = GalleryItem::query()
                ->where('language_id', $language->id)
                ->where('is_featured', 1)
                ->where('user_id', $user->id)
                ->orderBy('serial_number', 'ASC')
                ->get();
        }

        if ($data->theme_version != 4) {
            $queryResult['authorInfo'] = Information::query()
                ->where('language_id', $language->id)
                ->where('user_id', $user->id)
                ->first();

            $queryResult['mostViewedPosts'] = DB::table('posts')
                ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
                ->where('posts.views', '!=', 0)
                ->where('post_contents.language_id', '=', $language->id)
                ->where('posts.user_id', '=', $user->id)
                ->where('posts.is_featured', '!=', 10)
                ->orderByDesc('posts.views')
                ->limit(3)
                ->get();
        }

        $queryResult['featPostCategories'] = PostCategory::query()
            ->where('language_id', $language->id)
            ->where('status', 1)
            ->where('is_featured', 1)
            ->orderBy('serial_number', 'ASC')
            ->get();

        if (Auth::guard('customer')->check() == true) {
            $authUser = Auth::guard('customer')->user();
            $queryResult['bookmarkPosts'] = BookmarkPost::query()
                ->where('user_id', $authUser->id)
                ->where('author_id', $user->id)
                ->get();
        }

        if ($data->theme_version == 1) {
            return view('user-front.theme1.index', $queryResult);
        } else if ($data->theme_version == 2) {
            return view('user-front.theme2.index', $queryResult);
        } else if ($data->theme_version == 3) {
            return view('user-front.theme3.index', $queryResult);
        }else if($data->theme_version == 5){
            return view('user-front.theme-5.index', $queryResult);
        }else if($data->theme_version == 6){
            return view('user-front.theme-6.index', $queryResult);
        }else if($data->theme_version == 7){
            return view('user-front.theme-7.index', $queryResult);
        }else {
            return view('user-front.theme4.index', $queryResult);
        }
    }

    public function paymentInstruction(Request $request): \Illuminate\Http\JsonResponse
    {
        $offline = OfflineGateway::where('name', $request->name)
            ->select('short_description', 'instructions', 'is_receipt')
            ->first();

        return response()->json([
            'description' => $offline->short_description,
            'instructions' => $offline->instructions,
            'is_receipt' => $offline->is_receipt
        ]);
    }

    public function adminContactMessage(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required'
        ];

        $bs = BS::select('is_recaptcha')->first();

        if ($bs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
            $messages = [
                'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
                'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
            ];
        }

        $request->validate($rules, $messages);

        $data['fromMail'] = $request->email;
        $data['fromName'] = $request->name;
        $data['subject'] = $request->subject;
        $data['body'] = $request->message;
        $mailer = new MegaMailer();
        $mailer->mailToAdmin($data);
        Session::flash('success', 'Message sent successfully');
        return back();
    }


    public function userFaqs($domain)
    {
        $user = getUser();
        $language = $this->getUserCurrentLanguage($user->id);
        $queryResult['pageHeading'] = $this->getUserPageHeading($language, $user->id);
        $queryResult['bgImg'] = $this->getUserBreadcrumb($user->id);
        $queryResult['faqs'] = User\FAQ::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->get();
        return view('user-front.common.faq', $queryResult);
    }

    public function vcard($domain, $id)
    {
        $vcard = UserVcard::findOrFail($id);

        $count = $vcard->user->memberships()->where('status', '=', 1)
            ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'))
            ->count();

        // check if the vcard owner does not have membership
        if ($count == 0) {
            return view('errors.404');
        }

        $cFeatures = UserPermissionHelper::packagePermission($vcard->user_id);
        $cFeatures = json_decode($cFeatures, true);
        if (empty($cFeatures) || !is_array($cFeatures) || !in_array('vCard', $cFeatures)) {
            return view('errors.404');
        }

        $parsedUrl = parse_url(url()->current());
        $host = $parsedUrl['host'];
        // if the current host contains the website domain
        if (strpos($host, env('WEBSITE_HOST')) !== false) {
            $host = str_replace("www.", "", $host);
            // if the current URL is subdomain
            if ($host != env('WEBSITE_HOST')) {
                $hostArr = explode('.', $host);
                $username = $hostArr[0];
                if (strtolower($vcard->user->username) != strtolower($username) || !cPackageHasSubdomain($vcard->user)) {
                    return view('errors.404');
                }
            } else {
                $path = explode('/', $parsedUrl['path']);
                $username = $path[1];
                if (strtolower($vcard->user->username) != strtolower($username)) {
                    return view('errors.404');
                }
            }
        }
        // if the current host doesn't contain the website domain (meaning, custom domain)
        else {
            // Always include 'www.' at the begining of host
            if (substr($host, 0, 4) == 'www.') {
                $host = $host;
            } else {
                $host = 'www.' . $host;
            }
            // if the current package doesn't have 'custom domain' feature || the custom domain is not connected
            $cdomain = UserCustomDomain::where('requested_domain', '=', $host)->orWhere('requested_domain', '=', str_replace("www.", "", $host))->where('status', 1)->firstOrFail();
            $username = $cdomain->user->username;
            if (!cPackageHasCdomain($vcard->user) || ($username != $vcard->user->username)) {
                return view('errors.404');
            }
        }

        $infos = json_decode($vcard->information, true);

        $prefs = [];
        if (!empty($vcard->preferences)) {
            $prefs = json_decode($vcard->preferences, true);
        }

        $keywords = json_decode($vcard->keywords, true);

        $data['vcard'] = $vcard;
        $data['infos'] = $infos;
        $data['prefs'] = $prefs;
        $data['keywords'] = $keywords;
        if ($vcard->template == 1) {
            return view('vcard.index1', $data);
        } elseif ($vcard->template == 2) {
            return view('vcard.index2', $data);
        } elseif ($vcard->template == 3) {
            return view('vcard.index3', $data);
        } elseif ($vcard->template == 4) {
            return view('vcard.index4', $data);
        }
    }

    public function vcardImport($domain, $id)
    {
        $vcard = UserVcard::findOrFail($id);

        // define vcard
        $vcardObj = new VCard();

        // add personal data
        if (!empty($vcard->name)) {
            $vcardObj->addName($vcard->name);
        }
        if (!empty($vcard->company)) {
            $vcardObj->addCompany($vcard->company);
        }
        if (!empty($vcard->occupation)) {
            $vcardObj->addJobtitle($vcard->occupation);
        }
        if (!empty($vcard->email)) {
            $vcardObj->addEmail($vcard->email);
        }
        if (!empty($vcard->phone)) {
            $vcardObj->addPhoneNumber($vcard->phone, 'WORK');
        }
        if (!empty($vcard->address)) {
            $vcardObj->addAddress($vcard->address);
            $vcardObj->addLabel($vcard->address);
        }
        if (!empty($vcard->website_url)) {
            $vcardObj->addURL($vcard->website_url);
        }

        $vcardObj->addPhoto(public_path('assets/user/img/vcard/' . $vcard->profile_image));

        return \Response::make(
            $vcardObj->getOutput(),
            200,
            $vcardObj->getHeaders(true)
        );
    }
    public function gallery($domain)
    {
        $user = getUser();

        $language = $this->getUserCurrentLanguage($user->id);

        $queryResult['pageHeading'] = $this->getUserPageHeading($language, $user->id);

        $queryResult['bgImg'] = $this->getUserBreadcrumb($user->id);

        $queryResult['settings'] = User\BasicSetting::where('user_id', $user->id)
            ->select('gallery_category_status')
            ->first();

        $queryResult['categories'] = GalleryCategory::where('language_id', $language->id)
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->orderBy('serial_number', 'asc')
            ->get();

        $queryResult['items'] = GalleryItem::where('language_id', $language->id)
            ->where('user_id', $user->id)
            ->orderBy('serial_number', 'asc')
            ->get();
        return view('user-front.common.gallery', $queryResult);
    }

    public function about($domain)
    {
        $user = getUser();

        $language = $this->getUserCurrentLanguage($user->id);

        $queryResult['pageHeading'] = $this->getUserPageHeading($language, $user->id);

        $queryResult['bgImg'] = $this->getUserBreadcrumb($user->id);

        $queryResult['sldImgs'] = SliderImage::where('user_id', $user->id)->orderBy('serial_number', 'asc')->get();

        $queryResult['authorInfo'] = Information::where('language_id', $language->id)->where('user_id', $user->id)->first();

        $queryResult['testimonials'] = DB::table('user_testimonials')
            ->join('user_testimonial_contents', 'user_testimonials.id', '=', 'user_testimonial_contents.testimonial_id')
            ->where('user_testimonial_contents.language_id', '=', $language->id)
            ->where('user_testimonial_contents.user_id', '=', $user->id)
            ->orderBy('user_testimonials.serial_number', 'ASC')
            ->get();

        $queryResult['partners'] = User\Brand::where('user_id', '=', $user->id)->orderBy('serial_number', 'ASC')->get();

        return view('user-front.common.about', $queryResult);
    }

    public function userCPage($domain, $slug)
    {
        $user = getUser();
        $userId = $user->id;
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $data['bgImg'] = $this->getUserBreadcrumb($user->id);
        $data['details'] = User\PageContent::query()
            ->where('user_id', $userId)
            ->where('language_id', $userCurrentLang->id)
            ->where('slug', $slug)->firstOrFail();
        return view('user-front.common.custom-page', $data);
    }

    public function changeLanguage($lang)
    {
        session()->put('lang', $lang);
        app()->setLocale($lang);
        return redirect()->route('front.index');
    }

    public function changeUserLanguage(Request $request, $domain)
    {
        session()->put('user_lang', $request->code);
        app()->setLocale($request->code);
        return redirect()->route('front.user.detail.view', $domain);
    }

    public function countAdView($domain, $id)
    {
        $user = getUser();
        $ad = Advertisement::where('user_id', $user->id)->where('id', $id)->first();

        $ad->update([
            'views' => $ad->views + 1
        ]);

        return response()->json(['success' => 'Counted successfully.']);
    }
}
