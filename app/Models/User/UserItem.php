<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'user_items';
    protected $fillable = [
        'thumbnail'
    ];

    public function itemContents()
    {
        return $this->hasMany(UserItemContent::class, 'item_id', 'id');
    }
    public function sliders()
    {
        return $this->hasMany(UserItemImage::class, 'item_id', 'id');
    }
    public function orderItems()
    {
        return $this->hasMany(UserOrderItem::class, 'item_id', 'id');
    }
    public function wishlist()
    {
        return $this->hasMany(CustomerWishList::class, 'item_id', 'id');
    }
}
