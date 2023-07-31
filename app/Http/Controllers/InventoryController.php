<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\c  $c
     * @return \Illuminate\Http\Response
     */
    public function show(c $c)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\c  $c
     * @return \Illuminate\Http\Response
     */
    public function edit(c $c)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\c  $c
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, c $c)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\c  $c
     * @return \Illuminate\Http\Response
     */
    public function destroy(c $c)
    {
        //
    }
    public function requestItem(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::find($request->input('item_id'));

        if (!$item) {
            return response()->json(['message' => 'Item not found.'], 404);
        }

        $requestedQuantity = $request->input('quantity');
        $remainingQuantity = max(0, $item->quantity - $requestedQuantity);

        if ($remainingQuantity === 0) {
            return response()->json(['message' => 'Item quantity is not sufficient.'], 400);
        }

        // Store the inventory request
        $inventory = new Inventory([
            'item_id' => $item->id,
            'quantity_approved' => $requestedQuantity,
            'status' => 'Pending', // Set the initial status as 'Pending'
            // Add other relevant data like 'requested_by', 'date_requested', etc.
        ]);

        $inventory->save();

        return response()->json(['message' => 'Item request submitted successfully.'], 200);
    }
}
