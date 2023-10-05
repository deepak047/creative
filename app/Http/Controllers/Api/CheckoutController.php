<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Models\Cart;

class CheckoutController extends Controller
{

    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     /**
     * Saves order.
     *
     * @return \Illuminate\Http\Response
    */
    public function saveOrder()
    {
        $cart = Cart::getCart();

        if (!$cart) {
            abort(400);
        }
        try {

            $order =  Cart::orderCreate();

            if($order){

                $paymentData = [
                    'order_id' => $order->id,
                    'customer_email' => $order->user->email,
                    'amount' => $order->grand_total,
                ];
        
                $paymentResult = $this->paymentService->processPayment($paymentData);

                if($paymentResult == 'success'){

                    $order->update(['status' => 1]);

                    Cart::deActivateCart();

                    return response()->json([
                        'success' => true,
                        'message'   => 'Payment Initated successfully',
                        'order'   => new OrderResource($order),
                    ]);


                }else{


                }
                
            }else{

                return response()->json([
                    'error' => [
                        'message' => 'Cart Not Found. Please try again later.',
                        'code'    => $e->getCode()
                    ]
                ]);

            }
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'message' => 'We are experiencing technical difficulties. Please try again later.',
                    'code'    => $e->getCode()
                ]
            ]);
        }

        
    }
}
