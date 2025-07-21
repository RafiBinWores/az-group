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
        $cuttings = Cutting::with('order')->latest()->get();

        return view('cutting.view', compact('cuttings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::with('garmentTypes')->latest()->get();

        return view('cutting.create', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'garment_type' => 'required|exists:garment_types,name',
            'date' => 'required|date',
            'cutting' => 'required|array|min:1',
            'cutting.*.color' => 'nullable|string',
            'cutting.*.qty' => 'nullable',
        ], [
            'order_id.required' => 'The style no field is required.',
        ]);

        // Add custom validation logic before checking fails
        $validator->after(function ($validator) use ($request) {
            $exists = Cutting::where('order_id', $request->order_id)
                ->where('garment_type', $request->garment_type)
                ->where('date', $request->date)
                ->exists();
            if ($exists) {
                $validator->errors()->add('date', 'A report for this style, garment type, and date already exists.');
            }
        });

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
            'date' => $request->date,
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
        $cutting = Cutting::with('order.garmentTypes')->findOrFail($cutting);

        return view('cutting.show', compact('cutting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cutting)
    {
        $cutting = Cutting::findOrFail($cutting);
        $orders = Order::with(['garmentTypes'])->latest()->get();
        
        return view('cutting.edit', compact('cutting', 'orders'));
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
            'date' => 'required|date',
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
        $dateChanged = $cutting->date != $request->date;
        $typeChanged = $cutting->garment_type != $request->garment_type;

        if (!$orderChanged && !$cuttingChanged && !$typeChanged && !$dateChanged) {
            return response()->json([
                'status' => false,
                'message' => 'Nothing to update.',
            ]);
        }

        // Create Cutting report
        $cutting->update([
            'order_id' => $request->order_id,
            'garment_type' => $request->garment_type,
            'date' => $request->date,
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
