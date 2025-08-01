<?php

namespace App\Http\Controllers;

use App\Exports\EmbroideryPrintReportExport;
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
        $orders = Order::with('garmentTypes')->get();

        return view('embroidery-print.create', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'garment_type' => 'required|string',
            'embroidery_print' => 'required|array|min:1',
            'embroidery_print.*.color' => 'required|string',
            'embroidery_print.*.factory' => 'nullable|string',
            'embroidery_print.*.send' => 'nullable',
            'embroidery_print.*.received' => 'nullable',
            'date' => 'required|date',
        ], [
            'order_id.required' => 'The style no field is required.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $exists = EmbroideryPrint::where('order_id', $request->order_id)
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

        // Create report
        EmbroideryPrint::create([
            'order_id' => $request->order_id,
            'emb_or_print' => $request->embroidery_print,
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
    public function show($embroidery_print)
    {

        $embroideryPrint = EmbroideryPrint::with('order')->findOrFail($embroidery_print);

        return view('embroidery-print.show', compact('embroideryPrint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($embroidery_print)
    {
        $embroideryPrint = EmbroideryPrint::findOrFail($embroidery_print);
        $orders = Order::with(['garmentTypes'])->get();
        
        return view('embroidery-print.edit', compact('embroideryPrint', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $embroidery_print)
    {
        $embroideryPrint = EmbroideryPrint::findOrFail($embroidery_print);

         $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'garment_type' => 'required|string',
            'embroidery_print' => 'required|array|min:1',
            'embroidery_print.*.color' => 'required|string',
            'embroidery_print.*.factory' => 'nullable|string',
            'embroidery_print.*.send' => 'nullable',
            'embroidery_print.*.received' => 'nullable',
            'date' => 'required|date',
        ], [
            'order_id.required' => 'The style no field is required.',
        ]);

        $validator->after(function ($validator) use ($request, $embroideryPrint) {
            $exists = EmbroideryPrint::where('order_id', $request->order_id)
                ->where('garment_type', $request->garment_type)
                ->where('date', $request->date)
                ->where('id', '!=', $embroideryPrint->id)
                ->exists();
            if ($exists) {
                $validator->errors()->add('date', 'A report for this style, garment type, and date already exists.');
            }
        });;

        // error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Compare the array directly
        $orderChanged = $embroideryPrint->order_id != $request->order_id;
        $embroideryPrintChanged = $embroideryPrint->emb_or_print != $request->embroidery_print;
        $dateChanged = $embroideryPrint->date != $request->date;
        $garmentTypeChanged = $embroideryPrint->garment_type != $request->garment_type;

        if (!$orderChanged && !$embroideryPrintChanged && !$dateChanged && !$garmentTypeChanged) {
            return response()->json([
                'status' => false,
                'message' => 'Nothing to update.',
            ]);
        }

        // Update report
        $embroideryPrint->update([
            'order_id' => $request->order_id,
            'emb_or_print' => $request->embroidery_print,
            'garment_type' => $request->garment_type,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Embroidery/Print Report update successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($embroidery_print)
    {
        $embroideryPrint = EmbroideryPrint::findOrFail($embroidery_print);

        $embroideryPrint->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Embroidery/Print report deleted successfully.'
            ]);
        }

        return redirect()->route('embroidery_prints.index')->with('success', 'Embroidery/Print report deleted successfully');
    }

    // Excel download
    public function export($embroidery_print)
    {
        $embroideryPrint = EmbroideryPrint::with('order')->findOrFail($embroidery_print);
        $fileName = 'embroidery_print_report_' . $embroideryPrint->order->style_no . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new EmbroideryPrintReportExport($embroideryPrint), $fileName);
    }
}
