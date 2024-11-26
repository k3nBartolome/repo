<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\AwardPremium;
use App\Models\Inventory;
use App\Models\Items;
use App\Models\SiteInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'file_name' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $imagePath = $request->file('file_name')->store('storage', 'public');
        $inventory = new Inventory();
        $inventory->fill($request->all());
        $inventory->status = 'Pending';
        $inventory->file_path = $imagePath;
        $inventory->transaction_type = 'Site Request';
        $inventory->save();

        // Get the current inventory ID and format it as a transaction number.
        $formattedTransactionNumber = sprintf('%06d', $inventory->id);

        $inventory->transaction_no = $formattedTransactionNumber;
        $inventory->original_request = $inventory->quantity_approved;
        $inventory->inventory_id = $inventory->id;
        $inventory->date_requested = Carbon::now()->format('Y-m-d H:i');
        $inventory->save();

        $requestedItem = Items::find($request->item_id);
        $requestedItem->quantity -= $request->quantity_approved;
        $requestedItem->save();

        return response()->json([
            'Request' => $inventory,
        ]);
    }

    public function transferItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inventory_item_id' => 'required',
            'site_id' => 'required',
            'quantity_approved' => 'required',
            'transferred_by' => 'required',
            'transferred_from' => 'required',
            'transferred_to' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $inventory = new Inventory();
        $inventory->fill($request->all());
        $inventory->transaction_type = 'Sourcing Transfer Request';

        $inventory->save();
        $requestedItem = SiteInventory::find($request->inventory_item_id);
        $requestedItem->quantity -= $request->quantity_approved;
        $requestedItem->save();

        $inventory->item_id = $requestedItem->inventory_item_id;
        $inventory->original_request = $inventory->quantity_approved;
        $inventory->inventory_id = $inventory->id;
        $inventory->date_requested = Carbon::now()->format('Y-m-d H:i');
        $inventory->save();
        $formattedTransactionNumber = sprintf('%06d', $inventory->id);
        $inventory->transaction_no = $formattedTransactionNumber;
        $inventory->save();

        return response()->json([
            'Request' => $inventory,
        ]);
    }

    public function transferRemxItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'item_id' => 'required',
        'site_id' => 'required',
        'quantity_approved' => 'required',
        'transferred_by' => 'required',
        'transferred_from' => 'required',
        'transferred_to' => 'required',
    ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Find the requested item first
        $requestedItem = Items::find($request->item_id);
        if (!$requestedItem) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        // Reduce the quantity of the requested item
        $requestedItem->quantity -= $request->quantity_approved;
        $requestedItem->save();

        // Create and fill the inventory object
        $inventory = new Inventory();
        $inventory->fill($request->all());
        $inventory->item_id = $requestedItem->id;  // Use id, as 'item_id' may not exist
        $inventory->transaction_type = 'REMX Transfer Request';
        $inventory->original_request = $request->quantity_approved;
        $inventory->date_requested = Carbon::now()->format('Y-m-d H:i');

        // Save inventory object
        $inventory->save();

        // Generate the transaction number
        $formattedTransactionNumber = sprintf('%06d', $inventory->id);
        $inventory->transaction_no = $formattedTransactionNumber;
        $inventory->inventory_id = $inventory->id;
        // Save again with the updated transaction number
        $inventory->save();

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
            'file_name' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();

            $imagePath = $request->file('file_name')->store('storage', 'public');

            $award = new Award();
            $award->fill($request->all());
            $award->path = $imagePath;
            $award->award_status = 'Awarded';
            $award->date_released = Carbon::now()->format('Y-m-d H:i');
            $award->save();

            $requestedItem = SiteInventory::find($request->inventory_item_id);
            $requestedItem->quantity -= $request->awarded_quantity;
            $requestedItem->save();

            DB::commit();

            return response()->json([
                'message' => 'Item awarded successfully.',
                'Award' => $award,
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'An error occurred while awarding the item.'], 500);
        }
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

        $requestedItem = SiteInventory::find($request->item_id);
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
            'file_name' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $imagePath = $request->file('file_name')->store('storage', 'public');
        if ($request->input('received_status') === 'partial') {
            $inventory->approved_status = null;
            $inventory->received_quantity = $request->input('received_quantity');
            $inventory->file_path = $imagePath;
        } elseif ($request->input('received_status') === 'complete') {
            $inventory->approved_status = 'Received';
            $inventory->file_path = $imagePath;
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
            ->where('budget_code', $inventory->item->budget_code, $inventory->site_id)
            ->first();

        if ($siteInventory) {
            $siteInventory->quantity += $inventory->received_quantity;
            $siteInventory->original_quantity += $inventory->received_quantity;
            $siteInventory->total_cost += $totalCost;
            $siteInventory->received_by = $inventory->received_by;
            $siteInventory->date_received = $inventory->date_received;
            $siteInventory->file_name = $inventory->file_name;
            $siteInventory->file_path = $inventory->file_path;
            $siteInventory->save();
        } else {
            $siteInventory = new SiteInventory();
            $siteInventory->item_less_id = $inventory->item->item_less_id;
            $siteInventory->item_name = $inventory->item->item_name;
            $siteInventory->quantity = $inventory->received_quantity;
            $siteInventory->original_quantity = $inventory->received_quantity;
            $siteInventory->budget_code = $inventory->item->budget_code;
            $siteInventory->type = $inventory->item->type;
            $siteInventory->category = $inventory->item->category;
            $siteInventory->date_expiry = $inventory->item->date_expiry;
            $siteInventory->site_id = $inventory->site_id;
            $siteInventory->is_active = $inventory->item->is_active;
            $siteInventory->received_by = $inventory->received_by;
            $siteInventory->date_received = $inventory->date_received;
            $siteInventory->cost = $inventory->item->cost;
            $siteInventory->total_cost = $totalCost;
            $siteInventory->file_name = $inventory->file_name;
            $siteInventory->file_path = $inventory->file_path;

            $siteInventory->save();
        }
        $inventory->quantity_approved -= $inventory->received_quantity;
        $inventory->save();

        return response()->json([
            'Request' => $inventory,
        ]);
    }

    public function receivedTransfer(Request $request, $id)
    {
        $inventory = Inventory::with('item', 'siteInventory')->find($id);

        $validator = Validator::make($request->all(), [
            'received_by' => 'required',
            'received_status' => 'required',
            'received_quantity' => 'nullable',
            'file_name' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'messages' => $validator->errors()], 400);
        }
        $imagePath = $request->file('file_name')->store('storage', 'public');
        if ($request->input('received_status') === 'partial') {
            $inventory->status = 'Transferred Partial';
            $inventory->approved_status = null;
            $inventory->file_path = $imagePath;
            $inventory->received_quantity = $request->input('received_quantity');
        } elseif ($request->input('received_status') === 'complete') {
            $inventory->status = 'Transferred All';
            $inventory->approved_status = 'Received';
            $inventory->file_path = $imagePath;
            $inventory->received_quantity = $inventory->quantity_approved;
        }

        $inventory->fill([
            'received_by' => $request->input('received_by'),
            'received_status' => $request->input('received_status'),
        ]);
        $inventory->date_received = Carbon::now()->format('Y-m-d H:i');
        $inventory->save();

        $totalCost = $inventory->siteInventory->cost * $inventory->received_quantity;

        $siteInventory = SiteInventory::where('item_name', $inventory->siteInventory->item_name)
            ->where('budget_code', $inventory->siteInventory->budget_code)
            ->where('site_id', $inventory->transferred_to)
            ->first();

        if ($siteInventory) {
            $siteInventory->site_id = $inventory->transferred_to;
            $siteInventory->quantity += $inventory->received_quantity;
            $siteInventory->original_quantity += $inventory->received_quantity;
            $siteInventory->total_cost += $totalCost;
            $siteInventory->received_by = $inventory->received_by;
            $siteInventory->transferred_by = $inventory->transferred_by;
            $siteInventory->transferred_date = $inventory->transferred_date;
            $siteInventory->transferred_from = $inventory->transferred_from;
            $siteInventory->file_name = $inventory->siteInventory->file_name;
            $siteInventory->file_path = $inventory->siteInventory->file_path;
            $siteInventory->save();
        } else {
            $siteInventory = new SiteInventory();
            $siteInventory->site_id = $inventory->transferred_to;
            $siteInventory->item_less_id = $inventory->siteInventory->item_less_id;
            $siteInventory->item_name = $inventory->siteInventory->item_name;
            $siteInventory->quantity = $inventory->received_quantity;
            $siteInventory->original_quantity = $inventory->received_quantity;
            $siteInventory->budget_code = $inventory->siteInventory->budget_code;
            $siteInventory->type = $inventory->siteInventory->type;
            $siteInventory->category = $inventory->siteInventory->category;
            $siteInventory->date_expiry = $inventory->siteInventory->date_expiry;
            $siteInventory->site_id = $inventory->site_id;
            $siteInventory->is_active = $inventory->siteInventory->is_active;
            $siteInventory->received_by = $inventory->received_by;
            $siteInventory->date_received = $inventory->date_received;
            $siteInventory->cost = $inventory->siteInventory->cost;
            $siteInventory->total_cost = $totalCost;
            $siteInventory->transferred_by = $inventory->transferred_by;
            $siteInventory->transferred_date = $inventory->transferred_date;
            $siteInventory->transferred_from = $inventory->transferred_from;
            $siteInventory->file_name = $inventory->siteInventory->file_name;
            $siteInventory->file_path = $inventory->siteInventory->file_path;
            $siteInventory->save();
        }
        $inventory->quantity_approved -= $inventory->received_quantity;
        $inventory->save();

        return response()->json([
            'Request' => $inventory,
        ]);
    }

    public function receivedRemxTransfer(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $inventory = Inventory::with('item', 'siteInventory')->find($id);

            if (!$inventory) {
                return response()->json(['error' => 'Inventory not found.'], 404);
            }

            if (!$inventory->item) {
                return response()->json(['error' => 'Items data not found.'], 404);
            }

            $validator = Validator::make($request->all(), [
                'received_by' => 'required',
                'received_status' => 'required',
                'received_quantity' => 'nullable',
                'file_name' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation failed', 'messages' => $validator->errors()], 400);
            }

            $imagePath = $request->file('file_name')->store('storage', 'public');

            if ($request->input('received_status') === 'partial') {
                if (!$request->input('received_quantity') || $request->input('received_quantity') > $inventory->quantity_approved) {
                    return response()->json(['error' => 'Invalid received quantity for partial status.'], 400);
                }

                $inventory->status = 'Transferred Partial';
                $inventory->approved_status = null;
                $inventory->received_quantity = $request->input('received_quantity');
            } elseif ($request->input('received_status') === 'complete') {
                if ($inventory->quantity_approved <= 0) {
                    return response()->json(['error' => 'No approved quantity available for complete transfer.'], 400);
                }

                $inventory->status = 'Transferred All';
                $inventory->approved_status = 'Received';
                $inventory->received_quantity = $inventory->quantity_approved;
            } else {
                return response()->json(['error' => 'Invalid received status provided.'], 400);
            }

            $inventory->fill([
                'received_by' => $request->input('received_by'),
                'received_status' => $request->input('received_status'),
                'file_path' => $imagePath,
            ]);
            $inventory->date_received = Carbon::now()->format('Y-m-d H:i');
            $inventory->save();

            $items = Items::where('item_name', $inventory->item->item_name)
                ->where('budget_code', $inventory->item->budget_code)
                ->where('site_id', $inventory->transferred_to)
                ->first();

            $totalCost = $inventory->item->cost * $inventory->received_quantity;

            if ($items) {
                $items->site_id = $inventory->transferred_to;
                $items->quantity += $inventory->received_quantity;
                $items->original_quantity += $inventory->received_quantity;
                $items->total_cost += $totalCost;
                $items->received_by = $inventory->received_by;
                $items->file_name = $inventory->item->file_name;
                $items->file_path = $inventory->item->file_path;
                $items->save();
            } else {
                $items = new Items();
                $items->site_id = $inventory->transferred_to;
                $items->item_less_id = $inventory->item->item_less_id;
                $items->item_name = $inventory->item->item_name;
                $items->quantity = $inventory->received_quantity;
                $items->original_quantity = $inventory->received_quantity;
                $items->budget_code = $inventory->item->budget_code;
                $items->type = $inventory->item->type;
                $items->category = $inventory->item->category;
                $items->date_expiry = $inventory->item->date_expiry;
                $items->is_active = $inventory->item->is_active;
                $items->received_by = $inventory->received_by;
                $items->created_by = $inventory->received_by;
                $items->date_received = $inventory->date_received;
                $items->cost = $inventory->item->cost;
                $items->total_cost = $totalCost;
                $items->file_name = $inventory->item->file_name;
                $items->file_path = $inventory->item->file_path;
                $items->save();
            }

            $inventory->quantity_approved -= $inventory->received_quantity;
            $inventory->save();

            DB::commit();

            return response()->json([
                'Request' => $inventory,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to process the request', 'message' => $e->getMessage()], 500);
        }
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

    public function cancelledItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cancelled_by' => 'required',
            'cancellation_reason' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $inventory = Inventory::find($id);
        $inventory->fill($request->all());
        $inventory->status = 'Cancelled';
        $inventory->cancelled_date = Carbon::now()->format('Y-m-d H:i');
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
            'transferredBy',
            'cancelledBy',

            'siteInventory',
        ])->where('transaction_type', 'Site Request')
            ->where('status', 'Pending')
            ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function indexAll()
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])->where('transaction_type', 'Site Request')
            ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function indexAllTransaction()
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])->where('transaction_type', 'Site Request')
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])
            ->where('transaction_type', 'Site Request')
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])
            ->where('transaction_type', 'Site Request')
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])->where('transaction_type', 'Site Request')
            ->where('status', 'Denied')
            ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function cancelled()
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])
            ->where('transaction_type', 'Site Request')
            ->where('status', 'Cancelled')
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])->where('transaction_type', 'Site Request')
            ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function remxForTransfer()
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])->where('transaction_type', 'REMX Transfer Request')
            ->where(function ($query) {
                $query->whereNull('approved_status')
                    ->orWhere('approved_status', 'Received');
            })
            ->where(function ($query) {
                $query->whereNull('received_status')
                    ->orWhere('received_status', 'partial');
            })
            ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function sourcingForTransfer()
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])->where('transaction_type', 'Sourcing Transfer Request')
            ->where(function ($query) {
                $query->whereNull('approved_status')
                    ->orWhere('approved_status', 'Received');
            })
            ->where(function ($query) {
                $query->whereNull('received_status')
                    ->orWhere('received_status', 'partial');
            })
            ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function sourcingItemHistory($id)
    {
        // Debugging: Check the value of $id
        \Log::info('sourcingItemHistory called with id: '.$id);

        $inventory = Inventory::with([
            'site',
            'item',
            'releasedBy',
            'approvedBy',
            'deniedBy',
            'receivedBy',
            'processedBy',
            'requestedBy',
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])
            ->where('inventory_item_id', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'transaction_no' => $item->transaction_no,
                    'transaction_type' => $item->transaction_type,
                    'date_requested' => $item->date_requested,
                    'quantity_approved' => $item->quantity_approved,
                    'original_request' => $item->original_request,
                    'approved_status' => $item->approved_status,
                    'received_status' => $item->received_status,
                    'received_quantity' => $item->received_quantity,
                    'cost' => $item->cost,
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                    'date_added' => $item->created_at,
                    'site' => $item->site ? [
                        'id' => $item->site->id,
                        'name' => $item->site->name,
                        'description' => $item->site->description,
                        'region' => $item->site->region,
                    ] : null,
                    'site_inventory' => $item->siteInventory ? [
                        'id' => $item->siteInventory->id,
                        'item_less_id' => $item->siteInventory->item_less_id,
                        'item_name' => $item->siteInventory->item_name,
                        'quantity' => $item->siteInventory->quantity,
                        'original_quantity' => $item->siteInventory->original_quantity,
                        'total_cost' => $item->siteInventory->total_cost,
                        'item_id' => $item->siteInventory->item_id,
                        'type' => $item->siteInventory->type,
                        'category' => $item->siteInventory->category,
                    ] : null,
                    'released_by' => $item->releasedBy ? $item->releasedBy->name : 'N/A',
                    'approved_by' => $item->approvedBy ? $item->approvedBy->name : 'N/A',
                    'denied_by' => $item->deniedBy ? $item->deniedBy->name : 'N/A',
                    'received_by' => $item->receivedBy ? $item->receivedBy->name : 'N/A',
                    'processed_by' => $item->processedBy ? $item->processedBy->name : 'N/A',
                    'requested_by' => $item->requestedBy ? $item->requestedBy->name : 'N/A',
                    'transferred_by' => $item->transferredBy ? $item->transferredBy->name : 'N/A',
                    'cancelled_by' => $item->cancelledBy ? $item->cancelledBy->name : 'N/A',
                    'transferred_from' => $item->transferredFrom ? $item->transferredFrom->name : 'N/A',
                    'transferred_to' => $item->transferredTo ? $item->transferredTo->name : 'N/A',
                ];
            });

        return response()->json(['inventory' => $inventory]);
    }

    public function remxItemHistory(Request $request)
    {
        $id = $request->input('item_id');

        // Debugging: Check the value of $id
        \Log::info('remxItemHistory called with item_id: '.$id);

        // Ensure $id is an integer
        $id = (int) $id;

        $inventory = Inventory::with([
            'site',
            'item',
            'releasedBy',
            'approvedBy',
            'deniedBy',
            'receivedBy',
            'processedBy',
            'requestedBy',
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])
            ->where('item_id', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'transaction_no' => $item->transaction_no,
                    'transaction_type' => $item->transaction_type,
                    'date_requested' => $item->date_requested,
                    'quantity_approved' => $item->quantity_approved,
                    'original_request' => $item->original_request,
                    'approved_status' => $item->approved_status,
                    'received_status' => $item->received_status,
                    'received_quantity' => $item->received_quantity,
                    'cost' => $item->cost,
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                    'date_added' => $item->created_at,
                    'site' => $item->site ? [
                        'id' => $item->site->id,
                        'name' => $item->site->name,
                        'description' => $item->site->description,
                        'region' => $item->site->region,
                    ] : null,
                    'site_inventory' => $item->siteInventory ? [
                        'id' => $item->siteInventory->id,
                        'item_less_id' => $item->siteInventory->item_less_id,
                        'item_name' => $item->siteInventory->item_name,
                        'quantity' => $item->siteInventory->quantity,
                        'original_quantity' => $item->siteInventory->original_quantity,
                        'total_cost' => $item->siteInventory->total_cost,
                        'item_id' => $item->siteInventory->item_id,
                        'type' => $item->siteInventory->type,
                        'category' => $item->siteInventory->category,
                    ] : null,
                    'released_by' => $item->releasedBy ? $item->releasedBy->name : 'N/A',
                    'approved_by' => $item->approvedBy ? $item->approvedBy->name : 'N/A',
                    'denied_by' => $item->deniedBy ? $item->deniedBy->name : 'N/A',
                    'received_by' => $item->receivedBy ? $item->receivedBy->name : 'N/A',
                    'processed_by' => $item->processedBy ? $item->processedBy->name : 'N/A',
                    'requested_by' => $item->requestedBy ? $item->requestedBy->name : 'N/A',
                    'transferred_by' => $item->transferredBy ? $item->transferredBy->name : 'N/A',
                    'cancelled_by' => $item->cancelledBy ? $item->cancelledBy->name : 'N/A',
                    'transferred_from' => $item->transferredFrom ? $item->transferredFrom->name : 'N/A',
                    'transferred_to' => $item->transferredTo ? $item->transferredTo->name : 'N/A',
                ];
            });

        return response()->json(['inventory' => $inventory]);
    }

    public function allrequest()
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])->where('transaction_type', 'Transfer Request')

            ->get();

        return response()->json(['inventory' => $inventory]);
    }

    public function alltransfer()
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
            'transferredBy',
            'cancelledBy',
            'siteInventory',
            'transferredFrom',
            'transferredTo',
        ])->where('transaction_type', 'Transfer Request')
            ->where('status', null)
            ->get();

        return response()->json(['inventory' => $inventory]);
    }

    /*
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
