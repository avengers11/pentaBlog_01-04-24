<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHeading extends Model
{
    use HasFactory;

    protected $table = 'user_page_headings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'user_id',
        'about_me_title',
        'gallery_title',
        'posts_title',
        'post_details_title',
        'products_title',
        'product_details_title',
        'cart_title',
        'checkout_title',
        'faq_title',
        'contact_me_title',
        'error_page_title',
        'shop',
        'shop_details',
        'cart',
        'checkout'
    ];

    public function headingLang()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
