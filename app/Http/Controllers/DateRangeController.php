<?php

namespace App\Http\Controllers;

use App\Http\Resources\DateRangeResource;
use App\Models\DateRange;
use Illuminate\Http\Request;

class DateRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date_range = DateRange::paginate(10);

        return DateRangeResource::collection(DateRange::all());
    }

    public function indexByMonth($monthId)
    {
        $dateRanges = DateRange::where('month_num', $monthId)->get();

        return DateRangeResource::collection($dateRanges);
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
     * @return \Illuminate\Http\Response
     */
    public function show(DateRange $dateRange)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(DateRange $dateRange)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DateRange $dateRange)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DateRange $dateRange)
    {
    }
}
