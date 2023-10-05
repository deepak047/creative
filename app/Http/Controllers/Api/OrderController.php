<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Get all of the orders for the logged in user.
        $orders = Order::where('user_id', auth()->user()->id)->where('status', 1)->get();

        // Return the orders as a JSON response.
        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        // Get the order with the specified ID.
        $order = Order::find($id);

        // Check if the order exists.
        if (!$order) {
            return response()->json([
                'message' => 'Order not found.'
            ], 404);
        }

        // Check if the order belongs to the logged in user.
        if ($order->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        // Return the order as a JSON response.
        return response()->json($order);
    }

    public function store(Request $request)
    {
        // Validate the request.
        $request->validate([
            'total_qty_ordered' => 'required|integer|min:1',
        ]);

        // Create a new order.
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->payment_method = $request->input('payment_method');
        $order->total_item_count = $request->input('total_item_count');
        $order->total_qty_ordered = $request->input('total_qty_ordered');
        $order->grand_total = $request->input('grand_total');
        $order->sub_total = $request->input('sub_total');
        $order->status = 0;

        // Save the order.
        $order->save();

        // Return the order as a JSON response.
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        // Get the order with the specified ID.
        $order = Order::find($id);

        // Check if the order exists.
        if (!$order) {
            return response()->json([
                'message' => 'Order not found.'
            ], 404);
        }
        // Check if the order belongs to the logged in user.
        if ($order->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        // Update the order.
        $order->total_qty_ordered = $request->input('total_qty_ordered');

        // Save the order.
        $order->save();

        // Return the order as a JSON response.
        return response()->json($order);
    }

    public function destroy(Request $request, $id)
    {
        // Get the order with the specified ID.
        $order = Order::find($id);

        // Check if the order exists.
        if (!$order) {
            return response()->json([
                'message' => 'Order not found.'
            ], 404);
        }

        // Check if the order belongs to the logged in user.
        if ($order->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        // Delete the order.
        $order->delete();

        // Return a JSON response with a success message.
        return response()->json([
            'message' => 'Order deleted successfully.'
        ]);
    }
}

