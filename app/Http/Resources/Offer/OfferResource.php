<?php

namespace App\Http\Resources\Offer;

use App\Http\Resources\Offer\Condition\OfferConditionResource;
use App\Http\Resources\Offer\Discount\OfferDiscountResource;
use App\Models\Offers\OfferCondition;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'name' => $this->resource->name,
            'is_active' => $this->is_active,
            'condition' => OfferConditionResource::make($this->condition),
            'discount' => OfferDiscountResource::make($this->discount),
        ];
    }
}
