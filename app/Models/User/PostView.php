<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id', 'user_id', 'ip', 'author_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function viewByUser()
    {
        return $this->belongsTo(User::class);
    }
}
