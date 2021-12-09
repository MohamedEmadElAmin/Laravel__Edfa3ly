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
        'subtotal',
        'shipping',
        'vat',
        'total',
        'user_id',
    ];

    //Eloquent ORM RelationShip (Not in the database)
    public function getUser()
    {
        return $this->hasOne(User::class , 'id' , 'user_id');
    }
}
