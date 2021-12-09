<?php

namespace App\Models\Offers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferCondition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'min_bought_items',
        'can_be_activated_multiple_times',
        'bought_category_id',
        'bought_product_id'
    ];

    public function getCategory()
    {
        return $this->hasOne(OfferCondition::class , 'id' , 'bought_category_id');
    }

    public function getProduct()
    {
        return $this->hasOne(OfferDiscount::class , 'id' , 'bought_product_id');
    }
}
