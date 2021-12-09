<?php

namespace App\Models\Offers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferDiscount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'discount_percentage',
        'discount_fixed',
        'discount_form',
        'discount_category_id',
        'discount_product_id'
    ];

    public function getCategory()
    {
        return $this->hasOne(OfferCondition::class , 'id' , 'discount_category_id');
    }

    public function getProduct()
    {
        return $this->hasOne(OfferDiscount::class , 'id' , 'discount_product_id');
    }
}
