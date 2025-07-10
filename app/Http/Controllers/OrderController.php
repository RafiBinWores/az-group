<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::latest()->get();
        // Pass to Blade
        return view('orders.view', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'buyer_name' => 'required|string',
            'style_no' => 'required|string',
            'order_quantity' => 'required|numeric',
        ]);

        // error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Create Order
        Order::create([
            'user_id' => $request->user_id,
            'buyer_name' => $request->buyer_name,
            'style_no' => $request->style_no,
            'order_qty' => $request->order_quantity,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User added successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($order)
    {
        $order = Order::findOrFail($order);

        Gate::authorize('update', $order);

        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $order)
    {
        $order = Order::findOrFail($order);

        Gate::authorize('update', $order);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'buyer_name' => 'required|string',
            'style_no' => 'required|string',
            'order_quantity' => 'required|numeric',
        ]);

        // error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Create user
        $order->update([
            'user_id' => $request->user_id,
            'buyer_name' => $request->buyer_name,
            'style_no' => $request->style_no,
            'order_qty' => $request->order_quantity,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Order update successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);

        Gate::authorize('delete', $order);

        $order->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Order deleted successfully.'
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }
}
