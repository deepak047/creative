<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'items_count'                        => $this->total_item_count,
            'grand_total'                        => $this->grand_total,
            'base_sub_total'                     => $this->sub_total,
            'status'                             => $this->status,
            'created_at'                         => $this->created_at,
            'updated_at'                         => $this->updated_at,

        ];
    }
}
