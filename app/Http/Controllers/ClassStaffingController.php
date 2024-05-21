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
    public function index(Request $request)
    {
        $query = ClassStaffing::with(['classes', 'classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])
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
        ->whereHas('classes', function ($query) {
            $query->where('status', 'Active');
        });

        if ($request->has('site_id') && $request->site_id !== null) {
            $query->whereHas('classes.site', function ($query) use ($request) {
                $query->where('id', $request->site_id);
            });
        }

        if ($request->has('program_id') && $request->program_id !== null) {
            $query->whereHas('classes.program', function ($query) use ($request) {
                $query->where('id', $request->program_id);
            });
        }

        if ($request->has('month_num') && $request->month_num !== null) {
            $query->whereHas('classes.dateRange', function ($query) use ($request) {
                $query->where('month_num', $request->month_num);
            });
        }

        if ($request->has('date_range_id') && $request->date_range_id !== null) {
            $query->whereHas('classes.dateRange', function ($query) use ($request) {
                $query->where('id', $request->date_range_id);
            });
        }


        $classStaffing = $query->get();

        if ($classStaffing->isEmpty()) {
            return response()->json(['class_staffing' => []]);
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

    public function mpsWeek()
    {

        $year = 2024;
        $date = Carbon::now()->format('Y-m-d');

        $dateRange = DB::table('date_ranges')
            ->select('week_start', 'week_end')
            ->where('week_start', '<=', $date)
            ->where('week_end', '>=', $date)
            ->where('year',  $year)
            ->first();
        if (!$dateRange) {
            return response()->json(['error' => 'No date range found for the current date.']);
        }

        $distinctDateRanges = DB::table('date_ranges')
            ->whereIn('week_end', [
                Carbon::parse($dateRange->week_end)->subWeek(),
                $dateRange->week_end,
                Carbon::parse($dateRange->week_end)->addWeek(),
                Carbon::parse($dateRange->week_end)->addWeeks(2),

            ])
            ->pluck('date_id');

        $weeklyTotals = [];
        $weeklyData = [];

        foreach ($distinctDateRanges as $dateRangeId) {
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
                    DB::raw('COALESCE(date_ranges.date_range, null) as week_name'),
                    DB::raw('COALESCE(sites.site_id, 0) as site_id'),
                    DB::raw('COALESCE(programs.program_id, 0) as program_id'),
                    DB::raw('COALESCE(sites.name, null) as site_name'),
                    DB::raw('COALESCE(programs.name, null) as program_name'),
                    DB::raw('COALESCE(programs.program_group, null) as program_group')
                )
                ->where('class_staffing.active_status', 1)
                ->where('classes.total_target', '>', 0)
                ->where('sites.country', 'Philippines')
                ->where('date_ranges.date_id', $dateRangeId)
                ->get();

            foreach ($staffing as $item) {
                $siteId = $item->site_id;
                $programId = $item->program_id;
                $weekName = $item->week_name;

                $key = $dateRangeId . '' . $siteId . '' . $programId;

                if (!isset($weeklyData[$weekName])) {
                    $weeklyData[$weekName] = [];
                }

                if (!isset($weeklyData[$weekName][$key])) {
                    $weeklyData[$weekName][$key] = [
                        'week_name' => $item->week_name,
                        'site_name' => $item->site_name,
                        'program_name' => $item->program_name,
                        'program_group' => $item->program_group,
                        'total_target' => 0,
                        'show_ups_internal' => 0,
                        'show_ups_external' => 0,
                        'show_ups_total' => 0,
                        'fillrate' => 0,
                        'pending_jo' => 0,
                        'pending_berlitz' => 0,
                        'pending_ov' => 0,
                        'day_1' => 0,
                        'day_1sup' => 0,
                        'pipeline_total' => 0,
                        'hires_goal' => 0,
                        'color_status' => '',
                    ];
                }
                $weeklyData[$weekName][$key]['pending_ov'] += $item->pending_ov;
                $weeklyData[$weekName][$key]['pending_jo'] += $item->pending_jo;
                $weeklyData[$weekName][$key]['pending_berlitz'] += $item->pending_berlitz;
                $weeklyData[$weekName][$key]['total_target'] += $item->total_target;
                $weeklyData[$weekName][$key]['show_ups_internal'] += $item->show_ups_internal;
                $weeklyData[$weekName][$key]['show_ups_external'] += $item->show_ups_external;
                $weeklyData[$weekName][$key]['show_ups_total'] += $item->show_ups_total;
                $weeklyData[$weekName][$key]['day_1'] += $item->day_1;
                $weeklyData[$weekName][$key]['pipeline_total'] += $item->pipeline_total;
                $weeklyData[$weekName][$key]['hires_goal'] = $item->total_target != 0 ? number_format(($item->pipeline_total / $item->total_target) * 100, 1) : 0;
                $weeklyData[$weekName][$key]['fillrate'] = $item->total_target != 0 ? number_format(($item->show_ups_total / $item->total_target) * 100, 1) : 0;
                $weeklyData[$weekName][$key]['day_1sup'] = $item->total_target != 0 ? number_format(($item->day_1 / $item->total_target) * 100, 1) : 0;

                if ($distinctDateRanges[0] == $dateRangeId) {
                    $weeklyData[$weekName][$key]['color_status'] = ($weeklyData[$weekName][$key]['fillrate'] >= 100) ? 'Green' : 'Red';
                } elseif ($distinctDateRanges[1] == $dateRangeId) {
                    $weeklyData[$weekName][$key]['color_status'] = ($weeklyData[$weekName][$key]['hires_goal'] >= 100) ? 'Green' : 'Red';
                }
                elseif ($distinctDateRanges[2] == $dateRangeId) {
                    $weeklyData[$weekName][$key]['color_status'] = ($weeklyData[$weekName][$key]['hires_goal'] >= 100) ? 'Green' : 'Red';
                }
                 elseif ($distinctDateRanges[3] == $dateRangeId) {
                    if ($weeklyData[$weekName][$key]['hires_goal'] >= 100) {
                        $weeklyData[$weekName][$key]['color_status'] = 'Green';
                    } elseif ($weeklyData[$weekName][$key]['hires_goal'] >= 50) {
                        $weeklyData[$weekName][$key]['color_status'] = 'Yellow';
                    } else {
                        $weeklyData[$weekName][$key]['color_status'] = 'Red';
                    }
                }

                if (!isset($weeklyTotals[$weekName])) {
                    $weeklyTotals[$weekName] = [
                        'total_target' => 0,
                        'show_ups_internal' => 0,
                        'show_ups_external' => 0,
                        'show_ups_total' => 0,
                        'day_1' => 0,
                        'pipeline_total' => 0,
                        'pending_jo' => 0,
            'pending_berlitz' => 0,
            'pending_ov' => 0,
                    ];
                }
                $weeklyTotals[$weekName]['total_target'] += $item->total_target;
                $weeklyTotals[$weekName]['show_ups_internal'] += $item->show_ups_internal;
                $weeklyTotals[$weekName]['show_ups_external'] += $item->show_ups_external;
                $weeklyTotals[$weekName]['show_ups_total'] += $item->show_ups_total;
                $weeklyTotals[$weekName]['day_1'] += $item->day_1;
                $weeklyTotals[$weekName]['pipeline_total'] += $item->pipeline_total;
                $weeklyTotals[$weekName]['pending_jo'] += $item->pending_jo;
                $weeklyTotals[$weekName]['pending_berlitz'] += $item->pending_berlitz;
                $weeklyTotals[$weekName]['pending_ov'] += $item->pending_ov;
            }
        }

        // Prepare the weekly pipe data with weekly totals
        $weeklyPipeWithTotals = [];

        foreach ($weeklyData as $weekName => $weekItems) {
            // Add the weekly total for this week
            $weeklyPipeWithTotals["Weekly Total $weekName"] = [
                'week_name' => "Weekly Total for $weekName",
                'site_name' => '',
                'program_name' => '',
                'program_group' => '',
                'total_target' => $weeklyTotals[$weekName]['total_target'],
                'show_ups_internal' => $weeklyTotals[$weekName]['show_ups_internal'],
                'show_ups_external' => $weeklyTotals[$weekName]['show_ups_external'],
                'show_ups_total' => $weeklyTotals[$weekName]['show_ups_total'],
                'fillrate' => $weeklyTotals[$weekName]['total_target'] != 0 ? number_format(($weeklyTotals[$weekName]['show_ups_total'] / $weeklyTotals[$weekName]['total_target']) * 100, 1) : 0,
                'pending_jo' => $weeklyTotals[$weekName]['pending_jo'],
                'pending_berlitz' => $weeklyTotals[$weekName]['pending_berlitz'],
                'pending_ov' => $weeklyTotals[$weekName]['pending_ov'],
                'day_1' => $weeklyTotals[$weekName]['day_1'],
                'day_1sup' => $weeklyTotals[$weekName]['total_target'] != 0 ? number_format(($weeklyTotals[$weekName]['day_1'] / $weeklyTotals[$weekName]['total_target']) * 100, 1) : 0,
                'pipeline_total' => $weeklyTotals[$weekName]['pipeline_total'],
                'hires_goal' => $weeklyTotals[$weekName]['total_target'] != 0 ? number_format(($weeklyTotals[$weekName]['pipeline_total'] / $weeklyTotals[$weekName]['total_target']) * 100, 1) : 0,
                'color_status' => '',
            ];

            // Add the week's data
            $weeklyPipeWithTotals = array_merge($weeklyPipeWithTotals, $weekItems);
        }

        // Calculate the grand totals
        $grandTotals = [
            'total_target' => 0,
            'show_ups_internal' => 0,
            'show_ups_external' => 0,
            'show_ups_total' => 0,
            'fillrate' => 0,
            'day_1' => 0,
            'day_1sup' => 0,
            'pipeline_total' => 0,
            'hires_goal' => 0,
            'pending_jo' => 0,
            'pending_berlitz' => 0,
            'pending_ov' => 0,
        ];

        $totalCount = 0;
        $totalFillrate = 0;
        $totalDay1sup = 0;
        $totalHiresGoal = 0;

        foreach ($weeklyPipeWithTotals as $key => $sum) {
            if (strpos($sum['week_name'], 'Weekly Total') !== false) {
                continue;
            }

            $totalCount++;

            $grandTotals['total_target'] += $sum['total_target'];
            $grandTotals['show_ups_internal'] += $sum['show_ups_internal'];
            $grandTotals['show_ups_external'] += $sum['show_ups_external'];
            $grandTotals['show_ups_total'] += $sum['show_ups_total'];
            $grandTotals['pipeline_total'] += $sum['pipeline_total'];
            $grandTotals['day_1'] += $sum['day_1'];
            $grandTotals['pending_jo'] += $sum['pending_jo'];
            $grandTotals['pending_berlitz'] += $sum['pending_berlitz'];
            $grandTotals['pending_ov'] += $sum['pending_ov'];

            // Sum up for average calculation
            $totalFillrate += $sum['fillrate'];
            $totalDay1sup += $sum['day_1sup'];
            $totalHiresGoal += $sum['hires_goal'];
        }

        // Calculate averages
        $grandTotals['fillrate'] = $totalCount != 0 ? number_format($totalFillrate / $totalCount, 1) : 0;
        $grandTotals['day_1sup'] = $totalCount != 0 ? number_format($totalDay1sup / $totalCount, 1) : 0;
        $grandTotals['hires_goal'] = $totalCount != 0 ? number_format($totalHiresGoal / $totalCount, 1) : 0;

        // Mapped Grand Total
        $mappedGrandTotal = [
            'week_name' => 'Grand Total',
            'site_name' => '',
            'program_name' => '',
            'program_group' => '',
            'total_target' => $grandTotals['total_target'],
            'show_ups_internal' => $grandTotals['show_ups_internal'],
            'show_ups_external' => $grandTotals['show_ups_external'],
            'show_ups_total' => $grandTotals['show_ups_total'],
            'fillrate' => $grandTotals['fillrate'],
            'pending_jo' => $grandTotals['pending_jo'],
            'pending_berlitz' => $grandTotals['pending_berlitz'],
            'pending_ov' => $grandTotals['pending_ov'],
            'day_1' => $grandTotals['day_1'],
            'day_1sup' => $grandTotals['day_1sup'],
            'pipeline_total' => $grandTotals['pipeline_total'],
            'hires_goal' => $grandTotals['hires_goal'],
            'color_status' => '',
        ];

        // Merge Grand Total and Weekly Pipe Data with Totals
        $weeklyPipe = array_merge(['Grand Total' => $mappedGrandTotal], $weeklyPipeWithTotals);

        $response = [
            'mps' => $weeklyPipe,
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
            'show_ups_internal' => 0,
            'show_ups_external' => 0,
            'show_ups_total' => 0,
            'fillrate' => 0,
            'day_1' => 0,
            'day_1sup' => 0,
            'pipeline_total' => 0,
            'hires_goal' => 0,
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
                            'show_ups_internal' => $WeekMonthSiteProgram->sum('show_ups_internal'),
                            'show_ups_external' => $WeekMonthSiteProgram->sum('show_ups_external'),
                            'show_ups_total' => $WeekMonthSiteProgram->sum('show_ups_total'),
                            'fillrate' => $WeekMonthSiteProgram->sum('total_target') != 0 ? number_format(($WeekMonthSiteProgram->sum('show_ups_total') / $WeekMonthSiteProgram->sum('total_target')) * 100, 2) : 0,
                            'day_1' => $WeekMonthSiteProgram->sum('day_1'),
                            'day_1sup' => $WeekMonthSiteProgram->sum('total_target') != 0 ? number_format(($WeekMonthSiteProgram->sum('day_1') / $WeekMonthSiteProgram->sum('total_target')) * 100, 2) : 0,
                            'pipeline_total' => $WeekMonthSiteProgram->sum('pipeline_total'),
                            'hires_goal' => $WeekMonthSiteProgram->sum('total_target') != 0 ? number_format(($WeekMonthSiteProgram->sum('pipeline_total') / $WeekMonthSiteProgram->sum('total_target')) * 100, 2) : 0,

                        ];

                        if (array_sum($sums) > 0 && !in_array(null, $WeekMonthSiteProgram->pluck('month')->toArray())) {
                            $computedSums[$month][$dateRangeId][$siteId][$programId] = [
                                'month' => $WeekMonthSiteProgram->first()->month,
                                'week_name' => $WeekMonthSiteProgram->first()->week_name,
                                'site_name' => $WeekMonthSiteProgram->first()->site_name,
                                'program_name' => $WeekMonthSiteProgram->first()->program_name,
                                'total_target' => $sums['total_target'],
                                'show_ups_internal' => $sums['show_ups_internal'],
                                'show_ups_external' => $sums['show_ups_external'],
                                'show_ups_total' => $sums['show_ups_total'],
                                'fillrate' => $sums['fillrate'],
                                'day_1' => $sums['day_1'],
                                'day_1sup' => $sums['day_1sup'],
                                'pipeline_total' => $sums['pipeline_total'],
                                'hires_goal' => $sums['hires_goal'],

                            ];
                        } else {
                            $computedSums[$month][$dateRangeId][$siteId][$programId] = [
                                'month' => $monthName,
                                'week_name' => $weekName,
                                'site_name' => $siteName,
                                'program_name' => $programName,
                                'total_target' => 0,
                                'show_ups_internal' => 0,
                                'show_ups_external' => 0,
                                'show_ups_total' => 0,
                                'fillrate' => 0,
                                'day_1' => 0,
                                'day_1sup' => 0,
                                'pipeline_total' => 0,
                                'hires_goal' => 0,
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
                                'show_ups_internal' => $programDetails['show_ups_internal'] ?? 0,
                                'show_ups_external' => $programDetails['show_ups_external'] ?? 0,
                                'show_ups_total' => $programDetails['show_ups_total'] ?? 0,
                                'fillrate' => $programDetails['fillrate'] ?? 0,
                                'day_1' => $programDetails['day_1'] ?? 0,
                                'day_1sup' => $programDetails['day_1sup'] ?? 0,
                                'pipeline_total' => $programDetails['pipeline_total'] ?? 0,
                                'hires_goal' => $programDetails['hires_goal'] ?? 0,
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
                'week_end', 'date_range',
            ])
            ->where('year', $year)
            ->where('month_num', $month)
            ->distinct()
            ->get();

        $wtd = [];
        $grandTotals = [
            'total_target' => 0,
            'pipeline_total' => 0,
            'pipeline_goal' => 0,
            'total_internal' => 0,
            'total_external' => 0,
            'jo' => 0,
            'versant' => 0,
            'ov' => 0,
            'internal' => 0,
            'external' => 0,
            'total_show_ups' => 0,
            'fill_rate' => 0,
            'day_1' => 0,
            'day_1sup' => 0,
        ];

        foreach ($distinctMonthsAndWeeks as $item) {
            $monthName = $item->month_name;
            $weekStart = $item->week_start;
            $weekEnd = $item->week_end;
            $weekName = $item->date_range;

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
                ->where('class_staffing.active_status', 1)
                ->where('date_ranges.month_num', $item->month_num)
                ->where('date_ranges.year', $year)
                ->where('date_ranges.week_start', $weekStart)
                ->where('date_ranges.week_end', $weekEnd)
                ->get();

            $wtd[] = [
                'month' => $monthName,
                'week_name' => $weekName,
                'total_target' => $staffing->sum('total_target'),
                'pipeline_total' => $staffing->sum('pipeline_total'),
                'pipeline_goal' => $staffing->sum('total_target') != 0 ? number_format(($staffing->sum('pipeline_total') / $staffing->sum('total_target')) * 100, 1) : 0,
                'total_internal' => $staffing->sum('internals_hires'),
                'total_external' => $staffing->sum('with_jo'),
                'jo' => $staffing->sum('pending_jo'),
                'versant' => $staffing->sum('pending_berlitz'),
                'ov' => $staffing->sum('pending_ov'),
                'internal' => $staffing->sum('internal'),
                'external' => $staffing->sum('show_ups_external'),
                'total_show_ups' => $staffing->sum('show_ups_total'),
                'fill_rate' => $staffing->sum('total_target') != 0 ? number_format(($staffing->sum('show_ups_total') / $staffing->sum('total_target')) * 100, 1) : 0,
                'day_1' => $staffing->sum('day_1'),
                'day_1sup' => $staffing->sum('total_target') != 0 ? number_format(($staffing->sum('day_1') / $staffing->sum('total_target')) * 100, 1) : 0,
            ];

            foreach ($grandTotals as $key => $value) {
                if ($key !== 'fillrate') {
                    $grandTotals[$key] += $wtd[count($wtd) - 1][$key];
                }
            }
        }

        // Calculate fill rate, day 1 supervision rate, and hires goal
        $fillRateGrandTotal = $grandTotals['total_target'] != 0 ?
        number_format(($grandTotals['total_show_ups'] / $grandTotals['total_target']) * 100, 1) : 0;
        $day1SupGrandTotal = $grandTotals['total_target'] != 0 ?
        number_format(($grandTotals['day_1'] / $grandTotals['total_target']) * 100, 1) : 0;
        $hiresGoalGrandTotal = $grandTotals['total_target'] != 0 ?
        number_format(($grandTotals['pipeline_total'] / $grandTotals['total_target']) * 100, 1) : 0;
        $grandTotalRow = [
            'month' => 'Grand Total',
            'week_name' => '',
            'total_target' => $grandTotals['total_target'],
            'pipeline_total' => $grandTotals['pipeline_total'],
            'pipeline_goal' => $grandTotals['pipeline_goal'],
            'total_internal' => $grandTotals['total_internal'],
            'total_external' => $grandTotals['total_external'],
            'jo' => $grandTotals['jo'],
            'versant' => $grandTotals['versant'],
            'ov' => $grandTotals['ov'],
            'internal' => $grandTotals['internal'],
            'external' => $grandTotals['external'],
            'total_show_ups' => $grandTotals['total_show_ups'],
            'fill_rate' => $fillRateGrandTotal,
            'day_1' => $grandTotals['day_1'],
            'day_1sup' => $day1SupGrandTotal,
        ];

        $wtd[] = $grandTotalRow;
        return response()->json([
            'mps' => $wtd,
        ]);
    }

    /*     public function mpsMonth()
{
$distinctMonths = DB::table('date_ranges')
->select([
'month_num',
DB::raw('COALESCE(date_ranges.month, null) as month_name'),
])
->distinct()
->get();
$ytd = [];
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
$totalInternalAllMonths = 0;
$totalExternalAllMonths = 0;
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
->where('classes.status', 'Active')
->where('date_ranges.month_num', $monthNum)
->where('date_ranges.year', $year)
->get();

$totalShowUpsTotalAllMonths += $staffing->sum('show_ups_total');
$totalTargetsAllMonths += $staffing->sum('total_target');
$totalDay1AllMonths += $staffing->sum('day_1');
$totalInternalAllMonths += $staffing->sum('show_ups_internal');
$totalExternalAllMonths += $staffing->sum('show_ups_external');
$totalPipelineTotalAllMonths += $staffing->sum('pipeline_total');
$ytd[$monthName] = [
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
$grandTotals[$key] += $ytd[$monthName][$key];
}
}
}

// Calculate fill rate, day 1 supervision rate, and hires goal
$fillRateGrandTotal = $totalTargetsAllMonths != 0 ?
number_format(($totalShowUpsTotalAllMonths / $totalTargetsAllMonths) * 100, 2) : 0;
$day1SupGrandTotal = $totalTargetsAllMonths != 0 ?
number_format(($totalDay1AllMonths / $totalTargetsAllMonths) * 100, 2) : 0;
$hiresGoalGrandTotal = $totalTargetsAllMonths != 0 ?
number_format(($totalPipelineTotalAllMonths / $totalTargetsAllMonths) * 100, 2) : 0;

// Create Grand Total row
$grandTotalRow = [
'month' => 'Grand Total',
'total_target' => $totalTargetsAllMonths,
'internal' => $totalInternalAllMonths, // Fill with your calculation if needed
'external' => $totalExternalAllMonths, // Fill with your calculation if needed
'total' => $totalShowUpsTotalAllMonths,
'fillrate' => $fillRateGrandTotal,
'day_1' => $totalDay1AllMonths,
'day_1sup' => $day1SupGrandTotal,
'pipeline_total' => $totalPipelineTotalAllMonths,
'hires_goal' => $hiresGoalGrandTotal,
];

// Merge Grand Total row with the rest of the data
$ytd = array_merge([$grandTotalRow], $ytd);
return response()->json([
'mps' => $ytd,
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
            //'day_6' => 'required',
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
            //'day_6' => 'required',
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
        $newClass = $class->replicate();
        $newClass->active_status = '0';
        $newClass->save();
        $class->fill($request->all());
        $class->active_status = '1';
        $class->transaction = 'Update';
        $class->updated_by = $request->input('updated_by');
        $class->save();



        return response()->json([
            'class' => $class,
        ]);
    }
}
