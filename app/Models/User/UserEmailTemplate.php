<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['email_body','email_subject','email_type'];
}
