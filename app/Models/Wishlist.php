<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    public function user(){
        return $this->BelongsTo(User::class);
    }

    public function product(){
        return $this->BelongsTo(Product::class);
    }
}
