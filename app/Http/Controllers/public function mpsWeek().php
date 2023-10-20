public function mpsWeek()
    {
        $uniqueMonths = DB::table('date_ranges')
            ->select([
                DB::raw('COALESCE(month_num, 0) as month_num'),
                DB::raw('COALESCE(month, null) as month')])
            ->distinct()
            ->get()
            ->pluck('month_num')
            ->toArray();
        $uniqueSiteIds = DB::table('sites')
            ->select([
                DB::raw('COALESCE(site_id, 0) as site_id'),
                DB::raw('COALESCE(name, null) as site_name')])
            ->get()
            ->pluck('site_id')
            ->toArray();

        $distinctDateRanges = DB::table('date_ranges')
            ->select([
                DB::raw('COALESCE(date_id, 0) as date_id'),
                DB::raw('COALESCE(date_range, null) as week_name'),
            ])
            ->distinct()
            ->get();
        $computedSums = [];
        $grandTotals = [
            'total_target' => 0,
            'internal' => 0,
            'external' => 0,
            'total' => 0,
            'cap_starts' => 0,
            'day_1' => 0,
            'day_2' => 0,
            'day_3' => 0,
            'day_4' => 0,
            'day_5' => 0,
            'filled' => 0,
            'open' => 0,
            'classes' => 0,
        ];
        foreach ($distinctDateRanges as $dateRangeData) {
            $dateRangeId = $dateRangeData->date_id;
            $weekName = $dateRangeData->week_name;
            $staffing = DB::table('class_staffing')
                ->leftJoin('classes', 'class_staffing.classes_id', '=', 'classes.id')
                ->leftJoin('date_ranges', 'classes.date_range_id', '=', 'date_ranges.id')
                ->leftJoin('sites', 'classes.site_id', '=', 'sites.id')
                ->leftJoin('programs', function ($join) {
                    $join->on('classes.program_id', '=', 'programs.id')
                        ->on('sites.id', '=', 'programs.site_id');
                })
                ->select(
                    'class_staffing.*',
                    'classes.*',
                    'sites.*',
                    'programs.*',
                    'date_ranges.*',
                    DB::raw('COALESCE(date_ranges.date_id, 0) as date_range_id'),
                    DB::raw('COALESCE(date_ranges.month_num, 0) as month_num'),
                    DB::raw('COALESCE(date_ranges.month, null) as month'),
                    DB::raw('COALESCE(date_ranges.date_range, null) as week_name'),
                    DB::raw('COALESCE(sites.site_id, 0) as site_id'),
                    DB::raw('COALESCE(programs.program_id, 0) as program_id'),
                    DB::raw('COALESCE(sites.name, null) as site_name'),
                    DB::raw('COALESCE(programs.name, null) as program_name')
                )
                ->where('class_staffing.active_status', 1)
                ->where('date_ranges.date_id', $dateRangeId)
                ->get();
            $computedSums[$dateRangeId] = [];
            foreach ($uniqueMonths as $monthdata) {
                $month = $monthdata->date_id;
            $monthName = $monthdata->week_name;

                $computedSums[$dateRangeId][$month] = [];
                foreach ($uniqueSiteIds as $siteId) {
                    $computedSums[$dateRangeId][$month][$siteId] = [];
                    $uniqueProgramIds = DB::table('programs')
        ->where('site_id', $siteId)
        ->distinct()
            ->get()
        ->pluck('program_id')
        ->toArray();
                    foreach ($uniqueProgramIds as $programId) {
                        $computedSums[$dateRangeId][$month][$siteId][$programId] = [];

                        $WeekMonthSiteProgram = $staffing
                        ->where('month_num', $month)
                        ->where('date_range_id', $dateRangeId)
                        ->where('site_id', $siteId)
                        ->where('program_id', $programId);

                        $sums = [
                            'total_target' => $WeekMonthSiteProgram->sum('total_target'),
                            'internal' => $WeekMonthSiteProgram->sum('show_ups_internal'),
                            'external' => $WeekMonthSiteProgram->sum('show_ups_external'),
                            'total' => $WeekMonthSiteProgram->sum('show_ups_total'),
                            'cap_starts' => $WeekMonthSiteProgram->sum('cap_starts'),
                            'day_1' => $WeekMonthSiteProgram->sum('day_1'),
                            'day_2' => $WeekMonthSiteProgram->sum('day_2'),
                            'day_3' => $WeekMonthSiteProgram->sum('day_3'),
                            'day_4' => $WeekMonthSiteProgram->sum('day_4'),
                            'day_5' => $WeekMonthSiteProgram->sum('day_5'),
                            'filled' => $WeekMonthSiteProgram->sum('open'),
                            'open' => $WeekMonthSiteProgram->sum('filled'),
                            'classes' => $WeekMonthSiteProgram->sum('classes_number'),
                        ];

                        if (array_sum($sums) > 0 && !in_array(null, $WeekMonthSiteProgram->pluck('month')->toArray())) {
                            $computedSums[$dateRangeId][$month][$siteId][$programId] = [
                                'month' => $WeekMonthSiteProgram->first()->month,
                                'week_name' => $WeekMonthSiteProgram->first()->week_name,
                                'site_name' => $WeekMonthSiteProgram->first()->site_name,
                                'program_name' => $WeekMonthSiteProgram->first()->program_name,
                                'total_target' => $sums['total_target'],
                                'internal' => $sums['internal'],
                                'external' => $sums['external'],
                                'total' => $sums['total'],
                                'cap_starts' => $sums['cap_starts'],
                                'day_1' => $sums['day_1'],
                                'day_2' => $sums['day_2'],
                                'day_3' => $sums['day_3'],
                                'day_4' => $sums['day_4'],
                                'day_5' => $sums['day_5'],
                                'filled' => $sums['filled'],
                                'open' => $sums['open'],
                                'classes' => $sums['classes'],
                                'date_range_id' => $dateRangeId,
                                'month_num' => $month,
                                'site_id' => $siteId,
                                'program_id' => $programId,
                            ];
                        } else {
                            $computedSums[$dateRangeId][$month][$siteId][$programId] = [
                        'month' => "",
    'week_name' => $weekName,
    'site_name' => "",
    'program_name' =>"",
    'date_range_id' => $dateRangeId,
    'month_num' => $month,
    'site_id' => $siteId,
    'program_id' => $programId,
    'total_target' => 0,
        'internal' => 0,
        'external' => 0,
        'total' => 0,
        'cap_starts' => 0,
        'day_1' => 0,
        'day_2' => 0,
        'day_3' => 0,
        'day_4' => 0,
        'day_5' => 0,
        'filled' => 0,
        'open' => 0,
        'classes' => 0,
];
                        }
                    }
                }
            }
        }

        $response = [
            'mps' => $computedSums,
        ];

        return response()->json($response);
    }
