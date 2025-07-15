<?php

namespace App\Http\Controllers;

use App\Models\EmbroideryPrint;
use App\Models\GarmentType;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EmbroideryPrintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $embroideryPrints = EmbroideryPrint::with('order')->get();

        return view('embroidery-print.view', compact('embroideryPrints'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::get(['id', 'style_no']);
        $types = GarmentType::get(['name']);

        return view('embroidery-print.create', compact('orders', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'embroidery_or_print' => 'required|array|min:1',
            'embroidery_or_print.*.color' => 'required|string',
            'embroidery_or_print.*.send' => 'required',
            'embroidery_or_print.*.receive' => 'nullable',
            'date' => 'required|date',
            'garment_type' => 'required|string',
        ], [
            'order_id.required' => 'The style no field is required.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $exists = EmbroideryPrint::where('order_id', $request->order_id)
                ->where('date', $request->date)
                ->exists();
            if ($exists) {
                $validator->errors()->add('date', 'A report for this style and date already exists.');
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
        EmbroideryPrint::create([
            'order_id' => $request->order_id,
            'emb_or_print' => $request->embroidery_or_print,
            'garment_type' => $request->garment_type,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Embroidery or Print added successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($cutting)
    {

        $cutting = EmbroideryPrint::with('order')->findOrFail($cutting);

        return view('embroidery-print.show', compact('cutting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cutting)
    {
        $cutting = EmbroideryPrint::with('order')->findOrFail($cutting);
        $orders = Order::get(['id', 'style_no']);

        return view('embroidery-print.edit', compact('cutting', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cutting)
    {
        $cutting = EmbroideryPrint::findOrFail($cutting);

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
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

        if (!$orderChanged && !$cuttingChanged) {
            return response()->json([
                'status' => false,
                'message' => 'Nothing to update.',
            ]);
        }

        // Create Cutting report
        $cutting->update([
            'order_id' => $request->order_id,
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
        $cutting = EmbroideryPrint::findOrFail($cutting);

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
        $cutting = EmbroideryPrint::with('order')->findOrFail($cutting);
        $filename = 'cutting_' . $cutting->order->style_no . '_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new CuttingExport($cutting), $filename);
    }
}
