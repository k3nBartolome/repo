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

    public function perMonth(Request $request)
    {
        $dateNum = $request->input('month_num');

        $query = DateRange::where('year', 2023);

        if (!empty($dateNum)) {
            $dateNum = is_array($dateNum) ? $dateNum : [$dateNum];
            $query->whereIn('month_num', $dateNum);
        }

        $daterange = $query->get();

        return response()->json(['data' => $daterange]);
    }

    public function byMonth(Request $request, $monthId)
    {
        $monthIdArray = explode(',', $monthId);

        $allDaterange = [];

        foreach ($monthIdArray as $monthId) {
            $daterange = DateRange::where('month_num', $monthId)
            ->get();

            $allDaterange = array_merge($allDaterange, $daterange->toArray());
        }

        return response()->json(['data' => $allDaterange]);
    }

    public function getMonth()
    {
        $months = DateRange::select('month_num', 'month')->distinct()->get();

        return response()->json(['data' => $months]);
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
