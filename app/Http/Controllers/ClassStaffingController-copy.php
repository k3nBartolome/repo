/* public function mpsWeek()
    {
        // Query distinct site, program, and date_range IDs from the database
        $sites = DB::table('sites')->pluck('id')->toArray();
        $programs = DB::table('programs')->pluck('id')->toArray();
        $dateRanges = DB::table('date_ranges')->pluck('id')->toArray();

        $allCombinations = [];

        foreach ($sites as $siteId) {
            foreach ($programs as $programId) {
                foreach ($dateRanges as $dateRangeId) {
                    $allCombinations[] = [
                        'site_id' => $siteId,
                        'program_id' => $programId,
                        'date_range_id' => $dateRangeId,
                    ];
                }
            }
        }

        $staffing = DB::table('class_staffing')
            ->leftJoin('classes', 'class_staffing.classes_id', '=', 'classes.id')
            ->leftJoin('date_ranges', 'classes.date_range_id', '=', 'date_ranges.id')
            ->leftJoin('sites', 'classes.site_id', '=', 'sites.id')
            ->leftJoin('programs', 'classes.program_id', '=', 'programs.id')
            ->select(
                'class_staffing.*',
                'classes.*',
                'sites.*',
                'programs.*',
                'date_ranges.*',
                DB::raw('COALESCE(date_ranges.date_id, 0) as date_range_id'),
                DB::raw('COALESCE(date_ranges.month_num, 0) as month_num'),
                DB::raw('COALESCE(date_ranges.date_range, 0) as week_name'),
                DB::raw('COALESCE(sites.site_id, 0) as site_id'),
                DB::raw('COALESCE(programs.program_id, 0) as program_id'),
                DB::raw('COALESCE(sites.name, 0) as site_name'),
                DB::raw('COALESCE(programs.name, 0) as program_name'),
            )
            ->where('class_staffing.active_status', 1);

        $groupedData = $staffing->groupBy(['site_id', 'program_id', 'date_range_id']);

        $classesBySiteProgramDateRange = [];

        foreach ($allCombinations as $combination) {
            $siteId = $combination['site_id'];
            $programId = $combination['program_id'];
            $dateRangeId = $combination['date_range_id'];

            if (isset($groupedData["$siteId_$programId_$dateRangeId"])) {
                $classesBySiteProgramDateRange[] = [
                    'site_id' => $siteId,
                    'program_id' => $programId,
                    'date_range_id' => $dateRangeId,
                    'classes' => $groupedData["$siteId_$programId_$dateRangeId"]->toArray(),
                ];
            } else {
                $classesBySiteProgramDateRange[] = [
                    'site_id' => $siteId,
                    'program_id' => $programId,
                    'date_range_id' => $dateRangeId,
                    'classes' => [],
                ];
            }
        }

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

        foreach ($groupedStaffing as $monthNum => $group) {
            $computedSums[$monthNum] = [
                'month' => $group->first()->month,
                'week_name' => $group->first()->week_name,
                'program_name' => $group->first()->site_name,
                'site_name' => $group->first()->program_name,
                'total_target' => $group->sum('total_target'),
                'internal' => $group->sum('show_ups_internal'),
                'external' => $group->sum('show_ups_external'),
                'total' => $group->sum('show_ups_total'),
                'cap_starts' => $group->sum('cap_starts'),
                'day_1' => $group->sum('day_1'),
                'day_2' => $group->sum('day_2'),
                'day_3' => $group->sum('day_3'),
                'day_4' => $group->sum('day_4'),
                'day_5' => $group->sum('day_5'),
                'filled' => $group->sum('open'),
                'open' => $group->sum('filled'),
                'classes' => $group->sum('classes_number'),
            ];

            foreach ($grandTotals as $key => $value) {
                $grandTotals[$key] += $computedSums[$monthNum][$key];
            }
        }

        $computedSums[] = [
            'month' => 'Total',
            'week_name' => '',
            'total_target' => $grandTotals['total_target'],
            'internal' => $grandTotals['internal'],
            'external' => $grandTotals['external'],
            'total' => $grandTotals['total'],
            'cap_starts' => $grandTotals['cap_starts'],
            'day_1' => $grandTotals['day_1'],
            'day_2' => $grandTotals['day_2'],
            'day_3' => $grandTotals['day_3'],
            'day_4' => $grandTotals['day_4'],
            'day_5' => $grandTotals['day_5'],
            'filled' => $grandTotals['filled'],
            'open' => $grandTotals['open'],
            'classes' => $grandTotals['classes'],
        ];

        return response()->json([
            'mps' => $computedSums,
            'classes_by_site_program_date_range' => $classesBySiteProgramDateRange,
        ]);
    } */
    /*     public function mps($dateRangeId = null, $monthNum = null, $siteId = null, $programId = null)
        {
            $staffing = DB::table('class_staffing')
                ->leftJoin('classes', 'class_staffing.classes_id', '=', 'classes.id')
                ->leftJoin('date_ranges', 'classes.date_range_id', '=', 'date_ranges.id')
                ->leftJoin('sites', 'classes.site_id', '=', 'sites.id')
                ->leftJoin('programs', 'classes.program_id', '=', 'programs.id')
                ->select(
                    'class_staffing.*',
                    'classes.*',
                    'sites.site_id as site_id',
                    'programs.program_id as program_id',
                    'date_ranges.date_id as date_range_id',
                    'date_ranges.month_num'
                )
                ->where('class_staffing.active_status', 1);


            if ($dateRangeId !== null) {
                $staffing->where('date_ranges.id', $dateRangeId);
            }

            if ($monthNum !== null) {
                $staffing->orWhere('date_ranges.month_num', $monthNum);
            }

            if ($siteId !== null) {
                $staffing->where('sites.id', $siteId);
            }

            if ($programId !== null) {
                $staffing->where('programs.id', $programId);
            }

            $staffing = $staffing->get();

            $groupedStaffing = $staffing->groupBy([
                'date_range_id',
                'site_id',
                'program_id',
            ]);

            $computedSums = [];

            foreach ($groupedStaffing as $key => $group) {
                $computedSums[$key] = [
                    'total_target' => $group->sum('total_target'),
                    'internal' => $group->sum('show_ups_internal'),
                    'external' => $group->sum('show_ups_external'),
                    'total' => $group->sum('show_ups_total'),
                    'cap_starts' => $group->sum('cap_starts'),
                    'day_1' => $group->sum('day_1'),
                    'day_2' => $group->sum('day_2'),
                    'day_3' => $group->sum('day_3'),
                    'day_4' => $group->sum('day_4'),
                    'day_5' => $group->sum('day_5'),
                    'filled' => $group->sum('open'),
                    'open' => $group->sum('filled'),
                    'classes' => $group->sum('classes_number'),
                ];
            }

            return response()->json([
                'mps' => $computedSums,
            ]);
        } */
    /*
    public function mps()
    {
        $withRelations = [
        'classes.site',
        'classes.program',
        'classes.dateRange',
        'classes.createdByUser',
        'classes.updatedByUser',
    ];
        $withRelations2 = [
        'site', 'program', 'dateRange', 'createdByUser', 'updatedByUser',
    ];

        $classStaffingQuery = ClassStaffing::with($withRelations);
        $classesQuery = Classes::with($withRelations2);

        $totalTarget = $classesQuery->sum('total_target');
        $totalInternal = $classStaffingQuery->sum('show_ups_internal');
        $totalExternal = $classStaffingQuery->sum('show_ups_external');
        $totalShowups = $classStaffingQuery->sum('show_ups_total');
        $totalCapsStarts = $classStaffingQuery->sum('cap_starts');
        $totalDay1 = $classStaffingQuery->sum('day_1');
        $totalDay2 = $classStaffingQuery->sum('day_2');
        $totalDay3 = $classStaffingQuery->sum('day_3');
        $totalDay4 = $classStaffingQuery->sum('day_4');
        $totalDay5 = $classStaffingQuery->sum('day_5');

        $totalFilled = $classStaffingQuery->where('status', 'FILLED')->sum('classes_number');

        $filledClasses = ($totalTarget != 0) ? (($totalFilled / $totalTarget) * 100) : 0;

        $mps = [
        'total_target' => $totalTarget,
        'internal' => $totalInternal,
        'external' => $totalExternal,
        'overall starts' => $totalShowups,
        'fill rate' => ($totalCapsStarts != 0) ? (($totalTarget / $totalCapsStarts) * 100) : 0,
        'day 1' => $totalDay1,
        'fill_rate_on_day_1' => ($totalDay1 != 0) ? ($totalTarget / $totalDay1) * 100 : 0,
        'day 2' => $totalDay2,
        'fill_rate_on_day_2' => ($totalDay2 != 0) ? ($totalTarget / $totalDay2) * 100 : 0,
        'day 3' => $totalDay3,
        'fill_rate_on_day_3' => ($totalDay3 != 0) ? ($totalTarget / $totalDay3) * 100 : 0,
        'day 4' => $totalDay4,
        'fill_rate_on_day_4' => ($totalDay4 != 0) ? ($totalTarget / $totalDay4) * 100 : 0,
        'day 5' => $totalDay5,
        'fill_rate_on_day5' => ($totalDay5 != 0) ? ($totalTarget / $totalDay5) * 100 : 0,
        'total_classes' => $classStaffingQuery->sum('classes_number'),
        'total_filled' => $totalFilled,
        'total_open' => $classStaffingQuery->where('status', 'OPEN')->sum('classes_number'),
        'filled_classes' => $filledClasses,
    ];

        return response()->json([
        'mps' => $mps,
    ]);
    } */