<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public $table = "packages";

    protected $fillable = [
        'title',
        'slug',
        'price',
        'term',
        'featured',
        'is_trial',
        'trial_days',
        'status',
        'features',
        'feature_posts_limit',
        'post_categories_limit',
        'posts_limit',
        'language_limit',
        'serial_number',
        'meta_keywords',
        'meta_description',
        'number_of_vcards'
    ];

    public function memberships() {
        return $this->hasMany('App\Models\Membership');
    }
}
