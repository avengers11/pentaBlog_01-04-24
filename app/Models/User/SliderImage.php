<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    use HasFactory;

    protected $table = "user_slider_images";

    protected $fillable = [
        'user_id',
        'image',
        'serial_number'
    ];

    public function sliderVersionLang()
    {
        return $this->belongsTo(Language::class);
    }
}

