<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Cart;
use App\Http\Resources\CartResource;
use App\Http\Resources\WishlistResource;


class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all of the wishlists for the logged in user.
        $wishlists = Wishlist::where('user_id', auth()->user()->id)->get();

        // Return the wishlists as a JSON response.
        return response()->json([
            'data' => $wishlists ?  WishlistResource::collection($wishlists) : null,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

     /**
     * Delete's a individual resource.
     */
    public function destroy($id)
    {
        $wishlistProduct = Wishlist::where('user_id',auth()->user()->id)->where('product_id',$id)->first();

           if (isset($wishlistProduct)) {
        
               $wishlistProduct->delete();

               return response()->json([
                'message' => 'Item removed from wishlist successfully.',
            ]);

           }else{

            return response()->json([
                'message' => 'Item Not Found',
            ]);
           } 

    }

     /**
     * Function to add item to the wishlist.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {

        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'data'    => null,
                'error' => 'Product does not exist!',
            ]);
        }

        $user = auth()->user();

        $wishlist = new Wishlist;
        $wishlist->user_id = auth()->user()->id;
        $wishlist->product_id = $id;
        $wishlist->save();

        return response()->json([
            'data'    => new WishlistResource($wishlist),
            'message' => 'Product added to wishlist',
        ]);
       
    }

    /**
     * Move product from wishlist to cart.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function moveToCart($id)
    {
        $wishlistItem = Wishlist::where('user_id',auth()->user()->id)->where('product_id',$id)->first();

        if (isset($wishlistItem)) {

        $result = Cart::moveToCart($wishlistItem);

        if ($result) {

            $cart = Cart::getCart();

            return response()->json([
                'data' => $cart ? new CartResource($cart) : null,
                'message' => 'Product moved to cart successfully.',
            ]);
        } else {
            return response()->json([
                'data' => -1,
                'error' => 'Wislist Options Missing',
            ], 400);
        }
      }else{

        return response()->json([
            'error' => 'Wishlist Product does not exist!',
        ]);

      } 

     
    }
}
