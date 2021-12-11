<?php

namespace App\Models\Offers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'condition_id',
        'discount_id',
        'is_active'
    ];


    //Eloquent ORM RelationShip (Not in the database)
    public function condition()
    {
        return $this->hasOne(OfferCondition::class , 'id' , 'condition_id');
    }

    public function discount()
    {
        return $this->hasOne(OfferDiscount::class , 'id' , 'discount_id');
    }
}
