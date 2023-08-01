<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Items::with(['createdBy', 'site'])
            ->where('is_active', 1)
            ->where('quantity', '<>', 0)
            ->get();

        foreach ($items as $item) {
            $requestedQuantity = Inventory::where('item_id', $item->id)->sum('quantity_approved');
            $item->remaining_quantity = max(0, $item->quantity - $requestedQuantity);
        }

        return response()->json(['items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_name' => 'required|max:255',
            'quantity' => 'required',
            'original_quantity' => 'required',
            'type' => 'required',
            'cost' => 'required',
            'total_cost' => 'required',
            'site_id' => 'required|exists:sites,id',
            'budget_code' => 'required',
            'category' => 'required',
            'date_expiry' => 'nullable',
            'is_active' => 'nullable|boolean',
            'created_by' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $items = new Items();
        $items->fill($request->all());
        $items->save();
        $items->item_less_id = $items->id;
        $items->save();

        return response()->json([
            'items' => $items,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
