<?php

namespace App\Http\Controllers;

use App\Exports\ClassStaffingExports;
use App\Models\Classes;
use App\Models\ClassStaffing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ClassStaffingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classStaffing = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])
            ->whereHas('classes.dateRange', function ($query) {
                $query->where('year', 2024);
            })
            ->whereHas('classes.site', function ($query) {
                $query->where('country', 'Philippines')->where('is_active', 1);
            })
            ->whereHas('classes.program', function ($query) {
                $query->where('is_active', 1);
            })
            ->where('active_status', '1')
            ->get();

        if ($classStaffing->isEmpty()) {
            return response()->json(['error' => 'Classes not found'], 404);
        }

        return response()->json([
            'class_staffing' => $classStaffing,
        ]);
    }

    public function index2()
    {
        $classStaffing = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])
            ->whereHas('classes.dateRange', function ($query) {
                $query->whereYear('year', 2024);
            })
            ->get();

        if ($classStaffing->isEmpty()) {
            return response()->json(['error' => 'Classes not found'], 404);
        }

        return response()->json([
            'class_staffing' => $classStaffing,
        ]);
    }

    public function mps($dateRangeId = null, $monthNum = null, $siteId = null, $programId = null)
    {
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
                DB::raw('COALESCE(date_ranges.month_num, 0) as month_num'),
                DB::raw('COALESCE(date_ranges.month, 0) as month'),
                DB::raw('COALESCE(date_ranges.date_id, 0) as date_range_id'),
                DB::raw('COALESCE(sites.site_id, 0) as site_id'),
                DB::raw('COALESCE(programs.program_id, 0) as program_id')
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

        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $groupedStaffing = $staffing->groupBy([
            'month_num',
        ]);

        $computedSums = [];

        $allMonthNums = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

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
        foreach ($allMonthNums as $monthNum) {
            $group = $groupedStaffing->get($monthNum, collect());
            $monthName = $monthNames[$monthNum];
            $computedSums[$monthName] = [
                'month' => $monthName,
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
                $grandTotals[$key] += $computedSums[$monthName][$key];
            }
        }
        $computedSums['Grand Total'] = $grandTotals;

        return response()->json([
            'mps' => $computedSums,
        ]);
    }

    public function mpsWeek(Request $request)
    {
        $monthNum = $request->input('month_num');
        $siteNum = $request->input('site_id');
        $programNum = $request->input('program_id');
        $dateNum = $request->input('date_id');
        $year = 2024;
        $date = Carbon::now()->format('Y-m-d');
        $month = null;

        $dateRange = DB::table('date_ranges')
            ->select('month', 'month_num')
            ->where('week_start', '<=', $date)
            ->where('week_end', '>=', $date)
            ->first();

        if ($dateRange) {
            $month = $dateRange->month_num;
        }
        $uniqueMonths = DB::table('date_ranges')
            ->join('classes', 'date_ranges.id', '=', 'classes.date_range_id') // Joining 'classes' table
            ->where('classes.status', 'Active')
        // Adding condition on 'classes' table
            ->select([
                DB::raw('COALESCE(date_ranges.month_num, 0) as month_num'),
                DB::raw('COALESCE(date_ranges.month, null) as month'),
            ]);

        if (!empty($monthNum)) {
            $uniqueMonths->whereIn('date_ranges.month_num', $monthNum);
        }

        $uniqueMonths = $uniqueMonths->distinct()->get();

        $uniqueSiteIdsQuery = DB::table('sites')
            ->join('classes', 'sites.site_id', '=', 'classes.site_id') // Joining 'classes' table
            ->select([
                DB::raw('COALESCE(sites.site_id, 0) as site_id'),
                DB::raw('COALESCE(sites.name, null) as site_name'),
            ]);

        if (!empty($siteNum)) {
            $siteNum = is_array($siteNum) ? $siteNum : [$siteNum];
            $uniqueSiteIdsQuery->whereIn('sites.site_id', $siteNum);
        }

        $uniqueSiteIds = $uniqueSiteIdsQuery->distinct()
            ->where('sites.is_active', 1) // Assuming 'is_active' column exists in 'sites' table
            ->where('classes.status', 'Active')
            ->where('sites.country', 'Philippines')
            ->get();

        // Initialize the computed sums array
        $computedSums = [];

        foreach ($uniqueMonths as $monthdata) {
            $month = $monthdata->month_num;
            $monthName = $monthdata->month;
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
                    DB::raw('COALESCE(classes.total_target, 0) as total'),
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

                ->where('date_ranges.month_num', $month)
                ->where('classes.total_target', '>', 0)
                ->get();

            $distinctDateRanges = DB::table('date_ranges')
                ->join('classes', 'date_ranges.id', '=', 'classes.date_range_id')
                ->where('date_ranges.year', 2024)
                ->where('date_ranges.month_num', $month)
                ->where('classes.status', 'Active')
                ->select([
                    DB::raw('COALESCE(date_ranges.date_id, 0) as date_id'),
                    DB::raw('COALESCE(date_ranges.date_range, null) as week_name'),
                    'date_ranges.date_id', // Include date_id in the select list
                ])
                ->orderBy('date_ranges.date_id', 'asc');

            if (!empty($dateNum)) {
                $siteNum = is_array($dateNum) ? $dateNum : [$dateNum];
                $distinctDateRanges->whereIn('date_ranges.date_id', $dateNum);
            }

            $distinctDateRanges = $distinctDateRanges->distinct()->get();

            foreach ($distinctDateRanges as $dateRangeData) {

                $dateRangeId = $dateRangeData->date_id;
                $weekName = $dateRangeData->week_name;
                $computedSums[$month][$dateRangeId] = [];

                foreach ($uniqueSiteIds as $siteData) {
                    $siteId = $siteData->site_id;
                    $siteName = $siteData->site_name;
                    $computedSums[$month][$dateRangeId][$siteId] = [];
                    $uniqueProgramIds = DB::table('programs')
                        ->join('classes', 'programs.id', '=', 'classes.program_id')
                        ->where('programs.site_id', $siteId)
                        ->where('classes.status', 'Active')
                        ->select([
                            DB::raw('COALESCE(programs.id, 0) as program_id'),
                            DB::raw('COALESCE(programs.name, null) as program_name'),
                        ]);

                    if (!empty($programNum)) {
                        $programNum = is_array($programNum) ? $programNum : [$programNum];
                        $uniqueProgramIds->whereIn('programs.id', $programNum);
                    }

                    $uniqueProgramIds = $uniqueProgramIds->distinct()->get();

                    foreach ($uniqueProgramIds as $programData) {
                        $programId = $programData->program_id;
                        $programName = $programData->program_name;
                        $computedSums[$month][$dateRangeId][$siteId][$programId] = [];

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
                        if ($WeekMonthSiteProgram->sum('total_target') != 0) {

                            if (array_sum($sums) > 0 && !in_array(null, $WeekMonthSiteProgram->pluck('month')->toArray())) {
                                $computedSums[$month][$dateRangeId][$siteId][$programId] = [
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
                                ];
                            } else {
                                $computedSums[$month][$dateRangeId][$siteId][$programId] = [
                                    'month' => $monthName,
                                    'week_name' => $weekName,
                                    'site_name' => $siteName,
                                    'program_name' => $programName,
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
        }

        // Calculate the grand totals
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

        // Include the grand total in your response
        $computedSums['Grand Total'] = $grandTotals;

        $response = [
            'mps' => $computedSums,
        ];

        return response()->json($response);
    }

    private function removeEmptyArrays(&$array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->removeEmptyArrays($value);
                if (empty($value)) {
                    unset($array[$key]);
                }
            }
        }
    }

    public function exportToExcel(Request $request)
    {
        $monthNum = $request->input('month_num');
        $siteNum = $request->input('site_id');
        $programNum = $request->input('program_id');
        $dateNum = $request->input('date_id');

        $uniqueMonths = DB::table('date_ranges')
            ->select([
                DB::raw('COALESCE(month_num, 0) as month_num'),
                DB::raw('COALESCE(month, null) as month'),
            ]);
        if (!empty($monthNum)) {
            $uniqueMonths->where('month_num', $monthNum);
        }

        $uniqueMonths = $uniqueMonths->distinct()->get();
        $uniqueSiteIdsQuery = DB::table('sites')
            ->select([
                DB::raw('COALESCE(site_id, 0) as site_id'),
                DB::raw('COALESCE(name, null) as site_name'),
            ]);

        if (!empty($siteNum)) {
            $siteNum = is_array($siteNum) ? $siteNum : [$siteNum];
            $uniqueSiteIdsQuery->whereIn('site_id', $siteNum);
        }

        $uniqueSiteIds = $uniqueSiteIdsQuery->distinct()
            ->where('is_active', 1)
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
        $computedSums = [];

        foreach ($uniqueMonths as $monthdata) {
            $month = $monthdata->month_num;
            $monthName = $monthdata->month;
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
                ->where('date_ranges.month_num', $month)
                ->get();
            $year = 2024;
            $distinctDateRanges = DB::table('date_ranges')
                ->where('month_num', $month)
                ->where('year', $year)
                ->select([
                    DB::raw('COALESCE(date_id, 0) as date_id'),
                    DB::raw('COALESCE(date_range, null) as week_name'),
                ]);

            if (!empty($dateNum)) {
                $distinctDateRanges->where('date_id', $dateNum);
            }
            $distinctDateRanges = $distinctDateRanges->distinct()->get();
            foreach ($distinctDateRanges as $dateRangeData) {
                $dateRangeId = $dateRangeData->date_id;
                $weekName = $dateRangeData->week_name;
                $computedSums[$month][$dateRangeId] = [];
                foreach ($uniqueSiteIds as $siteData) {
                    $siteId = $siteData->site_id;
                    $siteName = $siteData->site_name;
                    $computedSums[$month][$dateRangeId][$siteId] = [];
                    $uniqueProgramIds = DB::table('programs')
                        ->where('site_id', $siteId)
                        ->select([
                            DB::raw('COALESCE(id, 0) as program_id'),
                            DB::raw('COALESCE(name, null) as program_name'),
                        ]);
                    if (!empty($programNum)) {
                        $uniqueProgramIds->where('program_id', $programNum);
                    }
                    $uniqueProgramIds = $uniqueProgramIds->distinct()->get();
                    foreach ($uniqueProgramIds as $programData) {
                        $programId = $programData->program_id;
                        $programName = $programData->program_name;
                        $computedSums[$month][$dateRangeId][$siteId][$programId] = [];

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
                            $computedSums[$month][$dateRangeId][$siteId][$programId] = [
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
                            ];
                        } else {
                            $computedSums[$month][$dateRangeId][$siteId][$programId] = [
                                'month' => $monthName,
                                'week_name' => $weekName,
                                'site_name' => $siteName,
                                'program_name' => $programName,
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

        $flattenedData = [];

        foreach ($computedSums as $month => $monthData) {
            foreach ($monthData as $dateRangeId => $dateRangeData) {
                foreach ($dateRangeData as $siteId => $siteData) {
                    foreach ($siteData as $programId => $programDetails) {
                        if (!empty($programDetails)) {
                            // Flatten the programDetails and add them to the flattenedData array
                            $flattenedData[] = [
                                'month' => $programDetails['month'],
                                'week_name' => $programDetails['week_name'],
                                'site_name' => $programDetails['site_name'],
                                'program_name' => $programDetails['program_name'],
                                'total_target' => $programDetails['total_target'] ?? 0,
                                'internal' => $programDetails['internal'] ?? 0,
                                'external' => $programDetails['external'] ?? 0,
                                'total' => $programDetails['total'] ?? 0,
                                'cap_starts' => $programDetails['cap_starts'] ?? 0,
                                'day_1' => $programDetails['day_1'] ?? 0,
                                'day_2' => $programDetails['day_2'] ?? 0,
                                'day_3' => $programDetails['day_3'] ?? 0,
                                'day_4' => $programDetails['day_4'] ?? 0,
                                'day_5' => $programDetails['day_5'] ?? 0,
                                'filled' => $programDetails['filled'] ?? 0,
                                'open' => $programDetails['open'] ?? 0,
                                'classes' => $programDetails['classes'] ?? 0,
                            ];
                        }
                    }
                }
            }
        }

        $dataToExport = [
            'mps' => $flattenedData,
        ];

        return Excel::download(new ClassStaffingExports($dataToExport), 'your_filename.xlsx');
    }

    public function mpsMonth(Request $request)
    {
        $year = 2024;
        $date = Carbon::now()->format('Y-m-d');
        $month = null;

        $dateRange = DB::table('date_ranges')
            ->select('month', 'month_num')
            ->where('week_start', '<=', $date)
            ->where('week_end', '>=', $date)
            ->first();

        if ($dateRange) {
            $month = $dateRange->month_num;
        }

        $distinctMonthsAndWeeks = DB::table('date_ranges')
            ->select([
                'month_num',
                DB::raw('COALESCE(date_ranges.month, null) as month_name'),
                'week_start',
                'week_end',
            ])
            ->where('year', $year)
            ->where('month_num', $month)
            ->distinct()
            ->get();

        $computedSums = [];
        $grandTotals = [
            'total_target' => 0,
            'internal' => 0,
            'external' => 0,
            'total' => 0,
            'fillrate' => 0,
            'day_1' => 0,
            'day_1sup' => 0,
            'pipeline_total' => 0,
            'hires_goal' => 0,
        ];

        foreach ($distinctMonthsAndWeeks as $item) {
            $monthNum = $item->month_num;
            $monthName = $item->month_name;
            $weekStart = $item->week_start;
            $weekEnd = $item->week_end;

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
                    DB::raw('COALESCE(date_ranges.month, null) as month_name'),
                    DB::raw('COALESCE(date_ranges.date_range, null) as week_name'),
                    DB::raw('COALESCE(date_ranges.week_start, null) as week_start'),
                    DB::raw('COALESCE(date_ranges.week_end, null) as week_end'),
                    DB::raw('COALESCE(sites.site_id, 0) as site_id'),
                    DB::raw('COALESCE(programs.program_id, 0) as program_id'),
                    DB::raw('COALESCE(sites.name, null) as site_name'),
                    DB::raw('COALESCE(programs.name, null) as program_name')
                )
                ->where('date_ranges.month_num', $monthNum)
                ->where('date_ranges.year', $year)
                ->where('date_ranges.week_start', $weekStart)
                ->where('date_ranges.week_end', $weekEnd)
                ->when($request->input('site_id'), function ($query, $siteId) {
                    return $query->where('sites.id', $siteId);
                })
                ->get();

            $computedSums[$monthName][$weekStart . ' - ' . $weekEnd] = [
                'month' => $monthName,
                'week_start' => $weekStart,
                'week_end' => $weekEnd,
                'total_target' => $staffing->sum('total_target'),
                'pipeline_total' => $staffing->sum('pipeline_total'),
                'hires_goal' => $staffing->sum('total_target') != 0 ? number_format(($staffing->sum('pipeline_total') / $staffing->sum('total_target')) * 100, 2) : 0,
                'total' => $staffing->sum('show_ups_total'),
                'fillrate' => $staffing->sum('total_target') != 0 ? number_format(($staffing->sum('show_ups_total') / $staffing->sum('total_target')) * 100, 2) : 0,

                'internal' => $staffing->sum('show_ups_internal'),
                'external' => $staffing->sum('show_ups_external'),
                'day_1' => $staffing->sum('day_1'),
                'day_1sup' => $staffing->sum('total_target') != 0 ? number_format(($staffing->sum('day_1') / $staffing->sum('total_target')) * 100, 2) : 0,
            ];

            foreach ($grandTotals as $key => $value) {
                if ($key !== 'fillrate') {
                    $grandTotals[$key] += $computedSums[$monthName][$weekStart . ' - ' . $weekEnd][$key];
                }
            }
        }
        $computedSums['Grand Total'] = $grandTotals;

        return response()->json([
            'mps' => $computedSums,
        ]);
    }

    /*     public function mpsMonth(Request $request)
    {
    $siteId = $request->input('site_id');
    $distinctMonths = DB::table('date_ranges')
    ->select([
    'month_num',
    DB::raw('COALESCE(date_ranges.month, null) as month_name'),
    ])
    ->distinct()
    ->get();
    $computedSums = [];
    $grandTotals = [
    'total_target' => 0,
    'internal' => 0,
    'external' => 0,
    'total' => 0,
    'fillrate' => 0,
    'day_1' => 0,
    'day_1sup' => 0,
    'pipeline_total' => 0,
    'hires_goal' => 0,
    ];
    $totalShowUpsTotalAllMonths = 0;
    $totalTargetsAllMonths = 0;
    $totalDay1AllMonths = 0;
    $totalPipelineTotalAllMonths = 0;
    foreach ($distinctMonths as $monthData) {
    $monthNum = $monthData->month_num;
    $monthName = $monthData->month_name;
    $year = 2024;
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
    DB::raw('COALESCE(date_ranges.month, null) as month_name'),
    DB::raw('COALESCE(date_ranges.date_range, null) as week_name'),
    DB::raw('COALESCE(sites.site_id, 0) as site_id'),
    DB::raw('COALESCE(programs.program_id, 0) as program_id'),
    DB::raw('COALESCE(sites.name, null) as site_name'),
    DB::raw('COALESCE(programs.name, null) as program_name')
    )
    ->where('class_staffing.active_status', 1)
    ->where('date_ranges.month_num', $monthNum)
    ->where('date_ranges.year', $year)
    ->when($siteId, function ($query) use ($siteId) {
    return $query->where('sites.id', $siteId);
    })
    ->get();
    $totalShowUpsTotalAllMonths += $staffing->sum('show_ups_total');
    $totalTargetsAllMonths += $staffing->sum('total_target');
    $totalDay1AllMonths += $staffing->sum('day_1');
    $totalPipelineTotalAllMonths += $staffing->sum('pipeline_total');
    $computedSums[$monthName] = [
    'month' => $monthName,
    'total_target' => $staffing->sum('total_target'),
    'internal' => $staffing->sum('show_ups_internal'),
    'external' => $staffing->sum('show_ups_external'),
    'total' => $staffing->sum('show_ups_total'),
    'fillrate' => $staffing->sum('total_target') != 0 ? number_format(($staffing->sum('show_ups_total') / $staffing->sum('total_target')) * 100, 2) : 0,
    'day_1' => $staffing->sum('day_1'),
    'day_1sup' => $staffing->sum('total_target') != 0 ? number_format(($staffing->sum('day_1') / $staffing->sum('total_target')) * 100, 2) : 0,
    'pipeline_total' => $staffing->sum('pipeline_total'),
    'hires_goal' => $staffing->sum('total_target') != 0 ? number_format(($staffing->sum('pipeline_total') / $staffing->sum('total_target')) * 100, 2) : 0,
    ];
    foreach ($grandTotals as $key => $value) {
    if ($key !== 'fillrate') {
    $grandTotals[$key] += $computedSums[$monthName][$key];
    }
    }
    }
    $fillRateGrandTotal = $totalTargetsAllMonths != 0 ?
    number_format(($totalShowUpsTotalAllMonths / $totalTargetsAllMonths) * 100, 2) : 0;
    $day1SupGrandTotal = $totalTargetsAllMonths != 0 ?
    number_format(($totalDay1AllMonths / $totalTargetsAllMonths) * 100, 2) : 0;
    $hiresGoalGrandTotal = $totalTargetsAllMonths != 0 ?
    number_format(($totalPipelineTotalAllMonths / $totalTargetsAllMonths) * 100, 2) : 0;
    $grandTotals['fillrate'] = $fillRateGrandTotal;
    $grandTotals['day_1sup'] = $day1SupGrandTotal;
    $grandTotals['hires_goal'] = $hiresGoalGrandTotal;
    $computedSums['Grand Total'] = $grandTotals;
    return response()->json([
    'mps' => $computedSums,
    ]);
    } */
    public function mpsSite(Request $request)
    {
        $monthNum = $request->input('month_num');
        $programId = $request->input('program_id');

        $distinctSites = DB::table('sites')
            ->select([
                'site_id',
                DB::raw('COALESCE(sites.name, null) as site_name'),
            ])
            ->where('sites.is_active', 1)
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

        foreach ($distinctSites as $siteData) {
            $siteId = $siteData->site_id;
            $siteName = $siteData->site_name;
            $year = 2024;
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
                    DB::raw('COALESCE(date_ranges.month, null) as month'),
                    DB::raw('COALESCE(date_ranges.date_range, null) as week_name'),
                    DB::raw('COALESCE(sites.site_id, 0) as site_id'),
                    DB::raw('COALESCE(programs.program_id, 0) as program_id'),
                    DB::raw('COALESCE(sites.name, null) as site_name'),
                    DB::raw('COALESCE(programs.name, null) as program_name')
                )
                ->where('class_staffing.active_status', 1)
                ->where('sites.site_id', $siteId)
                ->where('date_ranges.year', $year)
                ->when($monthNum, function ($query) use ($monthNum) {
                    return $query->where('date_ranges.month_num', 'LIKE', '%' . $monthNum . '%');
                })
                ->when($programId, function ($query) use ($programId) {
                    return $query->where('programs.program_id', 'LIKE', '%' . $programId . '%');
                })
                ->get();
            $computedSums[$siteId] = [
                'site' => $siteName,
                'total_target' => $staffing->sum('total_target'),
                'internal' => $staffing->sum('show_ups_internal'),
                'external' => $staffing->sum('show_ups_external'),
                'total' => $staffing->sum('show_ups_total'),
                'cap_starts' => $staffing->sum('cap_starts'),
                'day_1' => $staffing->sum('day_1'),
                'day_2' => $staffing->sum('day_2'),
                'day_3' => $staffing->sum('day_3'),
                'day_4' => $staffing->sum('day_4'),
                'day_5' => $staffing->sum('day_5'),
                'filled' => $staffing->sum('filled'),
                'open' => $staffing->sum('open'),
                'classes' => $staffing->sum('classes_number'),
            ];
            foreach ($grandTotals as $key => $value) {
                $grandTotals[$key] += $computedSums[$siteId][$key];
            }
        }
        $computedSums['Grand Total'] = $grandTotals;

        return response()->json([
            'mps' => $computedSums,
        ]);
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
            'show_ups_internal' => 'required',
            'show_ups_external' => 'required',
            'show_ups_total' => 'required',
            'deficit' => 'required',
            'percentage' => 'required',
            'status' => 'required',
            'day_1' => 'required',
            'day_2' => 'required',
            'day_3' => 'required',
            'day_4' => 'required',
            'day_5' => 'required',
            'day_6' => 'required',
            'open' => 'required',
            'filled' => 'required',
            'total_endorsed' => 'required',
            'internals_hires' => 'required',
            'additional_extended_jo' => 'required',
            'with_jo' => 'required',
            'pending_jo' => 'required',
            'pending_berlitz' => 'required',
            'pending_ov' => 'required',
            'pending_pre_emps' => 'required',
            'classes_number' => 'required',
            'pipeline_total' => 'required',
            'pipeline_target' => 'required',
            'cap_starts' => 'required',
            'internals_hires_all' => 'required',
            'pipeline' => 'required',
            'additional_remarks' => 'required',
            'classes_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $staffing = new ClassStaffing();
        $staffing->fill($request->all());
        $staffing->transaction = 'Add Class Staffing';
        $staffing->active_status = '1';
        $staffing->save();
        $staffing->class_staffing_id = $staffing->id;
        $staffing->save();

        return response()->json([
            'staffing' => $staffing,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $class = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])->find($id);

        if (!$class) {
            return response()->json(['error' => 'Classes not found'], 404);
        }

        return response()->json([
            'class' => $class,
        ]);
    }

    public function transaction($id)
    {
        $class = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])->find($id);

        if (!$class) {
            return response()->json(['error' => 'Classes not found'], 404);
        }
        $class = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])
            ->where('class_staffing_id', $class->class_staffing_id)
            ->get();

        return response()->json([
            'class' => $class,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'show_ups_internal' => 'required',
            'show_ups_external' => 'required',
            'show_ups_total' => 'required',
            'deficit' => 'required',
            'percentage' => 'required',
            'status' => 'required',
            'day_1' => 'required',
            'day_2' => 'required',
            'day_3' => 'required',
            'day_4' => 'required',
            'day_5' => 'required',
            'day_6' => 'required',
            'total_endorsed' => 'required',
            'internals_hires' => 'required',
            'additional_extended_jo' => 'required',
            'with_jo' => 'required',
            'pending_jo' => 'required',
            'pending_berlitz' => 'required',
            'pending_ov' => 'required',
            'pending_pre_emps' => 'required',
            'classes_number' => 'required',
            'pipeline_total' => 'required',
            'cap_starts' => 'required',
            'internals_hires_all' => 'required',
            'pipeline' => 'required',
            'pipeline_target' => 'required',
            'additional_remarks' => 'required',
            'classes_id' => 'required',
            'updated_by' => 'required',
            'wave_no' => 'nullable',
            'agreed_start_date' => 'nullable',
            'erf_number' => 'nullable',
            'date_range_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $classes = Classes::find($request->input('classes_id'));
        $classes->wave_no = $request->input('wave_no');
        $classes->agreed_start_date = $request->input('agreed_start_date');
        $classes->erf_number = $request->input('erf_number');
        $classes->date_range_id = $request->input('date_range_id');
        $classes->save();

        $class = ClassStaffing::find($id);
        $class->active_status = '1';
        $class->transaction = 'Update';
        $class->updated_by = $request->input('updated_by');
        $class->save();

        $newClass = $class->replicate();

        $newClass->fill($request->all());
        $newClass->active_status = '0';
        $newClass->save();

        return response()->json([
            'class' => $class,
        ]);
    }

    /*
 * Remove the specified resource from storage.
 *
 * @return \Illuminate\Http\Response
 */
}
