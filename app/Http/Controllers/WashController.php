<?php

namespace App\Http\Controllers;

use App\Exports\WashReportExport;
use App\Models\GarmentType;
use App\Models\Order;
use App\Models\Wash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class WashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $washes = Wash::with('order')->get();

        return view('washes.view', compact('washes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::with('garmentTypes')->latest()->get();

        return view('washes.create', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'date' => 'required|date',
            'garment_type' => 'required|string',
            'wash' => 'required|array|min:1',
            'wash.*.color' => 'required|string',
            'wash.*.factory' => 'nullable|string',
            'wash.*.send' => 'nullable',
            'wash.*.receive' => 'nullable',
        ], [
            'order_id.required' => 'The style no field is required.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $exists = Wash::where('order_id', $request->order_id)
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
        Wash::create([
            'order_id' => $request->order_id,
            'wash' => $request->wash,
            'garment_type' => $request->garment_type,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Wash added successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($wash)
    {

        $wash = Wash::with('order')->findOrFail($wash);

        return view('washes.show', compact('wash'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($wash)
    {
        $wash = Wash::findOrFail($wash);
        $orders = Order::with(['garmentTypes'])->get();

        return view('washes.edit', compact('wash', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $wash)
    {
        $wash = Wash::findOrFail($wash);

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric',
            'date' => 'required|date',
            'garment_type' => 'required|string',
            'wash' => 'required|array|min:1',
            'wash.*.color' => 'required|string',
            'wash.*.factory' => 'nullable|string',
            'wash.*.send' => 'nullable',
            'wash.*.receive' => 'nullable',
        ], [
            'order_id.required' => 'The style no field is required.',
        ]);

        $validator->after(function ($validator) use ($request, $wash) {
            $exists = Wash::where('order_id', $request->order_id)
                ->where('garment_type', $request->garment_type)
                ->where('date', $request->date)
                ->where('id', '!=', $wash->id)
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
        $orderChanged = $wash->order_id != $request->order_id;
        $washChanged = $wash->wash != $request->wash;
        $dateChanged = $wash->date != $request->date;
        $garmentTypeChanged = $wash->garment_type != $request->garment_type;

        if (!$orderChanged && !$washChanged && !$dateChanged && !$garmentTypeChanged) {
            return response()->json([
                'status' => false,
                'message' => 'Nothing to update.',
            ]);
        }

        // Update report
        $wash->update([
            'order_id' => $request->order_id,
            'wash' => $request->wash,
            'garment_type' => $request->garment_type,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Wash Report update successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($wash)
    {
        $wash = Wash::findOrFail($wash);

        $wash->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Wash report deleted successfully.'
            ]);
        }

        return redirect()->route('washes.index')->with('success', 'Wash report deleted successfully');
    }

    // Excel download
    public function export($wash)
    {
        $wash = Wash::with('order')->findOrFail($wash);
        $fileName = 'wash_report_' . $wash->order->style_no . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new WashReportExport($wash), $fileName);
    }
}
