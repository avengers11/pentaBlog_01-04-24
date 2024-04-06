<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'user_id',
        'image',
        'name',
        'status',
        'serial_number',
        'is_featured'
    ];

    public function categoryLang()
    {
        return $this->belongsTo(Language::class);
    }

    public function postContentList()
    {
        return $this->hasMany(PostContent::class);
    }
}
