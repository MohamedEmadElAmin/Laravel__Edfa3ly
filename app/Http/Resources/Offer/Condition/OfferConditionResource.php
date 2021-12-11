<?php

namespace App\Http\Resources\Offer\Condition;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferConditionResource extends JsonResource
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
            'min_bought_items' => $this->resource->min_bought_items,
            'bought_product_id' => $this->bought_product_id,
            'bought_category_id' => $this->bought_category_id, //reference to another resource
        ];
    }
}
