<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Language extends Model
{
    public $table = "user_languages";

    protected $fillable = [
        'id',
        'name',
        'is_default',
        'code',
        'rtl',
        'user_id',
        'keywords'
    ];

    public function itemInfo()
    {
        return $this->hasMany(UserItemContent::class, 'language_id');
    }


    public function pageHeading()
    {
        return $this->hasOne(PageHeading::class, 'language_id')->where('user_id', Auth::id());
    }
    public function seos()
    {
        return $this->hasOne(SEO::class, 'language_id')->where('user_id', Auth::id());
    }
    public function variations()
    {
        return $this->hasMany('App\Models\User\UserItemVariation', 'language_id');
    }
    public function user_item_contacts()
    {
        return $this->hasMany('App\Models\User\UserItemContent', 'language_id');
    }


    public function cookieAlert()
    {
        return $this->hasOne(CookieAlert::class, 'language_id')->where('user_id', Auth::id());
    }
    public function postCategory()
    {
        return $this->hasMany(PostCategory::class, 'language_id')->where('user_id', Auth::id());
    }
    public function postInfo()
    {
        return $this->hasMany(PostContent::class, 'language_id')->where('user_id', Auth::id());
    }
    public function galleryCategory()
    {
        return $this->hasMany(GalleryCategory::class, 'language_id')->where('user_id', Auth::id());
    }
    public function galleryItem()
    {
        return $this->hasMany(GalleryItem::class, 'language_id')->where('user_id', Auth::id());
    }
    public function authorInfo()
    {
        return $this->hasOne(Information::class, 'language_id')->where('user_id', Auth::id());
    }
    public function faq()
    {
        return $this->hasMany(FAQ::class, 'language_id')->where('user_id', Auth::id());
    }
    public function announcementPopup()
    {
        return $this->hasMany(Popup::class, 'language_id')->where('user_id', Auth::id());
    }
    public function footerText()
    {
        return $this->hasOne(FooterText::class, 'language_id')->where('user_id', Auth::id());
    }
    public function footerQuickLink()
    {
        return $this->hasMany(FooterQuickLink::class, 'language_id')->where('user_id', Auth::id());
    }
    public function menus()
    {
        return $this->hasOne(Menu::class, 'language_id')->where('user_id', Auth::id());
    }

    public function customPageInfo()
    {
        return $this->hasMany(PageContent::class, 'language_id')->where('user_id', Auth::id());
    }
}
