<?php

namespace App\Models\Cart;

use App\Models\Offers\Offer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCartOffers extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'quantity',
        'offer_id',
        'is_active',
        'shopping_cart_id',
    ];

    public function offer()
    {
        return $this->hasOne(Offer::class , 'id' , 'offer_id');
    }

    public function shoppingCart()
    {
        return $this->hasOne(ShoppingCart::class , 'id' , 'shopping_cart_id');
    }
}
