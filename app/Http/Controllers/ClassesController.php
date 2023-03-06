<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Models\Sla_reason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    public function index()
    {
        $classes = Classes::with(['sla_reason', 'site', 'program', 'createdByUser', 'updatedByUser', 'cancelledByUser', 'approvedByUser'])->get();
        $classesData = ClassesResource::collection($classes);

        return response()->json([
            'classes' => $classesData,
        ]);
    }

    public function clark()
    {
        $clark1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 1)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $clark2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 2)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $clark3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 3)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $clark4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 4)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $clark5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 5)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $clark6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 6)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $clark7 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 7)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $clark8 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 8)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $clark9 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 9)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $clark10 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 10)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $clark11 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 11)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $clark12 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 12)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $clark13 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 13)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $clark14 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 14)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $clark15 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 15)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $clark16 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 1)
            ->where('program_id', 16)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $clark17 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 1)
        ->where('program_id', 17)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
    ])->toArray();

            return $groupedData;
        };

        $clark18 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 1)
        ->where('program_id', 18)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
    ])->toArray();

            return $groupedData;
        };
        $clark19 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 1)
        ->where('program_id', 19)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
    ])->toArray();

            return $groupedData;
        };

        $clark20 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 1)
        ->where('program_id', 20)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
    ])->toArray();

            return $groupedData;
        };

        return response()->json([
            'clark1' => $clark1(),
            'clark2' => $clark2(),
            'clark3' => $clark3(),
            'clark4' => $clark4(),
            'clark5' => $clark5(),
            'clark6' => $clark6(),
            'clark7' => $clark7(),
            'clark8' => $clark8(),
            'clark9' => $clark9(),
            'clark10' => $clark10(),
            'clark11' => $clark11(),
            'clark12' => $clark12(),
            'clark13' => $clark13(),
            'clark14' => $clark14(),
            'clark15' => $clark15(),
            'clark16' => $clark16(),
            'clark17' => $clark17(),
            'clark18' => $clark18(),
            'clark19' => $clark19(),
            'clark20' => $clark20(),
        ]);
    }

    public function quezoncity()
    {
        $quezoncity1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
            ->where('site_id', 2)
            ->where('program_id', 21)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 22)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 23)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 24)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 25)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 26)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity7 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 27)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity8 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 28)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity9 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 29)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity10 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 30)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity11 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 31)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity12 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 32)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity13 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 33)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity14 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 34)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity15 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 35)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity16 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 36)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity17 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 37)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity18 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 38)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity19 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 39)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity20 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program'])
        ->where('site_id', 2)
        ->where('program_id', 40)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'weeks_start',
        ])->toArray();

            return $groupedData;
        };

        return response()->json([
            'quezoncity1' => $quezoncity1(),
            'quezoncity2' => $quezoncity2(),
            'quezoncity3' => $quezoncity3(),
            'quezoncity4' => $quezoncity4(),
            'quezoncity5' => $quezoncity5(),
            'quezoncity6' => $quezoncity6(),
            'quezoncity7' => $quezoncity7(),
            'quezoncity8' => $quezoncity8(),
            'quezoncity9' => $quezoncity9(),
            'quezoncity10' => $quezoncity10(),
            'quezoncity11' => $quezoncity11(),
            'quezoncity12' => $quezoncity12(),
            'quezoncity13' => $quezoncity13(),
            'quezoncity14' => $quezoncity14(),
            'quezoncity15' => $quezoncity15(),
            'quezoncity16' => $quezoncity16(),
            'quezoncity17' => $quezoncity17(),
            'quezoncity18' => $quezoncity18(),
            'quezoncity19' => $quezoncity19(),
            'quezoncity20' => $quezoncity20(),
        ]);
    }

    public function bridgetowne()
    {
        $classes = Classes::with(['sla_reason', 'site', 'program'])
                            ->where('site_id', 3)
                            ->get();
        $classesData = ClassesResource::collection($classes);

        $groupedData = $classesData->groupBy('weeks_start')->toArray();

        return response()->json([
            'classes' => $groupedData,
        ]);
    }

    public function makati()
    {
        $classes = Classes::with(['sla_reason', 'site', 'program'])
                            ->where('site_id', 4)
                            ->get();
        $classesData = ClassesResource::collection($classes);

        $groupedData = $classesData->groupBy('weeks_start')->toArray();

        return response()->json([
            'classes' => $groupedData,
        ]);
    }

    public function moa()
    {
        $classes = Classes::with(['sla_reason', 'site', 'program'])
                            ->where('site_id', 5)
                            ->get();
        $classesData = ClassesResource::collection($classes);

        $groupedData = $classesData->groupBy('weeks_start')->toArray();

        return response()->json([
            'classes' => $groupedData,
        ]);
    }

    public function dvsm()
    {
        $classes = Classes::with(['sla_reason', 'site', 'program'])
                            ->where('site_id', 6)
                            ->get();
        $classesData = ClassesResource::collection($classes);

        $groupedData = $classesData->groupBy('weeks_start')->toArray();

        return response()->json([
            'classes' => $groupedData,
        ]);
    }

    public function dvrob()
    {
        $classes = Classes::with(['sla_reason', 'site', 'program'])
                            ->where('site_id', 7)
                            ->get();
        $classesData = ClassesResource::collection($classes);

        $groupedData = $classesData->groupBy('weeks_start')->toArray();

        return response()->json([
            'classes' => $groupedData,
        ]);
    }

    public function dvdelta()
    {
        $classes = Classes::with(['sla_reason', 'site', 'program'])
                            ->where('site_id', 8)
                            ->get();
        $classesData = ClassesResource::collection($classes);

        $groupedData = $classesData->groupBy('weeks_start')->toArray();

        return response()->json([
            'classes' => $groupedData,
        ]);
    }

    public function dvcentral()
    {
        $classes = Classes::with(['sla_reason', 'site', 'program'])
                            ->where('site_id', 9)
                            ->get();
        $classesData = ClassesResource::collection($classes);

        $groupedData = $classesData->groupBy('weeks_start')->toArray();

        return response()->json([
            'classes' => $groupedData,
        ]);
    }

    public function dfc()
    {
        $classes = Classes::with(['sla_reason', 'site', 'program'])
                            ->where('site_id', 10)
                            ->get();
        $classesData = ClassesResource::collection($classes);

        $groupedData = $classesData->groupBy('weeks_start')->toArray();

        return response()->json([
            'classes' => $groupedData,
        ]);
    }

    public function show(Classes $class)
    {
        $class->load('sla_reason');

        return new ClassesResource($class);
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
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'required',
            'program_id' => 'required',
            'site_id' => 'required',
            'created_by' => 'required',
            'is_active' => 'required',
            'weeks_start' => 'required',
            'backfill' => 'required',
            'growth' => 'required',
            'category' => 'required',
            'reason' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::create($request->all());

        $slaReason = new Sla_reason(['reason' => $request->input('reason')]);
        $slaReason->classes_id = $class->id;
        $slaReason->save();

        return new ClassesResource($class);
    }

    public function update(Request $request, Classes $class)
    {
        $validatedData = $request->validate([
            'notice_weeks' => 'required',
            'notice_days' => 'required',
            'external_target' => 'required',
            'internal_target' => 'required',
            'total_target' => 'required',
            'type_of_hiring' => 'required',
            'within_sla' => 'required',
            'with_erf' => 'required',
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'required',
            'program_id' => 'required',
            'site_id' => 'required',
            'created_by' => 'required',
            'is_active' => 'required',
            'reason' => 'required',
        ]);

        if ($validatedData['name']) {
            $class->name = $validatedData['name'];
        }

        if ($validatedData['description']) {
            $class->description = $validatedData['description'];
        }

        $class->save();

        if ($validatedData['reason']) {
            $class->site_id = $validatedData['site_id'];
            $class->program_id = $validatedData['program_id'];
            $class->type_of_hiring = $validatedData['type_of_hiring'];
            $class->external_target = $validatedData['external_target'];
            $class->internal_target = $validatedData['internal_target'];
            $class->total_target = $validatedData['total_target'];
            $class->original_start_date = $validatedData['original_start_date'];
            $class->wfm_requested_date = $validatedData['wfm_requested_date'];
            $class->notice_days = $validatedData['notice_days'];
            $class->notice_weeks = $validatedData['notice_weeks'];
            $class->with_erf = $validatedData['with_erf'];
            $class->category = $validatedData['category'];
            $class->within_sla = $validatedData['within_sla'];
            $class->approved_status = $validatedData['approved_status'];
            $class->created_by = $validatedData['updated_by'];
            $class->is_active = $validatedData['is_active'];
        }

        return new ClassesResource($class);
    }

    public function destroy(Classes $class)
    {
        $class->delete();

        return response(null, 204);
    }
}
