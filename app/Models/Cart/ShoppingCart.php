<?php

namespace App\Models\Cart;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'subtotal' => 0,
        'shipping_fees' => 0,
        'vat' => 0,
        'total' => 0,
        'subtotal_wo_offers' => 0,
        'shipping_fees_wo_offers' => 0,
        'vat_wo_offers' => 0,
        'total_wo_offers' => 0,
        'discount_shipping_fees' => 0,
        'user_id',
    ];

    //Eloquent ORM RelationShip (Not in the database)
    public function user()
    {
        return $this->hasOne(User::class , 'id' , 'user_id');
    }
}
