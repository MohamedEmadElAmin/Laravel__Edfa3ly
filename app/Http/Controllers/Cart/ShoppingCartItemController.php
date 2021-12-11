<?php

namespace App\Http\Controllers\Cart;

use App\Helpers\CartHelper;
use App\Http\Controllers\BaseController\CustomBaseController;
use App\Models\Cart\ShoppingCart;
use App\Models\Cart\ShoppingCartItem;
use App\Models\Offers\Offer;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;

class ShoppingCartItemController extends CustomBaseController
{
    public function addItemToCart(Request $request): JsonResponse
    {
        $result = $this->itemCheck($request);
        if(isset($result['errorMessage']))
            return $this->sendResponse(['message' => $result['errorMessage'], 'data' => $result['error']]);

        $shoppingCart = $result['shoppingCart'];
        $product = $result['product'];

        $result = CartHelper::addProductToCart(['shoppingCart' => $shoppingCart, 'product' => $product]);
        return $this->sendResponse(['message' => $result['message'], 'error' => $result['error']]);
    }

    public function removeItemFromCart(Request $request): JsonResponse
    {
        $result = $this->itemCheck($request);
        if(isset($result['errorMessage']))
            return $this->sendResponse(['message' => $result['errorMessage'], 'data' => $result['error']]);

        $shoppingCart = $result['shoppingCart'];
        $product = $result['product'];

        $result = CartHelper::removeProductForCart(['shoppingCart' => $shoppingCart, 'product' => $product]);
        return $this->sendResponse(['message' => $result['message'], 'error' => $result['error']]);
    }

    public function getItemsInCart(Request $request): JsonResponse
    {
        $shoppingCart = ShoppingCart::where(['user_id' => $request->user()->id])->first();
        if(!$shoppingCart)
            return $this->sendResponse(['message' => 'Please logout then login again.']);

        $shoppingCartItems = ShoppingCartItem::where(['shopping_cart_id' => $shoppingCart->id])
            ->select(['quantity','product_id'])
            ->with('product')->get();
        return $this->sendResponse(['data' => ['items' => $shoppingCartItems , 'count' => count($shoppingCartItems)]]);
    }






    private function itemCheck(Request $request): array
    {
        $validator = Validator::make($request->all(), ['name' => 'required|string|min:2|max:100']);
        if($validator->fails())  //Guard
            return ['errorMessage' => 'Validation Error.' , 'error' => $validator->errors()];

        $input = $request->all();
        $product = Product::where('name' , $input['name'])->first();
        if(!$product)
            return ['errorMessage' => 'Please Enter Valid name.' , 'error' => NULL];

        $shoppingCart = ShoppingCart::where(['user_id' => $request->user()->id])->first();
        if(!$shoppingCart)
            return ['errorMessage' => 'Please logout then login again.' , 'error' => NULL ];

        return ['product' => $product , 'shoppingCart' => $shoppingCart];
    }
}
