<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
  use HasFactory;

  protected $table = 'user_page_contents';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'user_id',
    'page_id',
    'title',
    'slug',
    'content',
    'meta_keywords',
    'meta_description'
  ];
  protected $casts = [
    'preview' => 'array'
  ];

  public function contentLang()
  {
    return $this->belongsTo(Language::class);
  }

  public function page()
  {
    return $this->belongsTo(Page::class);
  }
}
