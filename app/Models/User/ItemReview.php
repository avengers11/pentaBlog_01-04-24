<?php

namespace App\Models\User;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemReview extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'item_id', 'review', 'comment'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}
