<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Http\Resources\CartResource;


class CartController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
     * Get customer cart.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
    
        $cart = Cart::getCart();

        return response()->json([
            'data' => $cart ? new CartResource($cart) : null,
        ]);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param int $id
     *
     */
    public function store(Request $request, string $id)
    {
       
        try {

            $result = Cart::addProduct($id);

            if (is_array($result) && isset($result['warning'])) {
                return response()->json([
                    'error' => $result['warning'],
                ], 400);
            }

            $cart = Cart::getCart();

            return response()->json([
                'message' => 'Product added to cart successfully.',
                'data'    => $cart ? new CartResource($cart) : null,
            ]);
        } catch (Exception $e) {

            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                    'code'    => $e->getCode()
                ]
            ]);
        }
    }

     /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {

        $cart = Cart::deActivateCart();

        if($cart){
            return response()->json([
                'message' => 'Cart removed successfully.',
            ]);
        }else{
            return response()->json([
                'message' => 'Cart Not Found.',
            ]);
        }

       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyItem($id)
    {
       
        $cart = Cart::removeItem($id);

        if($cart){
            return response()->json([
                'message' => 'Cart Item removed successfully.',
            ]);
        }else{
            return response()->json([
                'message' => 'Cart Item Not Found.',
            ]);
        }


       
    }
}
