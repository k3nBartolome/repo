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
        $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser', 'approvedByUser'])->get();
        $classesData = ClassesResource::collection($classes);

        return response()->json([
            'classes' => $classesData,
        ]);
    }

    public function clark()
    {
        $clark1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 1)
            ->get();
            $classesData = ClassesResource::collection($classes);
            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $clark2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 2)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $clark3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 3)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $clark4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 4)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $clark5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 5)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $clark6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 6)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $clark7 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 7)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $clark8 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 8)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $clark9 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 9)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $clark10 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 10)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $clark11 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 11)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $clark12 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 12)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $clark13 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 13)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $clark14 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 14)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $clark15 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 15)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $clark16 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 1)
            ->where('program_id', 16)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $clark17 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 1)
        ->where('program_id', 17)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
    ])->toArray();

            return $groupedData;
        };

        $clark18 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 1)
        ->where('program_id', 18)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
    ])->toArray();

            return $groupedData;
        };
        $clark19 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 1)
        ->where('program_id', 19)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
    ])->toArray();

            return $groupedData;
        };

        $clark20 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 1)
        ->where('program_id', 20)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
    ])->toArray();

            return $groupedData;
        };
        $clark21 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 1)
        ->where('program_id', 21)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
    ])->toArray();

            return $groupedData;
        };
        $clark22 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 1)
        ->where('program_id', 22)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
    ])->toArray();

            return $groupedData;
        };
        $clark23 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 1)
        ->where('program_id', 23)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
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
            'clark21' => $clark21(),
            'clark22' => $clark22(),
            'clark23' => $clark23(),
        ]);
    }

    public function quezoncity()
    {
        $quezoncity1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
            ->where('site_id', 2)
            ->where('program_id', 24)
            ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
            'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 25)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 26)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 27)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 28)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 29)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity7 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 30)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity8 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 31)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        $quezoncity9 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 32)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity10 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 33)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity11 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 34)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity12 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 35)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity13 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 36)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity14 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 37)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity15 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 38)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity16 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 39)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity17 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 40)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity18 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 41)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity19 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 42)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity20 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 43)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity21 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 44)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity22 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 45)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $quezoncity23 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 2)
        ->where('program_id', 46)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
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
            'quezoncity21' => $quezoncity21(),
            'quezoncity22' => $quezoncity22(),
            'quezoncity23' => $quezoncity23(),
        ]);
    }

    public function bridgetowne()
    {
        $bridgetowne1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 47)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $bridgetowne2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 48)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $bridgetowne3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 49)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $bridgetowne4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 50)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $bridgetowne5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 51)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $bridgetowne6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 52)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $bridgetowne7 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 53)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $bridgetowne8 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 54)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $bridgetowne9 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 55)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $bridgetowne10 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 56)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $bridgetowne11 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 3)
        ->where('program_id', 57)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        return response()->json([
            'bridgetowne1' => $bridgetowne1(),
            'bridgetowne2' => $bridgetowne2(),
            'bridgetowne3' => $bridgetowne3(),
            'bridgetowne4' => $bridgetowne4(),
            'bridgetowne5' => $bridgetowne5(),
            'bridgetowne6' => $bridgetowne6(),
            'bridgetowne7' => $bridgetowne7(),
            'bridgetowne8' => $bridgetowne8(),
            'bridgetowne9' => $bridgetowne9(),
            'bridgetowne10' => $bridgetowne10(),
            'bridgetowne11' => $bridgetowne11(),
        ]);
    }

    public function makati()
    {
        $makati1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 58)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 59)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 60)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 61)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 62)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 63)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati7 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 64)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati8 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 65)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati9 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 66)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati10 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 67)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati11 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 67)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati12 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 68)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati13 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 69)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati14 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 70)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati15 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 71)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati16 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 72)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $makati17 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 4)
        ->where('program_id', 73)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        return response()->json([
            'makati1' => $makati1(),
            'makati2' => $makati2(),
            'makati3' => $makati3(),
            'makati4' => $makati4(),
            'makati5' => $makati5(),
            'makati6' => $makati6(),
            'makati7' => $makati7(),
            'makati8' => $makati8(),
            'makati9' => $makati9(),
            'makati10' => $makati10(),
            'makati11' => $makati11(),
            'makati12' => $makati12(),
            'makati13' => $makati13(),
            'makati14' => $makati14(),
            'makati15' => $makati15(),
            'makati16' => $makati16(),
            'makati17' => $makati17(),
        ]);
    }

    public function moa()
    {
        $moa1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 5)
        ->where('program_id', 74)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $moa2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 5)
        ->where('program_id', 75)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $moa3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 5)
        ->where('program_id', 76)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $moa4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 5)
        ->where('program_id', 77)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $moa5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 5)
        ->where('program_id', 78)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        return response()->json([
            'moa1' => $moa1(),
            'moa2' => $moa2(),
            'moa3' => $moa3(),
            'moa4' => $moa4(),
            'moa5' => $moa5(),
        ]);
    }

    public function dvsm()
    {
        $dvsm1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 6)
        ->where('program_id', 79)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvsm2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 6)
        ->where('program_id', 80)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvsm3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 6)
        ->where('program_id', 81)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvsm4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 6)
        ->where('program_id', 82)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvsm5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 6)
        ->where('program_id', 83)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvsm6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 6)
        ->where('program_id', 84)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvsm7 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 6)
        ->where('program_id', 85)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        return response()->json([
            'dvsm1' => $dvsm1(),
            'dvsm2' => $dvsm2(),
            'dvsm3' => $dvsm3(),
            'dvsm4' => $dvsm4(),
            'dvsm5' => $dvsm5(),
            'dvsm6' => $dvsm6(),
            'dvsm7' => $dvsm7(),
        ]);
    }

    public function dvrob()
    {
        $dvrob1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 7)
        ->where('program_id', 86)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvrob2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 7)
        ->where('program_id', 87)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvrob3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 7)
        ->where('program_id', 88)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvrob4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 7)
        ->where('program_id', 89)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvrob5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 7)
        ->where('program_id', 90)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvrob6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 7)
        ->where('program_id', 91)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvrob7 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 7)
        ->where('program_id', 92)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        return response()->json([
            'dvrob1' => $dvrob1(),
            'dvrob2' => $dvrob2(),
            'dvrob3' => $dvrob3(),
            'dvrob4' => $dvrob4(),
            'dvrob5' => $dvrob5(),
            'dvrob6' => $dvrob6(),
            'dvrob7' => $dvrob7(),
        ]);
    }

    public function dvdelta()
    {
        $dvdelta1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 8)
        ->where('program_id', 93)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvdelta2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 8)
        ->where('program_id', 94)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvdelta3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 8)
        ->where('program_id', 95)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvdelta4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 8)
        ->where('program_id', 96)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvdelta5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 8)
        ->where('program_id', 97)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvdelta6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 8)
        ->where('program_id', 98)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        return response()->json([
            'dvdelta1' => $dvdelta1(),
            'dvdelta2' => $dvdelta2(),
            'dvdelta3' => $dvdelta3(),
            'dvdelta4' => $dvdelta4(),
            'dvdelta5' => $dvdelta5(),
            'dvdelta6' => $dvdelta6(),
        ]);
    }

    public function dvcentral()
    {
        $dvcentral1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 99)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 100)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 101)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 102)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 103)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 104)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral7 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 105)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral8 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 106)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral9 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 107)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral10 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 108)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral11 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 109)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral12 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 110)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral13 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 111)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral14 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 112)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral15 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 113)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral16 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 114)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral17 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 115)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral18 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 116)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral19 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 117)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral20 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 118)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral21 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 119)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral22 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 120)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral23 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 121)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral24 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 122)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dvcentral25 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 9)
        ->where('program_id', 123)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        return response()->json([
            'dvcentral1' => $dvcentral1(),
            'dvcentral2' => $dvcentral2(),
            'dvcentral3' => $dvcentral3(),
            'dvcentral4' => $dvcentral4(),
            'dvcentral5' => $dvcentral5(),
            'dvcentral6' => $dvcentral6(),
            'dvcentral7' => $dvcentral7(),
            'dvcentral8' => $dvcentral8(),
            'dvcentral9' => $dvcentral9(),
            'dvcentral10' => $dvcentral10(),
            'dvcentral11' => $dvcentral11(),
            'dvcentral12' => $dvcentral12(),
            'dvcentral13' => $dvcentral13(),
            'dvcentral14' => $dvcentral14(),
            'dvcentral15' => $dvcentral15(),
            'dvcentral16' => $dvcentral16(),
            'dvcentral17' => $dvcentral17(),
            'dvcentral18' => $dvcentral18(),
            'dvcentral19' => $dvcentral19(),
            'dvcentral20' => $dvcentral20(),
            'dvcentral21' => $dvcentral21(),
            'dvcentral22' => $dvcentral22(),
            'dvcentral23' => $dvcentral23(),
            'dvcentral24' => $dvcentral24(),
            'dvcentral25' => $dvcentral25(),
        ]);
    }

    public function dfc()
    {
        $dfc1 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 10)
        ->where('program_id', 124)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dfc2 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 10)
        ->where('program_id', 125)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dfc3 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 10)
        ->where('program_id', 126)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dfc4 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 10)
        ->where('program_id', 127)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dfc5 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 10)
        ->where('program_id', 128)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };
        $dfc6 = function () {
            $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
        ->where('site_id', 10)
        ->where('program_id', 129)
        ->get();

            $classesData = ClassesResource::collection($classes);

            $groupedData = $classesData->groupBy([
        'date_range_id',
        ])->toArray();

            return $groupedData;
        };

        return response()->json([
            'dfc1' => $dfc1(),
            'dfc2' => $dfc2(),
            'dfc3' => $dfc3(),
            'dfc4' => $dfc4(),
            'dfc5' => $dfc5(),
            'dfc6' => $dfc6(),
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
            'date_range_id' => 'required',
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
