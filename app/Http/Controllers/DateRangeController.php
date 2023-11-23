<?php

namespace App\Http\Controllers;

use App\Http\Resources\DateRangeResource;
use App\Models\DateRange;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DateRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): AnonymousResourceCollection
    {
        $dateRanges = DateRange::paginate(10);
    
        return DateRangeResource::collection($dateRanges);
    }
    
    public function indexByMonth($monthId): AnonymousResourceCollection
    {
        $dateRanges = DateRange::where('month_num', $monthId)->paginate(10);
    
        return DateRangeResource::collection($dateRanges);
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

    /*
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
