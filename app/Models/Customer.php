<?php

namespace App\Models;

use App\Models\User\BookmarkPost;
use App\Models\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Controllers\Controller;

class Customer extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'username',
        'email',
        'email_verified_at',
        'password',
        'contact_number',
        'address',
        'city',
        'state',
        'country',
        'status',
        'verification_token',
        'remember_token',
        'verification_link',
        'user_id',
        'shpping_fname',
        'shpping_lname',
        'shpping_email',
        'shpping_number',
        'shpping_city',
        'shpping_state',
        'shpping_address',
        'shpping_country',
        'billing_fname',
        'billing_lname',
        'billing_email',
        'billing_number',
        'billing_city',
        'billing_state',
        'billing_address',
        'billing_country',
    ];

    use Notifiable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $username = Customer::query()->where('email',request()->email)->pluck('username')->first();
        $subject = 'You are receiving this email because we received a password reset request for your account.';
        $body = "Recently you tried forget password for your account.Click below to reset your account password.
             <br>
             <a href='".url('password/reset/'.$token .'/email/'.request()->email)."'><button type='button' class='btn btn-primary'>Reset Password</button></a>
             <br>
             Thank you.
             ";
        $controller = new Controller();
        $controller->resetPasswordMail(request()->email,$username,$subject,$body);
        session()->flash('success', "we sent you an email. Please check your inbox");
    }

    public function bookmarkList()
    {
        return $this->hasMany(BookmarkPost::class,'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
