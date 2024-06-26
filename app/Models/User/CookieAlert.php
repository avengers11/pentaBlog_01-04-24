<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookieAlert extends Model
{
    use HasFactory;

    protected $table='user_cookie_alerts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'user_id',
        'cookie_alert_status',
        'cookie_alert_btn_text',
        'cookie_alert_text'
    ];

    public function cookieAlertLang()
    {
        return $this->belongsTo(Language::class);
    }
}
