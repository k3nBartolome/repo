<?php

namespace App\Http\Controllers;

use App\Http\Resources\DateRangeResource;
use App\Models\DateRange;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DateRangeController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        $year = 2024;
        $dateRanges = DateRange::where( 'year', $year )
        ->get();

        return response()->json( [ 'data' => $dateRanges ] );
    }

    public function indexAll() {
        $dateRanges = DateRange::all();

        return response()->json( [ 'data' => $dateRanges ] );
    }

    public function indexByMonth( $monthId ) {
        $year = 2024;

        $dateRanges = DateRange::where( 'month_num', $monthId )
        ->where( 'year', $year )
        ->get();

        return response()->json( [ 'data' => $dateRanges ] );
    }

    public function perMonth( Request $request ) {
        $dateNum = $request->input( 'month_num' );

        $query = DateRange::where( 'year', 2024 );

        if ( !empty( $dateNum ) ) {
            $dateNum = is_array( $dateNum ) ? $dateNum : [ $dateNum ];
            $query->whereIn( 'month_num', $dateNum );
        }

        $daterange = $query->get();

        return response()->json( [ 'data' => $daterange ] );
    }

    public function byMonth( Request $request, $monthId ) {
        $monthIdArray = explode( ',', $monthId );
        $year = 2024;
        $allDaterange = [];

        foreach ( $monthIdArray as $monthId ) {
            $daterange = DateRange::where( 'month_num', $monthId )
            ->where( 'year', $year )
            ->get();

            $allDaterange = array_merge( $allDaterange, $daterange->toArray() );
        }

        return response()->json( [ 'data' => $allDaterange ] );
    }

    public function getMonth() {
        $months = DateRange::select( 'month_num', 'month' )->distinct()->get();

        return response()->json( [ 'data' => $months ] );
    }

    /*
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
}
