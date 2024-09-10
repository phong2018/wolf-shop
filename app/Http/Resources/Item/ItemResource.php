<?php

declare(strict_types=1);

namespace App\Http\Resources\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed[] array
     */
    public function toArray($request)
    {
        $result = $this->resource->only([
            'id',
            'name',
            'sell_in',
            'quality',
            'img_url',
            'data',
        ]);

        return $result;
    }
}
