<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'thumbnail_image',
        'slider_images',
        'serial_number',
        'is_slider',
        'slider_post_image',
        'is_featured',
        'featured_post_image',
        'views',
        'bookmarks',
        'user_id',
        'is_hero_post',
        'hero_post_image',
        'image_size_type'
    ];

    public function content()
    {
        return $this->hasMany(PostContent::class);
    }

    public function view()
    {
        return $this->hasMany(PostView::class);
    }

    public function bookmark()
    {
        return $this->hasMany(BookmarkPost::class);
    }
}

