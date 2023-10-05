<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'items_count'                        => $this->items_count,
            'grand_total'                        => $this->grand_total,
            'base_sub_total'                     => $this->sub_total,
            'checkout_method'                    => $this->checkout_method,
            'is_active'                          => $this->is_active,
            'items'                              => CartItemResource::collection($this->cartItems),
            'created_at'                         => $this->created_at,
            'updated_at'                         => $this->updated_at,

        ];
    }
}
