<?php

namespace App\Http\Controllers\Offers;

use App\Http\Controllers\BaseController\CustomBaseController;
use App\Http\Resources\Offer\OfferCollection;
use App\Models\Offers\Offer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OffersController extends CustomBaseController
{
    public function index(): Response
    {
        $offers = new OfferCollection(Offer::with(['condition' , 'discount'])->get());

        $result = ['count' => count($offers), 'offers' => $offers];
        return $this->sendResponse(['data' => $result]);
    }
}
