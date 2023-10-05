<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                                 => $this->id,
            'customer_email'                     => $this->user->email,
            'customer_first_name'                => $this->user->first_name,
            'product_id'                         => $this->product->id,
            'product_name'                       => $this->product->product_name,
            'product_price'                      => $this->product->price,
            'created_at'                         => $this->created_at,
            'updated_at'                         => $this->updated_at,
            
        ];
    }
}
