<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
    use HasFactory;

    protected $table = 'user_seos';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'language_id',
        'meta_keyword_home',
        'meta_description_home',
        'meta_keyword_about',
        'meta_description_about',
        'meta_keyword_gallery',
        'meta_description_gallery',
        'meta_keyword_posts',
        'meta_description_posts',
        'meta_keyword_faq',
        'meta_description_faq',
        'meta_keyword_contact',
        'meta_description_contact',
        'meta_keyword_login',
        'meta_description_login',
        'meta_keyword_signup',
        'meta_description_signup',
        'meta_keyword_forget_password',
        'meta_description_forget_password',
        'meta_keyword_shop',
        'meta_description_shop',
        'meta_keyword_shop_details',
        'meta_description_shop_details'
    ];

    public function language() {
        return $this->belongsTo(Language::class,'language_id');
    }
}
