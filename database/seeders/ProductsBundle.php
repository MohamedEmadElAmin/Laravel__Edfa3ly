<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsBundle extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createCountries();
        $this->createSettings();
        $this->createProductCategories();

        $productCategories = ProductCategory::all();
        $topsID = _findInList($productCategories , 'name' , 'tops')->id;
        $defaultID = _findInList($productCategories , 'name' , 'default')->id;
        $this->createProducts($topsID, $defaultID);
    }


    private function createCountries()
    {
        DB::table('countries')->insert
        ([
            ['name' => 'US', 'shipping_rate' => 2 , 'weight_grams' => 100],
            ['name' => 'UK', 'shipping_rate' => 3 , 'weight_grams' => 100],
            ['name' => 'CN', 'shipping_rate' => 2 , 'weight_grams' => 100]
        ]);
    }
    private function createSettings()
    {
        DB::table('settings')->insert
        ([
            ['vat' => 14]
        ]);
    }
    private function createProductCategories()
    {
        DB::table('product_categories')->insert
        ([
            ['name' => 'default'],
            ['name' => 'tops'],
        ]);
    }
    private function createProducts($topsID, $defaultID)
    {
        //generic function defined helpers.php
        $countries = Country::all();
        $us = _findInList($countries , 'name' , 'US');
        $uk = _findInList($countries , 'name' , 'UK');
        $cn = _findInList($countries , 'name' , 'CN');



        //Shipping Fees
        $shipping_fees_t_shirt = $this->calculateShippingFeesForProduct(0.2 , $us);
        $shipping_fees_blouse = $this->calculateShippingFeesForProduct(0.3 , $uk);
        $shipping_fees_pants = $this->calculateShippingFeesForProduct(0.9 , $uk);
        $shipping_fees_sweatpants = $this->calculateShippingFeesForProduct(1.1 , $cn);
        $shipping_fees_jacket = $this->calculateShippingFeesForProduct(2.2 , $us);
        $shipping_fees_shoes = $this->calculateShippingFeesForProduct(1.3 , $cn);



        DB::table('products')->insert
        ([
            ['name' => 'T-shirt', 'price' => 30.99, 'weight_kg' => 0.2, 'shipped_from_country_id' => $us->id,
                'category_id' => $topsID, 'shipping_fees' => $shipping_fees_t_shirt , 'hash' => _generateRandomString(200) ],
            ['name' => 'Blouse', 'price' => 10.99, 'weight_kg' => 0.3, 'shipped_from_country_id' => $uk->id,
                'category_id' => $topsID, 'shipping_fees' => $shipping_fees_blouse , 'hash' => _generateRandomString(200) ],
            ['name' => 'Pants', 'price' => 64.99, 'weight_kg' => 0.9, 'shipped_from_country_id' => $uk->id,
                'category_id' => $defaultID, 'shipping_fees' => $shipping_fees_pants , 'hash' => _generateRandomString(200) ],
            ['name' => 'Sweatpants', 'price' => 84.99, 'weight_kg' => 1.1, 'shipped_from_country_id' => $cn->id,
                'category_id' => $defaultID, 'shipping_fees' => $shipping_fees_sweatpants , 'hash' => _generateRandomString(200) ],
            ['name' => 'Jacket', 'price' => 199.99, 'weight_kg' => 2.2, 'shipped_from_country_id' => $us->id,
                'category_id' => $defaultID, 'shipping_fees' => $shipping_fees_jacket , 'hash' => _generateRandomString(200) ],
            ['name' => 'Shoes', 'price' => 79.99, 'weight_kg' => 1.3, 'shipped_from_country_id' => $cn->id,
                'category_id' => $defaultID, 'shipping_fees' => $shipping_fees_shoes , 'hash' => _generateRandomString(200) ]
        ]);

        //Real Hash
        $products = Product::select('id','hash')->get();
        foreach ($products as $product) {
            $product->hash  = _generateHash($product->id);
            $product->save();
        }

    }
    private function calculateShippingFeesForProduct($productWeight, Country $country)
    {
        return ($productWeight / ($country->weight_grams / 1000)) * $country->shipping_rate;
    }
}
