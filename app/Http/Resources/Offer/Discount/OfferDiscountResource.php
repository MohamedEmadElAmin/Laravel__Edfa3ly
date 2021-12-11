<?php

namespace App\Http\Resources\Offer\Discount;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferDiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'discount_type' => $this->resource->discount_type,
            'discount_value' => $this->discount_value,
            'discount_from' => $this->discount_from,
            'discount_product_id' => $this->discount_product_id,
        ];
    }
}
