<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requestItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'site_id' => 'required',
            'quantity_approved' => 'required',
            'requested_by' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $inventory = new Inventory();
        $inventory->fill($request->all());
        $inventory->status = 'Pending';
        $inventory->transaction_type = 'Site Request';
        $inventory->save();
        $inventory->transaction_no = $inventory->id;
        $inventory->inventory_id = $inventory->id;
        $inventory->save();

        $requestedItem = Items::find($request->item_id);
        $requestedItem->quantity -= $request->quantity_approved;
        $requestedItem->save();

        return response()->json([
            'Request' => $inventory,
        ]);
    }

    public function approvedItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'approved_by' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $inventory = Inventory::find($id);
        $inventory->fill($request->all());
        $inventory->save();

        return new ClassesResource($class);
    }

    public function index()
    {
        $inventory = Inventory::with([
            'site',
            'item',
            'releasedBy',
            'approvedBy',
            'deniedBy',
            'receivedBy',
            'processedBy',
            'requestedBy',
        ])
        ->where('status', 'Pending')
        ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function approved()
    {
        $inventory = Inventory::with([
            'site',
            'item',
            'releasedBy',
            'approvedBy',
            'deniedBy',
            'receivedBy',
            'processedBy',
            'requestedBy',
        ])
        ->where('status', 'Approved')
        ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function denied()
    {
        $inventory = Inventory::with([
            'site',
            'item',
            'releasedBy',
            'approvedBy',
            'deniedBy',
            'receivedBy',
            'processedBy',
            'requestedBy',
        ])
        ->where('status', 'Denied')
        ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function allstatus()
    {
        $inventory = Inventory::with([
            'site',
            'item',
            'releasedBy',
            'approvedBy',
            'deniedBy',
            'receivedBy',
            'processedBy',
            'requestedBy',
        ])
        ->get();

        return response()->json(['inventory' => $inventory]);
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
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\c $c
     *
     * @return \Illuminate\Http\Response
     */
    public function show(c $c)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\c $c
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(c $c)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\c $c
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, c $c)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\c $c
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(c $c)
    {
    }
}
