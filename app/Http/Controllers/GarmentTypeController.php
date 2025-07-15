<?php

namespace App\Http\Controllers;

use App\Models\GarmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GarmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $garmentTypes = GarmentType::all();

        return view('garment-types.view', compact('garmentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('garment-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|unique:garment_types,name',
            'status' => 'required|boolean',
        ]);

        // error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Create Garment type
        GarmentType::create([
            'name' => trim($request->type),
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Garment types added successfully.',
        ]);
    }

    /**
     * update the status.
     */
    public function updateStatus(Request $request)
    {
        $type = GarmentType::findOrFail($request->id);
        $type->status = $request->status ? 1 : 0;
        $type->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
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
    public function edit($garment_type)
    {
        $garmentType = GarmentType::findOrFail($garment_type);

        return view('garment-types.edit', compact('garmentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $garment_type)
    {
        $type = GarmentType::findOrFail($garment_type);

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|unique:garment_types,name,' . $type->name . ',name',
            'status' => 'required|boolean',
        ]);

        // error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $typeChanged = $type->name !== $request->type;
        $statusChanged = $type->status !== (int)$request->status;

        if (!$typeChanged && !$statusChanged) {
            return response()->json([
                'status' => false,
                'message' => 'Nothing to update.',
            ]);
        }
        // Create Garment type
        $type->update([
            'name' => trim($request->type),
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Garment types added successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($garment_type)
    {
        $type = GarmentType::findOrFail($garment_type);

        $type->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Garment types deleted successfully.'
            ]);
        }

        return redirect()->route('garment_types.index')->with('success', 'Garment types deleted successfully');
    }
}
