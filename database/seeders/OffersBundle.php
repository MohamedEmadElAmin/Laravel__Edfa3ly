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
        $t_shirt = Product::where('name', 'T-shirt')->first();
        $topsID = ProductCategory::where('name', 'tops')->first()->id;


        $this->createOfferConditions($topsID, $t_shirt);
        $this->createOfferDiscounts($topsID, $t_shirt);
        $this->createOffers($topsID);
    }



    private function createOfferConditions($topsID, $t_shirt)
    {
        DB::table('offer_conditions')->insert
        ([
            //Shoes are on 10% off.
            ['min_bought_items' => 1, 'can_be_activated_multiple_times' => true, 'bought_category_id' => NULL,
                'bought_product_id' => $t_shirt->id],

            //Buy any two tops (t-shirt or blouse) and get any jacket half its price.
            ['min_bought_items' => 2, 'can_be_activated_multiple_times' => true, 'bought_category_id' => $topsID,
                'bought_product_id' => NULL],

            //Buy any two items or more and get a maximum of $10 off shipping fees.
            ['min_bought_items' => 2, 'can_be_activated_multiple_times' => false, 'bought_category_id' => NULL,
                'bought_product_id' => NULL]
        ]);
    }
    private function createOfferDiscounts($topsID, $t_shirt)
    {
        DB::table('offer_discounts')->insert
        ([
            //Shoes are on 10% off.
            ['discount_percentage' => 10, 'discount_fixed' => NULL, 'discount_form' => NULL,
                'discount_product_id' => $t_shirt->id , 'discount_category_id' => NULL],

            //Buy any two tops (t-shirt or blouse) and get any jacket half its price.
            ['discount_percentage' => 50, 'discount_fixed' => NULL, 'discount_form' => NULL,
                'discount_product_id' => NULL, 'discount_category_id' => $topsID],

            //Buy any two items or more and get a maximum of $10 off shipping fees.
            ['discount_percentage' => NULL, 'discount_fixed' => 10, 'discount_form' => 'ShippingFees',
                'discount_product_id' => NULL, 'discount_category_id' => NULL]
        ]);
    }
    private function createOffers($topsID)
    {
        $conditions = OfferCondition::all();
        $cond1 = findInList($conditions , 'min_bought_items' , 1);
        $cond2 = findInList($conditions , 'bought_category_id' , $topsID);
        $cond3 = findInListMultiple($conditions , ["conditions" =>
            [
                ["pName" => 'min_bought_items' , 'pValue' => 2],
                ["pName" => 'can_be_activated_multiple_times' , 'pValue' => false],
            ]]);

        $discounts = OfferDiscount::all();
        $disc1 = findInList($discounts , 'discount_percentage' , 10);
        $disc2 = findInList($discounts , 'discount_percentage' , 50);
        $disc3 = findInList($discounts , 'discount_fixed' , 10);

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
