<?php


namespace App\Helpers;

use App\Models\Cart\ShoppingCart;
use App\Models\Cart\ShoppingCartItem;
use App\Models\Cart\ShoppingCartOffers;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class CartOffersHelper
{
    public static function addAvailableOffersToCart(&$shoppingCart, $shoppingCartItems)
    {
        $activeOffers = DB::table('offers')
            ->where('is_active' , true)
            ->join('offer_conditions', 'offers.condition_id', '=', 'offer_conditions.id')
            ->select('offers.id', 'offer_conditions.min_bought_items', 'offer_conditions.bought_product_id',
            'bought_category_id')
            ->get();

        foreach ($activeOffers as $activeOffer)
        {
            $result = CartOffersHelper::checkForOfferConditions($activeOffer, $shoppingCartItems);
            if($result)
                ShoppingCartOffers::firstOrCreate(['shopping_cart_id' => $shoppingCart->id , 'offer_id' => $activeOffer->id]);
            else
                ShoppingCartOffers::where(['shopping_cart_id' => $shoppingCart->id , 'offer_id' => $activeOffer->id])->delete();
        }
    }
    private static function checkForOfferConditions($offer, $shoppingCartItems): bool
    {
        //check if this product in our cart or not
        $cond1 = false;
        $numberOfMatchedItemsForCond1 = 0;
        if($offer->bought_product_id != NULL)
        {
            foreach ($shoppingCartItems as $item)
            {
                if($item->product->id == $offer->bought_product_id)
                {
                    $cond1 = true;
                    $numberOfMatchedItemsForCond1++;
                }
            }
        }
        else
            $cond1 = true;


        //check if this category have product in our cart or not
        $cond2 = false;
        $numberOfMatchedItemsForCond2 = 0;
        if($offer->bought_category_id != NULL)
        {
            foreach ($shoppingCartItems as $item)
            {
                if($item->product->category_id == $offer->bought_category_id)
                {
                    $cond2 = true;
                    $numberOfMatchedItemsForCond2++;
                }
            }
        }
        else
            $cond2 = true;


        $cond3 = true;
        if($offer->min_bought_items != NULL)
        {
            if($offer->bought_product_id == NULL && $offer->bought_category_id == NULL)
            {
                if(count($shoppingCartItems) < $offer->min_bought_items)
                    $cond3 = false;
                else
                    $cond3 = true;
            }
            else
            {
                if($offer->bought_product_id != NULL)
                {
                    if($numberOfMatchedItemsForCond1 < $offer->min_bought_items)
                        $cond3 = false;
                }
                if($offer->bought_category_id != NULL)
                {
                    if($numberOfMatchedItemsForCond2 < $offer->min_bought_items)
                        $cond3 = false;
                }
            }
        }


        if($cond1 && $cond2 && $cond3)
            return true;
        else
            return false;
    }



    public static function applyDiscountOnCart(&$shoppingCart, $shoppingCartItems)
    {
        $shoppingCartActiveOffers = DB::table('shopping_cart_offers')

            ->join('offers', 'shopping_cart_offers.offer_id', '=', 'offers.id')
            ->join('offer_discounts', 'offers.discount_id', '=', 'offer_discounts.id')
            ->where('shopping_cart_id' , $shoppingCart->id)
            ->select('offers.name' ,'offers.id', 'offer_discounts.*')
            ->get();

        foreach ($shoppingCartActiveOffers as $shoppingCartActiveOffer)
        {
            CartOffersHelper::applyDiscountOnCartForThisOffer($shoppingCart, $shoppingCartItems, $shoppingCartActiveOffer);
        }
    }
    private static function applyDiscountOnCartForThisOffer(&$shoppingCart, $shoppingCartItems, $shoppingCartActiveOffer)
    {
        //Discount on product
        if($shoppingCartActiveOffer->discount_product_id != NULL) //discount_from = price
        {
            foreach ($shoppingCartItems as $item)
            {
                if($item->product->id == $shoppingCartActiveOffer->discount_product_id)
                {
                    if($shoppingCartActiveOffer->discount_type == "fixed")
                        $discountPrice = $item->original_price - $shoppingCartActiveOffer->discount_value;
                    else
                        $discountPrice = $item->original_price - (($item->original_price * $shoppingCartActiveOffer->discount_value) / 100);

                    $cartItem = ShoppingCartItem::find($item->id);
                    $cartItem->discount_price = $discountPrice;
                    $cartItem->save();
                    break;
                }
            }
        }
        //Discount on shipping
        else
        {
            if($shoppingCartActiveOffer->discount_from == "shippingFees")
            {
                if($shoppingCartActiveOffer->discount_type == "fixed")
                {
                    $shoppingCart->discount_shipping_fees =  -$shoppingCartActiveOffer->discount_value;
                    $shoppingCart->save();
                }
            }
        }
    }
}
