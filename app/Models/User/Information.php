<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table='user_informations';
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
        'about',
        'video_background_image',
        'link'
    ];

    public function infoLang()
    {
        return $this->belongsTo(Language::class);
    }
}
