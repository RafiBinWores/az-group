<?php

namespace App\Http\Controllers;

use App\Exports\CuttingExport;
use App\Models\Cutting;
use App\Models\GarmentType;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CuttingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cuttings = Cutting::with('order')->get();

        return view('cutting.view', compact('cuttings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::get(['id', 'style_no']);
        $types = GarmentType::where('status', 1)
                ->orderBy('name', 'ASC')
                ->get(['name']);

        return view('cutting.create', compact('orders', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'garment_type' => 'required|string',
            'cutting' => 'required|array|min:1',
            'cutting.*.color' => 'required|string',
            'cutting.*.qty' => 'required',
        ], [
            'order_id.required' => 'The style no field is required.',
        ]);

        // error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Create Cutting report
        Cutting::create([
            'order_id' => $request->order_id,
            'garment_type' => $request->garment_type,
            'cutting' => $request->cutting,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cutting report added successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($cutting)
    {
        $cutting = Cutting::with('order')->findOrFail($cutting);

        return view('cutting.show', compact('cutting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cutting)
    {
        $cutting = Cutting::with('order')
                ->findOrFail($cutting);

        $orders = Order::get(['id', 'style_no']);

        $types = GarmentType::where('status', 1)
                ->orderBy('name', 'ASC')
                ->get(['name']);

        return view('cutting.edit', compact('cutting', 'orders', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cutting)
    {
        $cutting = Cutting::findOrFail($cutting);

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'garment_type' => 'required|string',
            'cutting' => 'required|array|min:1',
            'cutting.*.color' => 'required|string',
            'cutting.*.qty' => 'required',
        ], [
            'order_id.required' => 'The style no is required.',
        ]);

        // error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Compare the order_id and the cutting array directly
        $orderChanged = $cutting->order_id != $request->order_id;
        $cuttingChanged = $cutting->cutting != $request->cutting;
        $typeChanged = $cutting->garment_type != $request->garment_type;

        if (!$orderChanged && !$cuttingChanged && !$typeChanged) {
            return response()->json([
                'status' => false,
                'message' => 'Nothing to update.',
            ]);
        }

        // Create Cutting report
        $cutting->update([
            'order_id' => $request->order_id,
            'garment_type' => $request->garment_type,
            'cutting' => $request->cutting,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cutting Report update successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cutting)
    {
        $cutting = Cutting::findOrFail($cutting);

        $cutting->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Cutting report deleted successfully.'
            ]);
        }

        return redirect()->route('cutting.index')->with('success', 'Cutting report deleted successfully');
    }

    // Excel download
    public function export($cutting)
    {
        $cutting = Cutting::with('order')->findOrFail($cutting);
        $filename = 'cutting_' . $cutting->order->style_no . '_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new CuttingExport($cutting), $filename);
    }
}
