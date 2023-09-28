<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Models\ClassStaffing;
use App\Models\DateRange;
use App\Models\Program;
use App\Notifications\ClassNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    public function index()
    {
        $minutes = 60;
        $data = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA')
            ->select(
                'DateOfApplication',
                'LastName',
                'FirstName',
                'MiddleName',
                'MobileNo',
                'Site',
                'GenSource',
                'SpecSource',
                'Step',
                'AppStep',
                'PERX_HRID',
                'PERX_NAME',
                'OSS_HRID',
                'OSS_FNAME',
                'OSS_LNAME',
                'OSS_LOB',
                'OSS_SITE'
            )->get();

        $data = cache()->remember('perx_data', $minutes, function () use ($data) {
            return $data;
        });

        return response()->json([
            'perx' => $data,
        ]);
    }

    public function sumTotalTarget()
    {
        $total = Classes::where('status', 'active')
            ->sum('total_target');

        return response()->json(['total_target' => $total]);
    }

    public function countStatus()
    {
        $counts = [
            'active' => Classes::whereHas('site', function ($query) {
                $query->where('country', '=', 'Philippines');
            })
                ->where('status', 'active')
                ->count(),

            'cancelled' => Classes::whereHas('site', function ($query) {
                $query->where('country', '=', 'Philippines');
            })
                ->where('status', 'cancelled')
                ->count(),

            'moved' => Classes::whereHas('site', function ($query) {
                $query->where('country', '=', 'Philippines');
            })
                ->where('status', 'moved')
                ->count(),
        ];

        return $counts;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notice_weeks' => 'required',
            'notice_days' => 'required',
            'external_target' => 'required',
            'internal_target' => 'required',
            'total_target' => 'required',
            'type_of_hiring' => 'required',
            'within_sla' => 'required',
            'with_erf' => 'required',
            'erf_number' => 'nullable',
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'required',
            'program_id' => 'required',
            'site_id' => 'required',
            'created_by' => 'required',
            'date_range_id' => 'required',
            'category' => 'required',
            'approved_by' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = new Classes();
        $class->fill($request->all());
        $class->agreed_start_date = $request->input('original_start_date');
        $class->changes = 'Add Class';
        $class->save();
        $class->pushedback_id = $class->id;
        $class->save();
        $classStaffing = new ClassStaffing();
        $classStaffing->classes_id = $class->id;
        $classStaffing->show_ups_internal = '0';
        $classStaffing->show_ups_external = '0';
        $classStaffing->show_ups_total = '0';
        $classStaffing->deficit = '0';
        $classStaffing->percentage = '0';
        $classStaffing->status = '0';
        $classStaffing->day_1 = '0';
        $classStaffing->day_2 = '0';
        $classStaffing->day_3 = '0';
        $classStaffing->day_4 = '0';
        $classStaffing->day_5 = '0';
        $classStaffing->day_6 = '0';
        $classStaffing->total_endorsed = '0';
        $classStaffing->internals_hires = '0';
        $classStaffing->additional_extended_jo = '0';
        $classStaffing->with_jo = '0';
        $classStaffing->pending_jo = '0';
        $classStaffing->pending_berlitz = '0';
        $classStaffing->pending_ov = '0';
        $classStaffing->pending_pre_emps = '0';
        $classStaffing->classes_number = '0';
        $classStaffing->pipeline_total = '0';
        $classStaffing->pipeline_target = '0';
        $classStaffing->cap_starts = '0';
        $classStaffing->internals_hires_all = '0';
        $classStaffing->pipeline = '0';
        $classStaffing->additional_remarks = '0';
        $classStaffing->transaction = 'Add Class Staffing';
        $classStaffing->active_status = 1;
        $classStaffing->save();
        $classStaffing->class_staffing_id = $classStaffing->id;
        $classStaffing->save();

        $customEmail = 'padillakryss@gmail.com';

        Notification::route('mail', $customEmail)->notify(new ClassNotification($customEmail));

        return new ClassesResource($class);
    }

    public function destroy($id)
    {
        $classes = Classes::find($id);

        if (!$classes) {
            return response()->json(['error' => 'Classes not found'], 404);
        }

        $classes->delete();

        return response()->json(null, 204);
    }

    public function show($id)
    {
        $class = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])->find($id);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        return response()->json([
            'class' => $class,
        ]);
    }

    public function staffing($classesId)
    {
        $class = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])->find($classesId);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        return response()->json([
            'class' => $class,
        ]);
    }

    public function transaction($id)
    {
        $class = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])->find($id);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        $classes = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])
            ->where('pushedback_id', $class->pushedback_id)
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function classesall()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Philippines');
        })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function cStat()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Philippines');
        })
        ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
        ->get();

        return response()->json([
        'classes' => $classes,
    ]);
    }

    /*   public function dashboardClasses()
{
      $programs = Program::all();
      $dateRanges = DateRange::all();

      $groupedClasses = [];

      foreach ($programs as $program) {
          $siteName = $program->site->name;
          $programName = $program->name;
          $programId = $program->id;

          foreach ($dateRanges as $dateRange) {
              $dateRangeName = $dateRange->date_range;
              $dateRangeMonth = $dateRange->month;

              $class = Classes::where([
                  'site_id' => $program->site_id,
                  'program_id' => $programId,
                  'date_range_id' => $dateRange->id,
                  'status' => 'Active'
              ])->first();

              $totalTarget = $class ? $class->total_target : 0;

              // Build the result structure directly with a flat array.
              $groupedClasses[] = [
                  'site_name' => $siteName,
                  'program_name' => $programName,
                  'date_range' => $dateRangeName,
                  'month' => $dateRangeMonth,
                  'total_target' => $totalTarget,
              ];
          }
      }

      // Calculate total_target for each month.
      $result = [];
      foreach ($groupedClasses as $class) {
          $siteName = $class['site_name'];
          $programName = $class['program_name'];
          $dateRangeMonth = $class['month'];

          if (!isset($result[$siteName][$programName][$dateRangeMonth])) {
              $result[$siteName][$programName][$dateRangeMonth] = 0;
          }

          $result[$siteName][$programName][$dateRangeMonth] += $class['total_target'];
      }

      return response()->json([
          'classes' => $result,
      ]);
} */

    /*  public function dashboardClasses()
{
     $programs = Program::all();
     $dateRanges = DateRange::all();

     $groupedClasses = [];

     foreach ($programs as $program) {
         $siteName = $program->site->name;
         $programName = $program->name;
         $programId = $program->id;

         foreach ($dateRanges as $dateRange) {
             $dateRangeName = $dateRange->date_range;
             $dateRangeMonth = $dateRange->month;

             $class = Classes::where([
                 'site_id' => $program->site_id,
                 'program_id' => $programId,
                 'date_range_id' => $dateRange->id,
                 'status' => 'Active'
             ])->first();

             $totalTarget = $class ? $class->total_target : 0;

             $groupedClasses[$siteName][$programName]['date_ranges'][$dateRangeMonth][$dateRangeName]['total_target'] = $totalTarget;
         }
     }

     foreach ($groupedClasses as &$siteData) {
         foreach ($siteData as &$programData) {
             foreach ($programData['date_ranges'] as $month => &$monthData) {
                 $monthData['total_target'] = array_sum(array_column($monthData, 'total_target'));
             }
         }
     }

     return response()->json([
         'classes' => $groupedClasses,
     ]);
} */

    /* public function dashboardClasses()
    {
        $programs = Program::all();
        $dateRanges = DateRange::all();

        $classes = [];
        foreach ($programs as $program) {
            $programClasses = [];
            foreach ($dateRanges as $dateRange) {
                $class = Classes::where('site_id', $program->site_id)
                ->where('program_id', $program->id)
                ->where('date_range_id', $dateRange->id)
                ->where('status', 'Active')
                ->first();
                $totalTarget = $class ? $class->total_target : 0;
                $programClasses[] = [
                'date_range_id' => $dateRange->id,
                'class_id' => $class ? $class->id : 0,
                'date_range' => $dateRange->date_range,
                'month' => $dateRange->month,
                'total_target' => $totalTarget,
            ];
            }
            $classes[] = [
            'site_id' => $program->site_id,
            'site_name' => $program->site->name,
            'program_name' => $program->name,
            'program_id' => $program->id,
            'classes' => $programClasses,
        ];
        }

        $groupedClasses = [];

        foreach ($classes as $class) {
            $siteId = $class['site_name'];
            $siteName = $class['site_id'];
            $programName = $class['program_name'];
            $programId = $class['program_id'];

            if (!isset($groupedClasses[$siteId][$programName])) {
                $groupedClasses[$siteId][$programName] = [
                'date_ranges' => [],
            ];
            }

            $dateRanges = $class['classes'];

            foreach ($dateRanges as $dateRange) {
                $dateRangeName = $dateRange['date_range'];
                $dateRangeMonth = $dateRange['month'];
                $totalTarget = $dateRange['total_target'];
                $dateRangeId = $dateRange['date_range_id'];
                $classId = $dateRange['class_id'];
                if (!isset($groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeMonth])) {
                    $groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeMonth] = [
                    'total_target' => 0,
                    //'program_id' => $programId,
                    'month' => $dateRangeMonth,
                    //'date_ranges' => [],
                ];
                }

                $groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeMonth]['total_target'] += $totalTarget;
                $groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeMonth]['date_ranges'][] = [
                //'date_range_id' => $dateRangeId,
                //'class_id' => $classId,
                'date_range' => $dateRangeName,
                'total_target' => $totalTarget,
            ];
            }
        }
        foreach ($groupedClasses as &$siteData) {
            foreach ($siteData as &$programData) {
                foreach ($programData['date_ranges'] as $month => &$monthData) {
                    $totalTarget = 0;
                    foreach ($monthData['date_ranges'] as $dateRange) {
                        $totalTarget += $dateRange['total_target'];
                    }
                    $monthData['total_target'] = $totalTarget;
                }
            }
        }

        return response()->json([
        'classes' => $groupedClasses,
    ]);
    } */
    /* public function dashboardClasses()
    {
        // Fetch programs with their associated site
        $programs = Program::with('site')->get();
        $dateRanges = DateRange::all();

        $groupedClasses = [];

        foreach ($programs as $program) {
            $siteId = $program->site_id;
            $siteName = $program->site->name;
            $programName = $program->name;
            $programId = $program->id;

            foreach ($dateRanges as $dateRange) {
                $class = Classes::where('site_id', $siteId)
                ->where('program_id', $programId)
                ->where('date_range_id', $dateRange->id)
                ->where('status', 'Active')
                ->first();

                $totalTarget = $class ? $class->total_target : 0;

                if (!isset($groupedClasses[$siteName][$programName][$dateRange->month])) {
                    $groupedClasses[$siteName][$programName][$dateRange->month] = [
                    'total_target' => 0,
                    'date_ranges' => [],
                ];
                }

                $groupedClasses[$siteName][$programName][$dateRange->month]['total_target'] += $totalTarget;
                $groupedClasses[$siteName][$programName][$dateRange->month]['date_ranges'][] = [
                'date_range' => $dateRange->date_range,
                'total_target' => $totalTarget,
            ];
            }
        }

        // Calculate the total_target for each month and for the entire program
        foreach ($groupedClasses as &$siteData) {
            foreach ($siteData as &$programData) {
                $programTotal = 0;
                foreach ($programData as $month => &$monthData) {
                    $totalTarget = array_sum(array_column($monthData['date_ranges'], 'total_target'));
                    $monthData['total_target'] = $totalTarget;
                    $programTotal += $totalTarget;
                }
                $programData['program_total_target'] = $programTotal;
            }
        }

        return response()->json([
        'classes' => $groupedClasses,
    ]);
    } */

    /*   public function dashboardClasses()
      {
          // Fetch programs with their associated site
          $programs = Program::with('site')->get();
          $dateRanges = DateRange::all();

          $groupedClasses = [];

          foreach ($programs as $program) {
              $siteName = $program->site->name;
              $programName = $program->name;

              foreach ($dateRanges as $dateRange) {
                  $daterangeName = $dateRange->date_range;
                  $programId = $program->id;
                  $month = $dateRange->month;

                  $classes = Classes::where('site_id', $program->site_id)
                  ->where('program_id', $programId)
                  ->where('date_range_id', $dateRange->id)
                  ->where('status', 'Active')
                  ->get();

                  $totalTarget = $classes->sum('total_target');

                  // Populate the data structure directly
                  if (!isset($groupedClasses[$siteName][$programName][$month])) {
                      $groupedClasses[$siteName][$programName][$month] = [
                      'total_target' => 0,
                      'date_ranges' => [],
                  ];
                  }

                  $groupedClasses[$siteName][$programName][$month]['total_target'] += $totalTarget;
                  $groupedClasses[$siteName][$programName][$month]['date_ranges'][$daterangeName] = $totalTarget;
              }
          }

          return response()->json([
          'classes' => $groupedClasses,
      ]);
      } */
    public function dashboardClasses()
     {
         $programs = Program::with('site')->get();
         $dateRanges = DateRange::all();

         $groupedClasses = [];

         foreach ($programs as $program) {
             $siteName = $program->site->name;
             $programName = $program->name;

             foreach ($dateRanges as $dateRange) {
                 $daterangeName = $dateRange->date_range;
                 $programId = $program->id;
                 $month = $dateRange->month;

                 $classes = Classes::where('site_id', $program->site_id)
                        ->where('program_id', $programId)
                        ->where('date_range_id', $dateRange->id)
                        ->where('status', 'Active')
                        ->get();

                 $totalTarget = $classes->sum('total_target');

                 if (!isset($groupedClasses[$siteName][$programName][$month])) {
                     $groupedClasses[$siteName][$programName][$month] = [
                            'date_ranges' => [],
                            'total_target' => 0,
                        ];
                 }
                 $groupedClasses[$siteName][$programName][$month]['total_target'] += $totalTarget;
                 $groupedClasses[$siteName][$programName][$month]['date_ranges'][$daterangeName] = [
                        'total_target' => $totalTarget,
                    ];
             }
         }

         logger('Grouped Classes:', $groupedClasses);

         return response()->json([
         'classes' => $groupedClasses,
     ]);
     }

     /* public function dashboardClasses()
    {
        $programs = Program::with('site')->get();
        $dateRanges = DateRange::all();

        $flatClasses = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            foreach ($dateRanges as $dateRange) {
                $daterangeName = $dateRange->date_range;
                $programId = $program->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                      ->where('program_id', $programId)
                      ->where('date_range_id', $dateRange->id)
                      ->where('status', 'Active')
                      ->get();

                $totalTarget = $classes->sum('total_target');

                $flatClasses[] = [
                    'site_name' => $siteName,
                    'program_name' => $programName,
                    'month' => $month,
                    'date_range' => $daterangeName,
                    'total_target' => $totalTarget,
                ];
            }
        }

        return response()->json([
            'classes' => $flatClasses,
        ]);
    } */
    /*  public function dashboardClasses()
     {
         $flatClasses = DB::table('programs')
             ->select('sites.name as site_name', 'programs.name as program_name', 'date_ranges.month', 'date_ranges.date_range', DB::raw('SUM(classes.total_target) as total_target'))
             ->leftJoin('sites', 'programs.site_id', '=', 'sites.id')
             ->leftJoin('classes', function ($join) {
                 $join->on('programs.id', '=', 'classes.program_id')
                     ->where('classes.status', '=', 'Active');
             })
             ->leftJoin('date_ranges', 'classes.date_range_id', '=', 'date_ranges.id')
             ->groupBy('sites.name', 'programs.name', 'date_ranges.month', 'date_ranges.date_range')
             ->get();

         return response()->json([
             'classes' => $flatClasses,
         ]);
     } */
      /* public function dashboardClasses()
      {
          $programs = Program::with('site')
              ->with('classes')
              ->get();

          $dateRanges = DateRange::all();

          $groupedClasses = [];

          foreach ($programs as $program) {
              $siteName = $program->site->name;
              $programName = $program->name;
              $programId = $program->id;

              foreach ($dateRanges as $dateRange) {
                  $dateRangeName = $dateRange->date_range;
                  $dateRangeMonth = $dateRange->month;

                  $class = $program->classes
                      ->where('date_range_id', $dateRange->id)
                      ->where('status', 'Active')
                      ->first();

                  $totalTarget = $class ? $class->total_target : 0;

                  if (!isset($groupedClasses[$siteName][$programName])) {
                      $groupedClasses[$siteName][$programName] = [
                          'date_ranges' => [],
                      ];
                  }

                  if (!isset($groupedClasses[$siteName][$programName]['date_ranges'][$dateRangeMonth])) {
                      $groupedClasses[$siteName][$programName]['date_ranges'][$dateRangeMonth] = [
                          'total_target' => 0,
                          'month' => $dateRangeMonth,
                      ];
                  }

                  $groupedClasses[$siteName][$programName]['date_ranges'][$dateRangeMonth]['total_target'] += $totalTarget;

                  $groupedClasses[$siteName][$programName]['date_ranges'][$dateRangeMonth]['date_ranges'][] = [
                      'date_range' => $dateRangeName,
                      'total_target' => $totalTarget,
                  ];
              }
          }

          foreach ($groupedClasses as &$siteData) {
              foreach ($siteData as &$programData) {
                  foreach ($programData['date_ranges'] as $month => &$monthData) {
                      $totalTarget = 0;
                      foreach ($monthData['date_ranges'] as $dateRange) {
                          $totalTarget += $dateRange['total_target'];
                      }
                      $monthData['total_target'] = $totalTarget;
                  }
              }
          }

          return response()->json([
              'classes' => $groupedClasses,
          ]);
      } */
        /* public function dashboardClasses()
        {
            $dateRanges = DateRange::all();

            $groupedClasses = Program::with('site')
                ->leftJoin('classes', function($join) use ($dateRanges) {
                    $join->on('programs.site_id', '=', 'classes.site_id')
                         ->on('programs.id', '=', 'classes.program_id')
                         ->on('classes.status', '=', \DB::raw("'Active'"));
                })
                ->whereIn('classes.date_range_id', $dateRanges->pluck('id'))
                ->get()
                ->groupBy(['site.name', 'name'])
                ->map(function($group) use ($dateRanges) {
                    return $dateRanges->map(function($dateRange) use ($group) {
                        $class = $group->where('date_range_id', $dateRange->id)->first();
                        $totalTarget = $class ? $class->total_target : 0;

                        return [
                            'date_range' => $dateRange->date_range,
                            'month' => $dateRange->month,
                            'total_target' => $totalTarget,
                        ];
                    })->toArray();
                });

            return response()->json([
                'classes' => $groupedClasses,
            ]);
        } */
   /*  public function dashboardClasses()
    {
        $classes = DB::table('classes')
        ->leftjoin('date_ranges', 'classes.date_range_id', '=', 'date_ranges.id')
        ->leftjoin('programs', 'classes.program_id', '=', 'programs.id')
        ->leftjoin('sites', 'programs.site_id', '=', 'sites.id')
        ->select(
            'sites.name AS site_name',
            'programs.name AS program_name',
            'date_ranges.date_range AS date_range',

            //'classes.total_target'
        )
        ->groupBy('sites.name', 'programs.name', 'date_ranges.date_range')
        ->get();

        return response()->json(['classes' => $classes]);
    } */

    public function classesallInd()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'India');
        })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function classesallJam()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Jamaica');
        })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function classesallGua()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Guatemala');
        })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function pushedback(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notice_weeks' => 'required',
            'notice_days' => 'required',
            'external_target' => 'required',
            'internal_target' => 'required',
            'total_target' => 'required',
            'type_of_hiring' => 'required',
            'within_sla' => 'required',
            'with_erf' => 'required',
            'erf_number' => 'nullable',
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'nullable',
            'program_id' => 'required',
            'site_id' => 'required',
            'date_range_id' => 'required',
            'category' => 'required',
            'requested_by' => 'required',
            'agreed_start_date' => 'required',
            'approved_by' => 'required',
            'updated_by' => 'required',
            'changes' => 'required',
            'wf' => 'nullable',
            'tr' => 'nullable',
            'op' => 'nullable',
            'ta' => 'nullable',
            'cl' => 'nullable',
        ]);
        $requested_by = [$request->requested_by];
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);
        $class->status = 'Moved';
        $class->save();
        $newClass = $class->replicate();
        $newClass->update_status = $class->update_status + 1;
        $newClass->changes = 'Pushedback';
        $newClass->requested_by = json_encode($requested_by);
        $newClass->fill($request->all());
        $newClass->save();

        return new ClassesResource($newClass);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notice_weeks' => 'required',
            'notice_days' => 'required',
            'external_target' => 'required',
            'internal_target' => 'required',
            'total_target' => 'required',
            'type_of_hiring' => 'required',
            'within_sla' => 'required',
            'with_erf' => 'required',
            'erf_number' => 'nullable',
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'nullable',
            'program_id' => 'required',
            'site_id' => 'required',
            'date_range_id' => 'required',
            'category' => 'required',
            'requested_by' => 'required',
            'agreed_start_date' => 'required',
            'approved_by' => 'required',
            'updated_by' => 'required',
            'changes' => 'required',
            'wf' => 'nullable',
            'tr' => 'nullable',
            'op' => 'nullable',
            'ta' => 'nullable',
            'cl' => 'nullable',
            'wave_no' => 'nullable',
        ]);
        $requested_by = [$request->requested_by];
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);
        $class->fill($request->all());
        $class->requested_by = json_encode($requested_by);
        $class->save();

        return new ClassesResource($class);
    }

    public function cancel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'site_id' => 'required',
            'program_id' => 'required',
            'date_range_id' => 'required',
            'cancelled_by' => 'required',
            'wf' => 'nullable',
            'tr' => 'nullable',
            'op' => 'nullable',
            'ta' => 'nullable',
            'cl' => 'nullable',
            'cancelled_date' => 'required',
        ]);
        $cancelled_by = [$request->cancelled_by];

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);
        $class->status = 'Cancelled';
        $class->save();
        $newClass = $class->replicate();
        $newClass->cancelled_by = json_encode($cancelled_by);
        $newClass->changes = 'Cancellation';
        $newClass->cancelled_date = $request->input('cancelled_date');
        $newClass->save();

        return new ClassesResource($class);
    }
}
