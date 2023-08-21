<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\AwardPremium;
use App\Models\Inventory;
use App\Models\Items;
use App\Models\SiteInventory;
use Carbon\Carbon;
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
        $inventory->original_request = $inventory->quantity_approved;
        $inventory->transaction_no = $inventory->id;
        $inventory->inventory_id = $inventory->id;
        $inventory->date_requested = Carbon::now()->format('Y-m-d H:i');
        $inventory->save();

        $requestedItem = Items::find($request->item_id);
        $requestedItem->quantity -= $request->quantity_approved;
        $requestedItem->total_cost -= $request->quantity_approved * $requestedItem->cost;
        $requestedItem->save();

        return response()->json([
            'Request' => $inventory,
        ]);
    }

    public function awardNormalItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inventory_item_id' => 'required',
            'site_id' => 'required',
            'awarded_quantity' => 'required',
            'awardee_name' => 'required',
            'awardee_hrid' => 'required',
            'processed_by' => 'required',
            'released_by' => 'required',
            'remarks' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $award = new Award();
        $award->fill($request->all());
        $award->save();
        $award->award_status = 'Awarded';
        $award->date_released = Carbon::now()->format('Y-m-d H:i');
        $award->save();

        $requestedItem = SiteInventory::find($request->inventory_item_id);
        $requestedItem->quantity -= $request->awarded_quantity;
        $requestedItem->save();

        return response()->json([
            'Award' => $award,
        ]);
    }

    public function awardPremiumItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'site_id' => 'required',
            'awarded_quantity' => 'required',
            'awardee_name' => 'required',
            'awardee_hrid' => 'required',
            'processed_by' => 'required',
            'released_by' => 'required',
            'remarks' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $award = new AwardPremium();
        $award->fill($request->all());
        $award->save();
        $award->award_status = 'Awarded';
        $award->date_released = Carbon::now()->format('Y-m-d H:i');
        $award->save();

        $requestedItem = Items::find($request->item_id);
        $requestedItem->quantity -= $request->awarded_quantity;
        $requestedItem->save();

        return response()->json([
            'Award' => $award,
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
        $inventory->status = 'Approved';
        $inventory->date_approved = Carbon::now()->format('Y-m-d H:i');
        $inventory->save();

        return response()->json([
            'Request' => $inventory,
        ]);
    }

    public function receivedItem(Request $request, $id)
    {
        $inventory = Inventory::find($id);

        $validator = Validator::make($request->all(), [
            'received_by' => 'required',
            'received_status' => 'required',
            'received_quantity' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if ($request->input('received_status') === 'partial') {
            $inventory->approved_status = null;
            $inventory->received_quantity = $request->input('received_quantity');
        } elseif ($request->input('received_status') === 'complete') {
            $inventory->approved_status = 'Received';
            $inventory->received_quantity = $inventory->quantity_approved;
        }

        $inventory->fill([
            'received_by' => $request->input('received_by'),
            'received_status' => $request->input('received_status'),
        ]);
        $inventory->date_received = Carbon::now()->format('Y-m-d H:i');
        $inventory->save();

        $totalCost = $inventory->item->cost * $inventory->received_quantity;

        $siteInventory = SiteInventory::where('item_name', $inventory->item->item_name)
            ->where('budget_code', $inventory->item->budget_code)
            ->first();

        if ($siteInventory) {
            $siteInventory->quantity += $inventory->received_quantity;
            $siteInventory->original_quantity += $inventory->received_quantity;
            $siteInventory->total_cost += $totalCost;
            $siteInventory->save();
        } else {
            $siteInventory = new SiteInventory();
            $siteInventory->item_less_id = $inventory->item->id;
            $siteInventory->item_name = $inventory->item->item_name;
            $siteInventory->quantity = $inventory->received_quantity;
            $siteInventory->original_quantity = $inventory->received_quantity;
            $siteInventory->budget_code = $inventory->item->budget_code;
            $siteInventory->type = $inventory->item->type;
            $siteInventory->category = $inventory->item->category;
            $siteInventory->date_expiry = $inventory->item->date_expiry;
            $siteInventory->site_id = $inventory->site_id;
            $siteInventory->is_active = $inventory->item->is_active;
            $siteInventory->cost = $inventory->item->cost;
            $siteInventory->total_cost = $totalCost;

            $siteInventory->save();
        }
        $inventory->quantity_approved -= $inventory->received_quantity;
        $inventory->save();

        return response()->json([
            'Request' => $inventory,
        ]);
    }

    public function deniedItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'denied_by' => 'required',
            'denial_reason' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $inventory = Inventory::find($id);
        $inventory->fill($request->all());
        $inventory->status = 'Denied';
        $inventory->date_denied = Carbon::now()->format('Y-m-d H:i');
        $inventory->save();

        $requestedItem = Items::find($inventory->item_id);
        $requestedItem->quantity += $inventory->quantity_approved;
        $requestedItem->save();

        return response()->json([
            'Request' => $inventory,
        ]);
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

    public function approvedReceived()
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
            ->where('approved_status', 'Received')
            ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function approvedPending()
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
            ->whereNull('approved_status')
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
