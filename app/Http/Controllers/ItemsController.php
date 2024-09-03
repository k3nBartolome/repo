<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\SiteInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            ->where('category', 'Normal')
            ->where('quantity', '>', 0)
            ->get();
        $items = $items->map(function ($item) {
            return [
                    'id' => $item->id,
                    'item_less_id' => $item->item_less_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'original_quantity' => $item->original_quantity,
                    'cost' => $item->cost,
                    'total_cost' => $item->total_cost,
                    'budget_code' => $item->budget_code,
                    'type' => $item->type,
                    'category' => $item->category,
                    'date_expiry' => $item->date_expiry,
                    'is_active' => $item->is_active,
                    'created_by' => $item->createdBy->name,
                    'site_id' => $item->site_id,
                    'site_name' => $item->site->name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'date_added' => $item->created_at,
                    'image_path' => $this->getImagePath($item),
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                ];
        });

        return response()->json(['items' => $items]);
    }

    public function indexboth()
    {
        $items = Items::with(['createdBy', 'site'])
            ->where('is_active', 1)
            ->get();
        $items = $items->map(function ($item) {
            return [
                    'id' => $item->id,
                    'item_less_id' => $item->item_less_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'original_quantity' => $item->original_quantity,
                    'cost' => $item->cost,
                    'total_cost' => $item->total_cost,
                    'budget_code' => $item->budget_code,
                    'type' => $item->type,
                    'category' => $item->category,
                    'date_expiry' => $item->date_expiry,
                    'is_active' => $item->is_active,
                    'created_by' => $item->createdBy->name,
                    'site_id' => $item->site_id,
                    'site_name' => $item->site->name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'date_added' => $item->created_at,
                    'image_path' => $this->getImagePath($item),
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                ];
        });

        return response()->json(['items' => $items]);
    }

    public function indexboth3()
    {
        $items = Items::with(['createdBy', 'site'])
            ->where('is_active', 1)
            ->where('quantity', '>', 0)
            ->get();
        $items = $items->map(function ($item) {
            return [
                    'id' => $item->id,
                    'item_less_id' => $item->item_less_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'original_quantity' => $item->original_quantity,
                    'cost' => $item->cost,
                    'total_cost' => $item->total_cost,
                    'budget_code' => $item->budget_code,
                    'type' => $item->type,
                    'category' => $item->category,
                    'date_expiry' => $item->date_expiry,
                    'is_active' => $item->is_active,
                    'created_by' => $item->createdBy->name,
                    'site_id' => $item->site_id,
                    'site_name' => $item->site->name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'date_added' => $item->created_at,
                    'image_path' => $this->getImagePath($item),
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                ];
        });

        return response()->json(['items' => $items]);
    }

    public function indexboth2()
    {
        $items = SiteInventory::with(['createdBy', 'receivedBy', 'site'])
            ->where('is_active', 1)
            ->get();
        $items = $items->map(function ($item) {
            return [
                    'id' => $item->id,
                    'item_less_id' => $item->item_less_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'original_quantity' => $item->original_quantity,
                    'cost' => $item->cost,
                    'total_cost' => $item->total_cost,
                    'budget_code' => $item->budget_code,
                    'type' => $item->type,
                    'category' => $item->category,
                    'date_expiry' => $item->date_expiry,
                    'is_active' => $item->is_active,
                    'created_by' => $item->createdBy ? $item->createdBy->name : 'N/A',
                    'received_by' => $item->receivedBy ? $item->receivedBy->name : 'N/A',
                    'transferred_by' => $item->transferredBy ? $item->transferredBy->name : 'N/A',
                    'transferred_from' => $item->transferredFrom ? $item->transferredFrom->name : 'N/A',
                    'site_id' => $item->site_id,
                    'site_name' => $item->site->name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'date_added' => $item->created_at,
                    'image_path' => $this->getImagePath($item),
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                ];
        });

        return response()->json(['items' => $items]);
    }

    public function indexseparate(Request $request)
    {
        $category = $request->input('category');

        $itemsQuery = Items::with(['createdBy', 'site'])
            ->where('is_active', 1)
            ->where('quantity', '>', 0);

        if ($category === 'Premium') {
            $itemsQuery->where('category', 'Premium');
        } elseif ($category === 'Normal') {
            $itemsQuery->where('category', 'Normal');
        }

        $items = $itemsQuery->get();

        // Map items to include all relevant fields
        $items = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'item_less_id' => $item->item_less_id,
                'item_name' => $item->item_name,
                'quantity' => $item->quantity,
                'original_quantity' => $item->original_quantity,
                'cost' => $item->cost,
                'total_cost' => $item->total_cost,
                'budget_code' => $item->budget_code,
                'type' => $item->type,
                'category' => $item->category,
                'date_expiry' => $item->date_expiry,
                'is_active' => $item->is_active,
                'created_by' => $item->createdBy->name,
                'site_id' => $item->site_id,
                'site_name' => $item->site->name,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'date_added' => $item->created_at,
                'image_path' => $this->getImagePath($item),
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
            ];
        });

        return response()->json(['items' => $items]);
    }

    public function index3()
    {
        $items = $items = SiteInventory::with(['site', 'createdBy', 'receivedBy', 'transferredBy', 'transferredFrom'])
            ->where('is_active', 1)
            ->where('category', 'Normal')
            ->where('quantity', '>', 0)
            ->get();
        $items = $items->map(function ($item) {
            return [
                    'id' => $item->id,
                    'item_less_id' => $item->item_less_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'original_quantity' => $item->original_quantity,
                    'cost' => $item->cost,
                    'total_cost' => $item->total_cost,
                    'budget_code' => $item->budget_code,
                    'type' => $item->type,
                    'category' => $item->category,
                    'date_expiry' => $item->date_expiry,
                    'is_active' => $item->is_active,
                    'created_by' => $item->createdBy ? $item->createdBy->name : 'N/A',
                    'received_by' => $item->receivedBy ? $item->receivedBy->name : 'N/A',
                    'transferred_by' => $item->transferredBy ? $item->transferredBy->name : 'N/A',
                    'transferred_from' => $item->transferredFrom ? $item->transferredFrom->name : 'N/A',
                    'site_id' => $item->site_id,
                    'site_name' => $item->site->name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'date_added' => $item->created_at,
                    'image_path' => $this->getImagePath($item),
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                ];
        });

        return response()->json(['items' => $items]);
    }

    public function indexAllSite()
    {
        $items = $items = SiteInventory::with(['site', 'createdBy', 'receivedBy', 'transferredBy', 'transferredFrom'])
            ->where('is_active', 1)
            ->where('quantity', '>', 0)
            ->get();

        $items = $items->map(function ($item) {
            return [
                    'id' => $item->id,
                    'item_less_id' => $item->item_less_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'original_quantity' => $item->original_quantity,
                    'cost' => $item->cost,
                    'total_cost' => $item->total_cost,
                    'budget_code' => $item->budget_code,
                    'type' => $item->type,
                    'category' => $item->category,
                    'date_expiry' => $item->date_expiry,
                    'is_active' => $item->is_active,
                    'created_by' => $item->createdBy ? $item->createdBy->name : 'N/A',
                    'received_by' => $item->receivedBy ? $item->receivedBy->name : 'N/A',
                    'transferred_by' => $item->transferredBy ? $item->transferredBy->name : 'N/A',
                    'transferred_from' => $item->transferredFrom ? $item->transferredFrom->name : 'N/A',
                    'site_id' => $item->site_id,
                    'site_name' => $item->site->name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'date_added' => $item->created_at,
                    'image_path' => $this->getImagePath($item),
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                ];
        });

        return response()->json(['items' => $items]);
    }

    public function index4($siteId)
    {
        $items = $items = SiteInventory::with(['site', 'createdBy', 'receivedBy', 'transferredBy', 'transferredFrom'])
            ->where('site_id', $siteId)
            ->where('is_active', 1)
            ->where('category', 'Normal')
            ->where('quantity', '>', 0)
            ->get();
        $items = $items->map(function ($item) {
            return [
                    'id' => $item->id,
                    'item_less_id' => $item->item_less_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'original_quantity' => $item->original_quantity,
                    'cost' => $item->cost,
                    'total_cost' => $item->total_cost,
                    'budget_code' => $item->budget_code,
                    'type' => $item->type,
                    'category' => $item->category,
                    'date_expiry' => $item->date_expiry,
                    'is_active' => $item->is_active,
                      'created_by' => $item->createdBy ? $item->createdBy->name : 'N/A',
                    'received_by' => $item->receivedBy ? $item->receivedBy->name : 'N/A',
                    'transferred_by' => $item->transferredBy ? $item->transferredBy->name : 'N/A',
                    'transferred_from' => $item->transferredFrom ? $item->transferredFrom->name : 'N/A',
                    'site_id' => $item->site_id,
                    'site_name' => $item->site->name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'date_added' => $item->created_at,
                    'image_path' => $this->getImagePath($item),
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                ];
        });

        return response()->json(['items' => $items]);
    }

    public function index5($siteId)
    {
        $items = $items = SiteInventory::with(['site', 'createdBy', 'receivedBy', 'transferredBy', 'transferredFrom'])
            ->where('site_id', $siteId)
            ->where('is_active', 1)
            ->where('category', 'Premium')
            ->where('quantity', '>', 0)
            ->get();
        $items = $items->map(function ($item) {
            return [
                    'id' => $item->id,
                    'item_less_id' => $item->item_less_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'original_quantity' => $item->original_quantity,
                    'cost' => $item->cost,
                    'total_cost' => $item->total_cost,
                    'budget_code' => $item->budget_code,
                    'type' => $item->type,
                    'category' => $item->category,
                    'date_expiry' => $item->date_expiry,
                    'is_active' => $item->is_active,
                      'created_by' => $item->createdBy ? $item->createdBy->name : 'N/A',
                    'received_by' => $item->receivedBy ? $item->receivedBy->name : 'N/A',
                    'transferred_by' => $item->transferredBy ? $item->transferredBy->name : 'N/A',
                    'transferred_from' => $item->transferredFrom ? $item->transferredFrom->name : 'N/A',
                    'site_id' => $item->site_id,
                    'site_name' => $item->site->name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'date_added' => $item->created_at,
                    'image_path' => $this->getImagePath($item),
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                ];
        });

        return response()->json(['items' => $items]);
    }

    public function index2()
    {
        $items = SiteInventory::with(['site', 'createdBy', 'receivedBy', 'transferredBy', 'transferredFrom'])
            ->where('is_active', 1)
            ->where('category', 'Premium')
            ->where('quantity', '>', 0)
            ->get();
        $items = $items->map(function ($item) {
            return [
                    'id' => $item->id,
                    'item_less_id' => $item->item_less_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'original_quantity' => $item->original_quantity,
                    'cost' => $item->cost,
                    'total_cost' => $item->total_cost,
                    'budget_code' => $item->budget_code,
                    'type' => $item->type,
                    'category' => $item->category,
                    'date_expiry' => $item->date_expiry,
                    'is_active' => $item->is_active,
                     'created_by' => $item->createdBy ? $item->createdBy->name : 'N/A',
                    'received_by' => $item->receivedBy ? $item->receivedBy->name : 'N/A',
                    'transferred_by' => $item->transferredBy ? $item->transferredBy->name : 'N/A',
                    'transferred_from' => $item->transferredFrom ? $item->transferredFrom->name : 'N/A',
                    'site_id' => $item->site_id,
                    'site_name' => $item->site->name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'date_added' => $item->created_at,
                   'image_path' => $this->getImagePath($item),
                    'file_name' => $item->file_name,
                    'file_path' => $item->file_path,
                ];
        });

        return response()->json(['items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

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
            'file_name' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $imagePath = $request->file('file_name')->store('storage', 'public');
        $items = new Items();
        $items->fill($request->all());
        $items->date_added = Carbon::now()->format('Y-m-d H:i');
        $items->file_path = $imagePath;
        $items->save();
        $items->item_less_id = Str::uuid();
        $items->save();

        return response()->json([
            'items' => $items,
        ]);
    }

    public function storeSiteSupply(Request $request)
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
            'file_name' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $imagePath = $request->file('file_name')->store('storage', 'public');
        $items = new SiteInventory();
        $items->fill($request->all());
        $items->file_path = $imagePath;
        $items->date_added = Carbon::now()->format('Y-m-d H:i');
        $items->save();
        $items->item_less_id = Str::uuid();
        $items->save();

        return response()->json([
            'items' => $items,
        ]);
    }

    private function getImagePath($items)
    {
        return asset('storage/'.$items->file_path);
    }

    /*
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
}
