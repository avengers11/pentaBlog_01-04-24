<?php

namespace App\Models;

use App\Models\User\Advertisement;
use App\Models\User\Brand;
use App\Models\User\CookieAlert;
use App\Models\User\FAQ;
use App\Models\User\FooterQuickLink;
use App\Models\User\FooterText;
use App\Models\User\GalleryCategory;
use App\Models\User\GalleryItem;
use App\Models\User\HomeSection;
use App\Models\User\Information;
use App\Models\User\PageHeading;
use App\Models\User\Popup;
use App\Models\User\Post;
use App\Models\User\PostCategory;
use App\Models\User\UserQrCode;
use App\Models\User\UserVcard;
use App\Notifications\UserResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Controllers\Controller;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'photo',
        'username',
        'password',
        'phone',
        'city',
        'state',
        'address',
        'country',
        'status',
        'featured',
        'verification_link',
        'email_verified',
        'keywords',
        'designation',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_custom_domains() {
        return $this->hasMany('App\Models\User\UserCustomDomain','user_id');
    }

    public function custom_domains() {
        return $this->hasMany('App\Models\User\UserCustomDomain');
    }

    public function memberships() {
        return $this->hasMany('App\Models\Membership','user_id');
    }

    public function basic_setting(){
        return $this->hasOne('App\Models\User\BasicSetting','user_id');
    }

    public function seos(){
        return $this->hasOne(\App\Models\User\SEO::class,'user_id');
    }

    public function testimonials(){
        return $this->hasMany('App\Models\User\Testimonial','user_id');
    }

    public function social_media(){
        return $this->hasMany('App\Models\User\Social','user_id');
    }

    public function permission(){
        return $this->hasOne('App\Models\User\UserPermission','user_id');
    }
    public function languages(){
        return $this->hasMany('App\Models\User\Language','user_id');
    }
    public function brands(){
        return $this->hasMany(Brand::class,'user_id');
    }
    public function pageHeading(){
        return $this->hasOne(PageHeading::class,'user_id');
    }
    public function cookieAlert(){
        return $this->hasOne(CookieAlert::class,'user_id');
    }
    public function menus(){
        return $this->hasOne(\App\Models\User\Menu::class,'user_id');
    }
    public function postCategory(){
        return $this->hasMany(PostCategory::class,'user_id');
    }
    public function galleryCategory(){
        return $this->hasMany(GalleryCategory::class,'user_id');
    }
    public function galleryItem(){
        return $this->hasMany(GalleryItem::class, 'user_id');
    }
    public function authorInfo(){
        return $this->hasOne(Information::class,'user_id');
    }
    public function faq(){
        return $this->hasMany(FAQ::class,'user_id');
    }
    public function announcementPopup(){
        return $this->hasMany(Popup::class,'user_id');
    }
    public function footerText(){
        return $this->hasOne(FooterText::class,'user_id');
    }
    public function footerQuickLink(){
        return $this->hasMany(FooterQuickLink::class, 'user_id');
    }
    public function subscriber(){
        return $this->hasMany(\App\Models\User\Subscriber::class, 'user_id');
    }
    public function posts(){
        return $this->hasMany(Post::class,'user_id');
    }
    public function qr_code(){
        return $this->hasOne(UserQrCode::class,'user_id');
    }
    public function vcards(){
        return $this->hasMany(UserVcard::class,'user_id');
    }
    public function home_section(){
        return $this->hasOne(HomeSection::class,'user_id');
    }
    public function customer(){
        return $this->hasMany(Customer::class,'user_id');
    }
    public function advertisements(){
        return $this->hasMany(Advertisement::class,'user_id');
    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $username = User::query()->where('email',request()->email)->pluck('username')->first();
        $subject = 'You are receiving this email because we received a password reset request for your account.';
        $body = "Recently you tried forget password for your account.Click below to reset your account password.
             <br>
             <a href='".url('password/reset/'.$token .'/email/'.request()->email)."'><button type='button' class='btn btn-primary'>Reset Password</button></a>
             <br>
             Thank you.
             ";
        $controller = new Controller();
        $controller->resetPasswordMail(request()->email,$username,$subject,$body);
        session()->flash('success', "we sent you an email. Please check your inbox");
    }

}
