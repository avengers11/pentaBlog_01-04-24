<?php

namespace App\Models\User;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialContent extends Model
{
    use HasFactory;

    protected $table = 'user_testimonial_contents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'testimonial_id',
        'client_name',
        'comment',
        'user_id'
    ];

    public function testimonial()
    {
        return $this->belongsTo('App\Models\User\Testimonial','testimonial_id')->where('user_id', Auth::id());
    }

    public function testimonialUser()
    {
        return $this->belongsTo('App\Models\User\Testimonial', 'testimonial_id');
    }


    public function testimonialContentLang()
    {
        return $this->belongsTo('App\Models\User\Language','language_id')->where('user_id',Auth::id());
    }
}
