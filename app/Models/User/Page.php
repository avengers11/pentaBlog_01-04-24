<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
  use HasFactory;

  protected $table = 'user_pages';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['status','user_id'];

  public function content()
  {
    return $this->hasMany(PageContent::class);
  }
}
