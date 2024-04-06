<?php

namespace App\Models\User;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $table = 'user_testimonials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_image',
        'serial_number',
        'rating',
        'user_id'
    ];

    public function testimonialContent()
    {
        return $this->hasMany(TestimonialContent::class)->where('user_id',Auth::id());
    }
}
