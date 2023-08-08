<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchase = PurchaseRequest::with(['requestedBy', 'deniedBy', 'approvedBy', 'site'])
            ->where('status', 'pending')
            ->get();

        return response()->json(['purchase' => $purchase]);
    }

    public function index2()
    {
        $purchase = PurchaseRequest::with(['requestedBy', 'deniedBy', 'approvedBy', 'site'])
            ->where('status', 'approved')
            ->get();

        return response()->json(['purchase' => $purchase]);
    }

    public function index3()
    {
        $purchase = PurchaseRequest::with(['requestedBy', 'deniedBy', 'approvedBy', 'site'])
            ->where('status', 'denied')
            ->get();

        return response()->json(['purchase' => $purchase]);
    }

    public function approvedPurchase(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'approved_by' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $purchase = PurchaseRequest::find($id);
        $purchase->fill($request->all());
        $purchase->status = 'approved';
        $purchase->approved_date = Now();
        $purchase->save();

        return response()->json([
            'purchase' => $purchase,
        ]);
    }

    public function deniedPurchase(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'denied_by' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $purchase = PurchaseRequest::find($id);
        $purchase->fill($request->all());
        $purchase->status = 'denied';
        $purchase->denied_date = Now();
        $purchase->save();

        return response()->json([
            'purchase' => $purchase,
        ]);
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
            'item_name' => 'required',
            'quantity' => 'required',
            'estimated_cost' => 'nullable',
            'total_estimated_cost' => 'nullable',
            'site_id' => 'required|exists:sites,id',
            'requested_by' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $purchase = new PurchaseRequest();
        $purchase->fill($request->all());
        $purchase->status = 'pending';
        $purchase->save();

        return response()->json([
            'purchase' => $purchase,
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
