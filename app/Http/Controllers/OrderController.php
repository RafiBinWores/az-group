<?php

namespace App\Http\Controllers;

use App\Exports\OrderReportExport;
use App\Models\GarmentType;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

use function Pest\Laravel\get;

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
        $types = GarmentType::where('status', 1)->latest()->get();

        return view('orders.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'buyer_name' => 'required|string',
            'style_no' => 'required|string|unique:orders,style_no',
            'garment_types' => 'required|array|min:1',
            'garment_types.*' => 'exists:garment_types,id',
            'order_quantity' => 'required|numeric',
            'color_qty' => 'required|array|min:1',
            'color_qty.*.color' => 'required|string',
            'color_qty.*.qty' => 'required',
        ], [
            'garment_types.required'    => 'The garment type field is required.',
            'garment_types.*.exists'    => 'One or more selected garment types are invalid.',
        ]);

        // error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Create Order
        $order = Order::create([
            'user_id' => $request->user_id,
            'buyer_name' => $request->buyer_name,
            'style_no' => $request->style_no,
            'order_qty' => $request->order_quantity,
            'color_qty' => $request->color_qty,
        ]);

        $order->garmentTypes()->sync($request->garment_types);

        return response()->json([
            'status' => true,
            'message' => 'Order added successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($order)
    {
        $order = Order::findOrFail($order);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($order)
    {
        $order = Order::findOrFail($order);
        $types = GarmentType::where('status', 1)->latest()->get();

        Gate::authorize('update', $order);

        return view('orders.edit', compact('order', 'types'));
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
            'style_no' => 'required|string|unique:orders,style_no,' . $order->style_no . ',style_no',
            'garment_types' => 'required|array|min:1',
            'garment_types.*' => 'exists:garment_types,id',
            'order_quantity' => 'required|numeric',
            'color_qty' => 'nullable|array|min:1',
            'color_qty.*.color' => 'nullable|string',
            'color_qty.*.qty' => 'nullable',
        ], [
            'garment_types.required'    => 'The garment type field is required.',
            'garment_types.*.exists'    => 'One or more selected garment types are invalid.',
        ]);

        // error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Check if anything has actually changed
        $userChanged = $order->user_id != $request->user_id;
        $buyerChanged = $order->buyer_name !== $request->buyer_name;
        $styleChanged = $order->style_no !== $request->style_no;
        $qtyChanged = $order->order_qty != $request->order_quantity;
        $colorChanged = $order->color_qty != $request->color_qty;

        if (!$userChanged && !$buyerChanged && !$styleChanged && !$qtyChanged && !$colorChanged) {
            return response()->json([
                'status' => false,
                'message' => 'Nothing to update.',
            ]);
        }

        // Create user
        $order->update([
            'user_id' => $request->user_id,
            'buyer_name' => $request->buyer_name,
            'style_no' => $request->style_no,
            'order_qty' => $request->order_quantity,
            'color_qty' => $request->color_qty,
        ]);

        $order->garmentTypes()->sync($request->garment_types);

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

    // Excel download
    public function export($order)
    {
        $order = Order::findOrFail($order);
        $filename = 'order_' . $order->style_no . '_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new OrderReportExport($order), $filename);
    }
}
