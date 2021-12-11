<?php


namespace App\Helpers;


use App\Models\Cart\ShoppingCart;
use App\Models\Cart\ShoppingCartItem;
use App\Models\Offers\Offer;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CartHelper
{
    public static function addProductToCart($params): array
    {
        DB::beginTransaction();

        $shoppingCart = array_key_exists('shoppingCart' , $params) ? $params['shoppingCart'] : NULL;
        $product = array_key_exists('product' , $params) ? $params['product'] : NULL;
        $message = "Product Add Successfully";
        $error = false;

        $cartItem = ShoppingCartItem::where([['shopping_cart_id',$shoppingCart->id],  ['product_id' , $product->id]])->first();
        if(!$cartItem)
        {
            DB::table('shopping_cart_items')->insert([
                'product_id' => $product->id,
                'shopping_cart_id' => $shoppingCart->id,
                'original_price'=> $product->price,
                'discount_price'=> $product->price,
                'original_shipping_fees'=>$product->shipping_fees
            ]);
            CartHelper::updateCartAfterAddingProduct($shoppingCart, $product);
        }
        else
        {
            $message = "You Already Added This Product.";
            $error = true;
        }

        DB::commit();
        return ['message' => $message, 'error' => $error];
    }
    public static function removeProductForCart($params): array
    {
        DB::beginTransaction();

        $shoppingCart = array_key_exists('shoppingCart' , $params) ? $params['shoppingCart'] : NULL;
        $product = array_key_exists('product' , $params) ? $params['product'] : NULL;
        $error = false;

        $cartItem = ShoppingCartItem::where([['shopping_cart_id',$shoppingCart->id],  ['product_id' , $product->id]])->first();
        if(!$cartItem)
        {
            $message = "That Product Doesn't Exist In Your Current Cart.";
            $error = true;
        }
        else
        {
            $message = "Product Deleted Successfully.";
            CartHelper::updateCartAfterRemovingProduct($shoppingCart, $product);
            ShoppingCartItem::destroy($cartItem->id);
        }

        DB::commit();
        return ['message' => $message, 'error' => $error];
    }
    public static function calculateForInvoice(&$shoppingCart)
    {
        $shoppingCartItems = DB::table('shopping_cart_items')
            ->where('shopping_cart_id' , $shoppingCart->id)
            ->get();

        $subTotalWithoutOffers = 0;
        $subTotal = 0;
        $shippingFeesWithoutOffers = 0;
        $shippingFees = 0;
        $vatWithoutOffers = 0;
        //$vat = 0;
        $totalWithoutOffers = 0;
        $total = 0;
        foreach ($shoppingCartItems as $shoppingCartItem)
        {
            $subTotalWithoutOffers += $shoppingCartItem->original_price;
            $subTotal += $shoppingCartItem->discount_price;

            $shippingFeesWithoutOffers += $shoppingCartItem->original_shipping_fees;
            $shippingFees += $shoppingCartItem->original_shipping_fees; //same as shipping fees
        }
        $shippingFees += $shoppingCart->discount_shipping_fees; //subtract the discount


        $setting = Setting::first();
        $vatWithoutOffers = ($subTotalWithoutOffers * $setting->vat) / 100;
        //$vat = ($subTotal * $setting->vat) / 100;

        $totalWithoutOffers = $vatWithoutOffers + $shippingFeesWithoutOffers + $subTotalWithoutOffers;
        $total = $vatWithoutOffers + $shippingFees + $subTotal;



        $shoppingCart->subtotal = $subTotal;
        $shoppingCart->shipping_fees = $shippingFees;
        $shoppingCart->vat = $vatWithoutOffers;
        $shoppingCart->total = $total;

        $shoppingCart->subtotal_wo_offers = $subTotalWithoutOffers;
        $shoppingCart->shipping_fees_wo_offers = $shippingFeesWithoutOffers;
        $shoppingCart->vat_wo_offers = $vatWithoutOffers;
        $shoppingCart->total_wo_offers = $totalWithoutOffers;
        $shoppingCart->save();


        //lad($shoppingCartItems);
        //lad($shoppingCart);
    }






    private static function updateCartAfterAddingProduct(&$shoppingCart, $product)
    {
        $shoppingCart->shipping_fees = $shoppingCart->shipping_fees + $product->shipping_fees;
        $shoppingCart->subtotal = $shoppingCart->subtotal + $product->price;
        $shoppingCart->total = $shoppingCart->subtotal + $shoppingCart->shipping_fees;

        $shoppingCart->shipping_fees_wo_offers = $shoppingCart->shipping_fees;
        $shoppingCart->subtotal_wo_offers = $shoppingCart->subtotal;
        $shoppingCart->total_wo_offers = $shoppingCart->total;
        $shoppingCart->save();
    }
    private static function updateCartAfterRemovingProduct(&$shoppingCart, $product)
    {
        $shoppingCart->shipping_fees = $shoppingCart->shipping_fees - $product->shipping_fees;
        $shoppingCart->subtotal = $shoppingCart->subtotal - $product->price;
        $shoppingCart->total = $shoppingCart->subtotal + $shoppingCart->shipping_fees;

        $shoppingCart->shipping_fees_wo_offers = $shoppingCart->shipping_fees;
        $shoppingCart->subtotal_wo_offers = $shoppingCart->subtotal;
        $shoppingCart->total_wo_offers = $shoppingCart->total;
        $shoppingCart->save();
    }
}
