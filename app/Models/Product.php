<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'price',
        'weight_kg',
        'shipping_fees',
        'shipped_from_country_id',
        'category_id',
    ];


    //Eloquent ORM RelationShip (Not in the database)
    public function getShippedCountry()
    {
        return $this->hasOne(Country::class , 'id' , 'shipped_from_country_id');
    }

    public function getCategory()
    {
        return $this->hasOne(ProductCategory::class , 'id' , 'category_id');
    }
}
