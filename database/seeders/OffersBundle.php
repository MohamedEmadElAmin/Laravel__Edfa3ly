<?php

namespace Database\Seeders;

use App\Models\Offers\OfferCondition;
use App\Models\Offers\OfferDiscount;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OffersBundle extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shoes = Product::where('name', 'Shoes')->first();
        $jacket = Product::where('name', 'Jacket')->first();
        $topsID = ProductCategory::where('name', 'tops')->first()->id;


        $this->createOfferConditions($topsID, $shoes);
        $this->createOfferDiscounts($shoes , $jacket);
        $this->createOffers($topsID);
    }



    private function createOfferConditions($topsID, $shoes)
    {
        DB::table('offer_conditions')->insert
        ([
            //Shoes are on 10% off.
            ['min_bought_items' => 1, 'can_be_activated_multiple_times' => true, 'bought_category_id' => NULL,
                'bought_product_id' => $shoes->id],

            //Buy any two tops (t-shirt or blouse) and get any jacket half its price.
            ['min_bought_items' => 2, 'can_be_activated_multiple_times' => true, 'bought_category_id' => $topsID,
                'bought_product_id' => NULL],

            //Buy any two items or more and get a maximum of $10 off shipping fees.
            ['min_bought_items' => 2, 'can_be_activated_multiple_times' => false, 'bought_category_id' => NULL,
                'bought_product_id' => NULL]
        ]);
    }
    private function createOfferDiscounts($shoes, $jacket)
    {
        DB::table('offer_discounts')->insert
        ([
            //Shoes are on 10% off.
            ['discount_type' => 'percentage', 'discount_value' => 10, 'discount_from' => 'price',
                'discount_product_id' => $shoes->id],

            //Buy any two tops (t-shirt or blouse) and get any jacket half its price.
            ['discount_type' => 'percentage', 'discount_value' => 50, 'discount_from' => 'price',
                'discount_product_id' => $jacket->id],

            //Buy any two items or more and get a maximum of $10 off shipping fees.
            ['discount_type' => 'fixed', 'discount_value' => 10, 'discount_from' => 'shippingFees',
                'discount_product_id' => NULL]
        ]);
    }
    private function createOffers($topsID)
    {
        $conditions = OfferCondition::all();
        $cond1 = _findInList($conditions , 'min_bought_items' , 1);
        $cond2 = _findInList($conditions , 'bought_category_id' , $topsID);
        $cond3 = _findInListMultiple($conditions , [ 'min_bought_items' => 2,'can_be_activated_multiple_times' => false]);

        $discounts = OfferDiscount::all();
        $disc1 = _findInList($discounts , 'discount_value' , 10);
        $disc2 = _findInList($discounts , 'discount_value' , 50);
        $disc3 = _findInListMultiple($discounts , ['discount_value' => 10 , 'discount_from' => 'shippingFees']);

        DB::table('offers')->insert
        ([
            ['name' => "Shoes are on 10% off",
                'condition_id' => $cond1->id, 'is_active' => true,
                'discount_id' => $disc1->id],


            ['name' => "Buy any two tops (t-shirt or blouse) and get any jacket half its price.",
                'condition_id' => $cond2->id, 'is_active' => true,
                'discount_id' => $disc2->id],


            ['name' => "Buy any two items or more and get a maximum of $10 off shipping fees.",
                'condition_id' => $cond3->id, 'is_active' => true,
                'discount_id' => $disc3->id]
        ]);
    }
}
