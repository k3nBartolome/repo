<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\SiteInventory;
use Carbon\Carbon;
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
            ->where('category', 'Normal')
            ->where('quantity', '>', 0)
            ->get();

        return response()->json(['items' => $items]);
    }

    public function indexboth()
    {
        $items = Items::with(['createdBy', 'site'])
            ->where('is_active', 1)
            ->get();

        return response()->json(['items' => $items]);
    }

    public function indexboth3()
    {
        $items = Items::with(['createdBy', 'site'])
            ->where('is_active', 1)
            ->where('quantity', '>', 0)
            ->get();

        return response()->json(['items' => $items]);
    }

    public function indexboth2()
    {
        $items = SiteInventory::with(['createdBy', 'site'])
            ->where('is_active', 1)
            ->get();

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

        return response()->json(['items' => $items]);
    }

    public function index3()
    {
        $items = SiteInventory::with(['site'])
            ->where('is_active', 1)
            ->where('category', 'Normal')
            ->where('quantity', '>', 0)
            ->get();

        return response()->json(['items' => $items]);
    }

    public function index4($siteId)
    {
        $items = SiteInventory::with(['site'])
            ->where('site_id', $siteId)
            ->where('is_active', 1)
            ->where('category', 'Normal')
            ->where('quantity', '>', 0)
            ->get();

        return response()->json(['items' => $items]);
    }

    public function index5($siteId)
    {
        $items = SiteInventory::with(['site'])
            ->where('site_id', $siteId)
            ->where('is_active', 1)
            ->where('category', 'Premium')
            ->where('quantity', '>', 0)
            ->get();

        return response()->json(['items' => $items]);
    }

    public function index2()
    {
        $items = SiteInventory::with(['site'])
            ->where('is_active', 1)
            ->where('category', 'Premium')
            ->where('quantity', '>', 0)
            ->get();

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
        $items->date_added = Carbon::now()->format('Y-m-d H:i');
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
