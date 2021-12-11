<?php

namespace App\Models\Cart;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCartItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'quantity' => 1,
        'original_price' => 0,
        'discount_price' => 0,
        'original_shipping_fees' => 0,
        'product_id',
        'shopping_cart_id',
    ];

    public function product()
    {
        return $this->hasOne(Product::class , 'id' , 'product_id');
    }

    public function shoppingCart()
    {
        return $this->hasOne(ShoppingCart::class , 'id' , 'shopping_cart_id');
    }
}
