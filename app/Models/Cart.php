<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user(){
        return $this->BelongsTo(User::class);
    }

    /**
     * Add Items in a cart with some cart and item details.
     *
     * @param int   $productId
     */
    public static function addProduct($productId)
    {

        $cart = null;

        if(auth()->check()) {
            $cart = Cart::where([
                'user_id' => auth()->user()->id,
                'is_active'   => 1,
            ])->first();

        }

        if(!isset($cart)){

            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->is_active = 1;
            $cart->items_count = 1;
            $cart->save();

        }

        if (! $cart) {
            return ['warning' => 'Error while creating cart'];
        }

        $product = Product::find($productId);

        if (! $product) {
            return ['warning' => 'Product does not exist!'];
        }

        // Get the cart items.
        $cartItems = $cart->cartItems();

        // return ($cartItems->count());

        if ($cartItems->count() <= 0) {
            // Create a new cart item object.
            $cartItem = [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'base_price' => $product->price,
                'quantity' => 1,
                'total' => $product->price,
            ];

             // Add the cart item object to the cart.
             $cart->cartItems()->create($cartItem);

        }else{

         // Filter the cart items by the product ID.
        $filteredCartItems = $cartItems->where('product_id',$product->id)->first();

            // Check if the product already exists in the cart items.
            if (isset($filteredCartItems)) {
                // The product already exists in the cart items.
                $filteredCartItems->quantity++;
                $filteredCartItems->total = $filteredCartItems->price * $filteredCartItems->quantity;
                $filteredCartItems->save();

            }else{
                // The product does not exist in the cart items.
                // Create a new cart item object.
                $cartItem = [
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'base_price' => $product->price,
                    'quantity' => 1,
                    'total' => $product->price,
                ];

                // Add the cart item object to the cart.
                $cart->cartItems()->create($cartItem);
            }          

        }

       
       $cart->items_count = $cart->cartItems()->count();
       $cart->sub_total = $cart->cartItems()->sum('total');
       $cart->grand_total = $cart->cartItems()->sum('total');
       $cart->save();

        return $cart;
    }

   

     /**
     * Returns cart
     *
     */
    public static function getCart()
    {
        $cart = null;

        if(auth()->check()) {
            $cart = Cart::where([
                'user_id' => auth()->user()->id,
                'is_active'   => 1,
            ])->first();

        }

        if(!isset($cart)){

            $cart = new Cart;

        }

        return $cart;
    }

     /**
     * Deactivates current cart
     *
     * @return void
     */
    public static function deActivateCart()
    {
        $cart = null;

        if(auth()->check()) {
            $cart = Cart::where([
                'user_id' => auth()->user()->id,
                'is_active'   => 1,
            ])->first();
        }

        if(isset($cart)){

            $cart->update(['is_active' => 0]);

            return true;

        }
      
        return false;
        
    }

    /**
     * Remove the item from the cart
     *
     * @param  int  $itemId
     * @return boolean
     */
    public static function removeItem($itemId)
    {
    
        if(auth()->check()) {
            $cart = Cart::where([
                'user_id' => auth()->user()->id,
                'is_active'   => 1,
            ])->first();
        }

        if (!$cart) {
            return false;
        }

         // Get the cart items.
         $cartItems = $cart->cartItems();

           $deleteCartItems = $cartItems->where('product_id',$itemId)->first();

           if (isset($deleteCartItems)) {
        
               $deleteCartItems->delete();

           }else{

                return false;
           }  


        if ($cart->cartItems()->count() == 0) {

            $cart->update(['is_active' => 0]);

            return true;

        }

       $cart->items_count = $cart->cartItems()->count();
       $cart->sub_total = $cart->cartItems()->sum('total');
       $cart->grand_total = $cart->cartItems()->sum('total');
       $cart->save();

       return true;
    }

    /**
     * Move a wishlist item to cart
     * @return bool
     */
    public static function moveToCart($wishlistItem)
    {

        $result = self::addProduct($wishlistItem->product_id);

        if ($result) {
            $wishlistItem->delete();
            return true;
        }

        return false;
    }

     /**
     * create order from current cart
     *
     * @return void
     */
    public static function orderCreate()
    {
        $cart = null;

        if(auth()->check()) {
            $cart = Cart::where([
                'user_id' => auth()->user()->id,
                'is_active'   => 1,
            ])->first();
        }

        if(isset($cart)){

            $order = new Order;
            $order->user_id = auth()->user()->id;
            $order->status = 0;
            $order->total_item_count  = $cart->items_count;
            $order->total_qty_ordered = $cart->cartItems()->sum('quantity');
            $order->grand_total       = $cart->grand_total;
            $order->sub_total         = $cart->sub_total;
            $order->save();

            return $order;

        }
      
        return false;
        
    }
}
