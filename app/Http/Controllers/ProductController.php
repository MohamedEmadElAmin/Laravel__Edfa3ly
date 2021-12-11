<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController\CustomBaseController;
use App\Http\Resources\Product\ProductCollection;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends CustomBaseController
{
    public function index(): Response
    {
        $products = new ProductCollection(Product::all());

        $result = ['count' => count($products), 'products' => $products];
        return $this->sendResponse(['data' => $result]);
    }
}
