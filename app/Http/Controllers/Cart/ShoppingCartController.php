<?php

namespace App\Http\Controllers\Cart;

use App\Helpers\CartHelper;
use App\Helpers\CartOffersHelper;
use App\Http\Controllers\BaseController\CustomBaseController;
use App\Models\Cart\ShoppingCart;
use App\Models\Cart\ShoppingCartItem;
use App\Models\Cart\ShoppingCartOffers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShoppingCartController extends CustomBaseController
{
    public function getCustomerCart(Request $request): JsonResponse
    {
        $shoppingCart = ShoppingCart::where(['user_id' => $request->user()->id])->first();
        $shoppingCartItems = ShoppingCartItem::where(['shopping_cart_id' => $shoppingCart->id])
            ->select(['quantity','original_price', 'discount_price', 'original_shipping_fees', 'product_id' ,'id'])
            ->with('product')->get();


        DB::beginTransaction();
        CartOffersHelper::addAvailableOffersToCart($shoppingCart, $shoppingCartItems);
        CartOffersHelper::applyDiscountOnCart($shoppingCart, $shoppingCartItems);
        CartHelper::calculateForInvoice($shoppingCart);
        DB::commit();

        $shoppingCartItems = DB::table('shopping_cart_items')
            ->where('shopping_cart_id' , $shoppingCart->id)
            ->join('products', 'shopping_cart_items.product_id', '=', 'products.id')
            ->select('products.name','shopping_cart_items.quantity','shopping_cart_items.discount_price', 'shopping_cart_items.original_price'
                , 'shopping_cart_items.original_shipping_fees')
            ->get();
        $shoppingCartActiveOffers = DB::table('shopping_cart_offers')
            ->where('shopping_cart_id' , $shoppingCart->id)
            ->join('offers', 'shopping_cart_offers.offer_id', '=', 'offers.id')
            ->select('offers.name')
            ->get();

        $successData = ['cart' => $shoppingCart , 'shoppingCartActiveOffers' => $shoppingCartActiveOffers ,'items' => $shoppingCartItems];
        return $this->sendResponse(['data' => $successData]);
    }

}
