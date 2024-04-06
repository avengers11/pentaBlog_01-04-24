<?php

namespace App\Models\User;

use App\Models\User\Post;
use App\Models\User\PostCategory;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostContent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'language_id',
        'post_category_id',
        'post_id',
        'title',
        'slug',
        'author',
        'content',
        'meta_keywords',
        'meta_description'
    ];

    public function contentLang()
    {
        return $this->belongsTo(Language::class,'language_id');
    }

    public function postCategory()
    {
        return $this->belongsTo(PostCategory::class,'post_category_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class,'post_id');
    }
}
