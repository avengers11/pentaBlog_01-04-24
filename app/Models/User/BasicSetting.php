<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class BasicSetting extends Model
{
    public $table = "user_basic_settings";

    protected $fillable = [
        'favicon',
        'logo',
        'cv',
        'base_color',
        'theme',
        'post_view_type',
        'gallery_bg',
        'gallery_category_status',
        'disqus_status',
        'disqus_short_name',
        'google_recaptcha_status',
        'google_recaptcha_site_key',
        'google_recaptcha_secret_key',
        'user_id',
        'base_currency_symbol',
        'base_currency_symbol_position',
        'base_currency_text',
        'base_currency_text_position',
        'base_currency_rate',
        'pixel_status',
        'pixel_id',
        'tawkto_status',
        'tawkto_direct_chat_link',
        'hero_section_bg_image',
        'news_letter_section_bg_image',
        'news_letter_section_bg_image',
        'news_letter_section_bg_image',
        'text_to_logo',
        'text_to_logo_status',
    ];

    public function language(){
        return $this->hasMany('App\Models\User\Language','user_id');
    }
}
