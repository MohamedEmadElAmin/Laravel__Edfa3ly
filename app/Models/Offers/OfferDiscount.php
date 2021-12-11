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
        'discount_type',
        'discount_value',
        'discount_from',
        'discount_product_id'
    ];


    public function product()
    {
        return $this->hasOne(OfferDiscount::class , 'id' , 'discount_product_id');
    }
}
