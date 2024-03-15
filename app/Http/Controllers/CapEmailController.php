<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\DateRange;
use App\Models\Program;
use App\Models\Site;
use App\Models\SmartRecruitData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CapEmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $mappedGroupedClasses = $this->retrieveDataForEmail();
        $mappedClasses = $this->retrieveDataForClassesEmail();
        $mappedB2Classes = $this->retrieveB2DataForEmail();

        $recipients = ['kryss.bartolome@vxi.com', 'arielito.pascua@vxi.com', 'Philipino.Mercado@vxi.com', 'Aina.Dytioco@vxi.com', 'Ann.Gomez@vxi.com', 'Jemalyn.Fabiano@vxi.com', 'Kathryn.Olis@vxi.com', 'Jay.Juliano@vxi.com', 'Yen.Gelido-Alejandro@vxi.com'];
        $subject = 'PH TA Capacity File - as of ' . date('F j, Y');

        Mail::send('email', ['mappedGroupedClasses' => $mappedGroupedClasses, 'mappedClasses' => $mappedClasses, 'mappedB2Classes' => $mappedB2Classes], function ($message) use ($recipients, $subject) {
            $message->from('TA.Insights@vxi.com', 'TA Reports');
            $message->to($recipients);
            $message->subject($subject);
        });

        return response()->json(['message' => 'Email sent successfully']);
    }

    public function sendSR(Request $request)
    {
        $mappedResult = $this->srComplianceExport();
        $formattedResult = $this->AutomatedSrExport();

        $recipients = ['kryss.bartolome@vxi.com', 'PH_Talent_Acquisition_Management_Team@vxi.com', 'PH_Talent_Acquisition_Leaders@vxi.com'];
        $subject = 'SR Pending Movement - as of ' . date('F j, Y');

        Mail::send('sr_pending_email', ['mappedResult' => $mappedResult, 'formattedResult' => $formattedResult], function ($message) use ($recipients, $subject) {
            $message->from('TA.Insights@vxi.com', 'TA Reports');
            $message->to($recipients);
            $message->subject($subject);
        });

        return response()->json(['message' => 'Email sent successfully']);
    }

    public function retrieveDataForEmail()
    {
        $programs = Site::where('is_active', 1)->get();

        $year = 2024;
        $dateRanges = DateRange::select('month_num')->where('year', $year)->groupBy('month_num')->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->name;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $month = $dateRange->month_num;
                $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByProgram[$siteName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['total_target' => 0];
                }
                $groupedClasses[$siteName][$month]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
            }
            $grandTotal = $grandTotalByProgram[$siteName];
            if ($grandTotal != 0) {
                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'January' => $weeklyData['1'] ?: '',
                    'February' => $weeklyData['2'] ?: '',
                    'March' => $weeklyData['3'] ?: '',
                    'April' => $weeklyData['4'] ?: '',
                    'May' => $weeklyData['5'] ?: '',
                    'June' => $weeklyData['6'] ?: '',
                    'July' => $weeklyData['7'] ?: '',
                    'August' => $weeklyData['8'] ?: '',
                    'September' => $weeklyData['9'] ?: '',
                    'October' => $weeklyData['10'] ?: '',
                    'November' => $weeklyData['11'] ?: '',
                    'December' => $weeklyData['12'] ?: '',
                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum($grandTotalByProgram);

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'January' => $grandTotalByWeek['1'] ?: '',
            'February' => $grandTotalByWeek['2'] ?: '',
            'March' => $grandTotalByWeek['3'] ?: '',
            'April' => $grandTotalByWeek['4'] ?: '',
            'May' => $grandTotalByWeek['5'] ?: '',
            'June' => $grandTotalByWeek['6'] ?: '',
            'July' => $grandTotalByWeek['7'] ?: '',
            'August' => $grandTotalByWeek['8'] ?: '',
            'September' => $grandTotalByWeek['9'] ?: '',
            'October' => $grandTotalByWeek['10'] ?: '',
            'November' => $grandTotalByWeek['11'] ?: '',
            'December' => $grandTotalByWeek['12'] ?: '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];
        return $mappedGroupedClasses;
    }
    //sr
    public function srComplianceExport()
    {
        $appstepIDs = [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 32, 33, 34, 36, 40, 41, 42, 43, 44, 45, 46, 50, 53, 54, 55, 56, 59, 60, 69, 70, 73, 74, 78, 80, 81, 87, 88];

        $latestDate = SmartRecruitData::on('secondary_sqlsrv')->max('last_update_date');

        $query = SmartRecruitData::on('secondary_sqlsrv')
            ->select('Step', 'AppStep', 'Site', DB::raw('COUNT(*) as Count'))
            ->groupBy('Step', 'AppStep', 'Site')
            ->orderBy('Step')
            ->orderBy('AppStep')
            ->whereIn('ApplicationStepStatusId', $appstepIDs)
            ->where(function ($query) use ($latestDate) {
                $query->where('QueueDate', '>=', '20240101')
                    ->where('QueueDate', '<=', $latestDate);
            })
            ->orderBy('Site');


        $result = $query->get();
        $groupedData = [];
        $totalStepCounts = [];
        $totalAppStepCounts = [];
        $totalSiteCounts = [];

        foreach ($result as $item) {
            $step = $item->Step;
            $appStep = $item->AppStep;
            $site = $item->Site;

            if (!isset($groupedData[$step][$appStep])) {
                $groupedData[$step][$appStep] = [
                    'Bridgetowne' => 0,
                    'Clark' => 0,
                    'Davao' => 0,
                    'Makati' => 0,
                    'MOA' => 0,
                    'QC North EDSA' => 0,
                ];
            }

            $groupedData[$step][$appStep][$site] += $item->Count;
            $totalStepCounts[$step] = isset($totalStepCounts[$step]) ? $totalStepCounts[$step] + $item->Count : $item->Count;
            $totalAppStepCounts[$step][$appStep] = isset($totalAppStepCounts[$step][$appStep]) ? $totalAppStepCounts[$step][$appStep] + $item->Count : $item->Count;
            $totalSiteCounts[$step][$site] = (isset($item->Count) && $item->Count > 0) ? (isset($totalSiteCounts[$step][$site]) ? $totalSiteCounts[$step][$site] + $item->Count : $item->Count) : 0;
        }

        $formattedResult = [];
        $maxAppSteps = [];
        $mappedResult = [];

        foreach ($groupedData as $step => $appSteps) {
            $maxAppStep = null;
            $maxAppStepCount = 0;

            foreach ($appSteps as $appStep => $siteCounts) {
                $totalCount = $totalAppStepCounts[$step][$appStep];

                if ($totalCount > $maxAppStepCount) {
                    $maxAppStepCount = $totalCount;
                    $maxAppStep = $appStep;
                }
            }

            $maxAppSteps[$step] = $maxAppStep;

            $formattedSiteCounts = [];
            if ($maxAppStep !== null) {
                foreach ($appSteps[$maxAppStep] as $site => $count) {
                    $formattedSiteCounts[$site] = number_format($count);
                }

                $formattedResult[] = array_merge(
                    ['Step' => $step, 'MaxAppStep' => $maxAppStep, 'MaxAppStepCount' => number_format($totalAppStepCounts[$step][$maxAppStep]), 'StepName' => $step],
                    $formattedSiteCounts
                );
                $mappedResult['2. ONLINE ASSESSMENT'] = [
                    'MaxAppStep' => isset($formattedResult[0]) ? $formattedResult[0]['MaxAppStep'] : null,
                    'MaxAppStepCount' => isset($formattedResult[0]) ? $formattedResult[0]['MaxAppStepCount'] : null,
                    'Bridgetowne' => isset($formattedResult[0]) ? $formattedResult[0]['Bridgetowne'] : null,
                    'Clark' => isset($formattedResult[0]) ? $formattedResult[0]['Clark'] : null,
                    'Davao' => isset($formattedResult[0]) ? $formattedResult[0]['Davao'] : null,
                    'Makati' => isset($formattedResult[0]) ? $formattedResult[0]['Makati'] : null,
                    'MOA' => isset($formattedResult[0]) ? $formattedResult[0]['MOA'] : null,
                    'QC North EDSA' => isset($formattedResult[0]) ? $formattedResult[0]['QC North EDSA'] : null,
                    'TotalCount' => isset($totalStepCounts['2. ONLINE ASSESSMENT']) ? number_format($totalStepCounts['2. ONLINE ASSESSMENT']) : null,
                ];
                $mappedResult['3. INITIAL INTERVIEW'] = [
                    'MaxAppStep' => isset($formattedResult[1]) ? $formattedResult[1]['MaxAppStep'] : null,
                    'MaxAppStepCount' => isset($formattedResult[1]) ? $formattedResult[1]['MaxAppStepCount'] : null,
                    'Bridgetowne' => isset($formattedResult[1]) ? $formattedResult[1]['Bridgetowne'] : null,
                    'Clark' => isset($formattedResult[1]) ? $formattedResult[1]['Clark'] : null,
                    'Davao' => isset($formattedResult[1]) ? $formattedResult[1]['Davao'] : null,
                    'Makati' => isset($formattedResult[1]) ? $formattedResult[1]['Makati'] : null,
                    'MOA' => isset($formattedResult[1]) ? $formattedResult[1]['MOA'] : null,
                    'QC North EDSA' => isset($formattedResult[1]) ? $formattedResult[1]['QC North EDSA'] : null,
                    'TotalCount' => isset($totalStepCounts['3. INITIAL INTERVIEW']) ? number_format($totalStepCounts['3. INITIAL INTERVIEW']) : null,
                ];
                $mappedResult['4. BEHAVIORAL INTERVIEW'] = [
                    'MaxAppStep' => isset($formattedResult[2]) ? $formattedResult[2]['MaxAppStep'] : null,
                    'MaxAppStepCount' => isset($formattedResult[2]) ? $formattedResult[2]['MaxAppStepCount'] : null,
                    'Bridgetowne' => isset($formattedResult[2]) ? $formattedResult[2]['Bridgetowne'] : null,
                    'Clark' => isset($formattedResult[2]) ? $formattedResult[2]['Clark'] : null,
                    'Davao' => isset($formattedResult[2]) ? $formattedResult[2]['Davao'] : null,
                    'Makati' => isset($formattedResult[2]) ? $formattedResult[2]['Makati'] : null,
                    'MOA' => isset($formattedResult[2]) ? $formattedResult[2]['MOA'] : null,
                    'QC North EDSA' => isset($formattedResult[2]) ? $formattedResult[2]['QC North EDSA'] : null,
                    'TotalCount' => isset($totalStepCounts['4. BEHAVIORAL INTERVIEW']) ? number_format($totalStepCounts['4. BEHAVIORAL INTERVIEW']) : null,
                ];
                $mappedResult['5. OPERATIONS VALIDATION'] = [
                    'MaxAppStep' => isset($formattedResult[3]) ? $formattedResult[3]['MaxAppStep'] : null,
                    'MaxAppStepCount' => isset($formattedResult[3]) ? $formattedResult[3]['MaxAppStepCount'] : null,
                    'Bridgetowne' => isset($formattedResult[3]) ? $formattedResult[3]['Bridgetowne'] : null,
                    'Clark' => isset($formattedResult[3]) ? $formattedResult[3]['Clark'] : null,
                    'Davao' => isset($formattedResult[3]) ? $formattedResult[3]['Davao'] : null,
                    'Makati' => isset($formattedResult[3]) ? $formattedResult[3]['Makati'] : null,
                    'MOA' => isset($formattedResult[3]) ? $formattedResult[3]['MOA'] : null,
                    'QC North EDSA' => isset($formattedResult[3]) ? $formattedResult[3]['QC North EDSA'] : null,
                    'TotalCount' => isset($totalStepCounts['5. OPERATIONS VALIDATION']) ? number_format($totalStepCounts['5. OPERATIONS VALIDATION']) : null,
                ];
                $mappedResult['6. LANGUAGE ASSESSMENT'] = [
                    'MaxAppStep' => isset($formattedResult[4]) ? $formattedResult[4]['MaxAppStep'] : null,
                    'MaxAppStepCount' => isset($formattedResult[4]) ? $formattedResult[4]['MaxAppStepCount'] : null,
                    'Bridgetowne' => isset($formattedResult[4]) ? $formattedResult[4]['Bridgetowne'] : null,
                    'Clark' => isset($formattedResult[4]) ? $formattedResult[4]['Clark'] : null,
                    'Davao' => isset($formattedResult[4]) ? $formattedResult[4]['Davao'] : null,
                    'Makati' => isset($formattedResult[4]) ? $formattedResult[4]['Makati'] : null,
                    'MOA' => isset($formattedResult[4]) ? $formattedResult[4]['MOA'] : null,
                    'QC North EDSA' => isset($formattedResult[4]) ? $formattedResult[4]['QC North EDSA'] : null,
                    'TotalCount' => isset($totalStepCounts['6. LANGUAGE ASSESSMENT']) ? number_format($totalStepCounts['6. LANGUAGE ASSESSMENT']) : null,
                ];
            }
        }

        return $mappedResult;
    }
    public function AutomatedSrExport()
    {
        $appstepIDs = [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 32, 33, 34, 36, 40, 41, 42, 43, 44, 45, 46, 50, 53, 54, 55, 56, 59, 60, 69, 70, 73, 74, 78, 80, 81, 87, 88];
        $latestDate = SmartRecruitData::on('secondary_sqlsrv')->max('last_update_date');
        $query = SmartRecruitData::on('secondary_sqlsrv')
            ->select('Step', 'AppStep', 'Site', DB::raw('COUNT(*) as Count'))
            ->groupBy('Step', 'AppStep', 'Site')
            ->orderBy('Step')
            ->orderBy('AppStep')
            ->whereIn('ApplicationStepStatusId', $appstepIDs)
            ->whereBetween('QueueDate', ['20240101', $latestDate])
            ->orderBy('Site');

        $result = $query->get();

        $groupedData = [];
        $totalStepCounts = [];
        $totalAppStepCounts = [];

        $totalSiteCounts = [];

        foreach ($result as $item) {
            $step = $item->Step;
            $appStep = $item->AppStep;
            $site = $item->Site;

            if (!isset($groupedData[$step][$appStep])) {
                $groupedData[$step][$appStep] = [
                    'Bridgetowne' => 0,
                    'Clark' => 0,
                    'Davao' => 0,
                    'Makati' => 0,
                    'MOA' => 0,
                    'QC North EDSA' => 0,
                ];
            }

            $groupedData[$step][$appStep][$site] += $item->Count;
            $totalStepCounts[$step] = isset($totalStepCounts[$step]) ? $totalStepCounts[$step] + $item->Count : $item->Count;
            $totalAppStepCounts[$step][$appStep] = isset($totalAppStepCounts[$step][$appStep]) ? $totalAppStepCounts[$step][$appStep] + $item->Count : $item->Count;
            $totalSiteCounts[$step][$site] = (isset($item->Count) && $item->Count > 0) ? (isset($totalSiteCounts[$step][$site]) ? $totalSiteCounts[$step][$site] + $item->Count : $item->Count) : 0;
        }

        foreach ($groupedData as $step => $appSteps) {
            $formattedTotalSiteCounts = [];
            foreach ($totalSiteCounts[$step] as $site => $count) {
                $formattedTotalSiteCounts[$site] = number_format($count);
            }

            $formattedResult[] = array_merge(
                ['Step' => $step, 'TotalCount' => number_format($totalStepCounts[$step])],
                $formattedTotalSiteCounts
            );

            foreach ($appSteps as $appStep => $siteCounts) {
                $formattedSiteCounts = [];
                foreach ($siteCounts as $site => $count) {
                    $formattedSiteCounts[$site] = number_format($count);
                }

                $formattedResult[] = array_merge(
                    ['AppStep' => $appStep, 'TotalCount' => number_format($totalAppStepCounts[$step][$appStep]), 'StepName' => $step],
                    $formattedSiteCounts
                );
            }
        }

        return $formattedResult;
    }
    //capfile
    public function retrieveDataForClassesEmail()
    {
        $programs = Program::with('site')->when(true, function ($query) {
            $query->whereHas('site', function ($subquery) {
                $subquery->where('is_active', 1);
            });
        })
            ->where('is_active', 1) // You ckeep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$week])) {
                    $groupedClasses[$siteName][$programName][$week] = [
                        'total_target' => 0,
                    ];
                }
                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$week]['total_target'] += $totalTarget;
            }
        }

        $mappedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $weeklyData = [
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',

                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',

            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        return $mappedClasses;
    }

    public function retrieveB2DataForEmail()
    {
        $programs = Site::where('is_active', 1)->get();
        $year = 2024;
        $dateRanges = DateRange::select('month_num')->where('year', $year)->groupBy('month_num')->get();
        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByWeek2 = [];
        $grandTotalByProgram = [];
        $grandTotalByProgram2 = []; // Declare $b2percentage array

        foreach ($programs as $program) {
            $siteName = $program->name;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            if (!isset($grandTotalByProgram2[$siteName])) {
                $grandTotalByProgram2[$siteName] = 0;
            }
            foreach ($dateRanges as $dateRange) {
                $month = $dateRange->month_num;
                $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1)->where('program_type', 'B2');
                    })
                    ->where('site_id', $program->id)
                    ->where('status', 'Active')
                    ->get();
                $classes2 = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1);
                    })
                    ->where('site_id', $program->id)
                    ->where('status', 'Active')
                    ->get();
                $totalTarget = $classes->sum('total_target');
                $totalTarget2 = $classes2->sum('total_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }
                if (!isset($grandTotalByWeek2[$month])) {
                    $grandTotalByWeek2[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByWeek2[$month] += $totalTarget2;
                $grandTotalByProgram[$siteName] += $totalTarget;

                $grandTotalByProgram2[$siteName] += $totalTarget2;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['total_target' => 0];
                }
                $groupedClasses[$siteName][$month]['total_target'] += $totalTarget;
            }
        }
        $grandTotalForAllPrograms = array_sum($grandTotalByProgram);
        $grandTotalForAllPrograms2 = array_sum($grandTotalByProgram2);

        $mappedB2Classes = [];
        $mappedB2Classes[] = [
            'Site' => 'B2 Percentage',
            'January' => $grandTotalByWeek2['1'] != 0 ? round(($grandTotalByWeek['1'] / $grandTotalByWeek2['1']) * 100, 2) . '%' : '0%',
            'February' => $grandTotalByWeek2['2'] != 0 ? round(($grandTotalByWeek['2'] / $grandTotalByWeek2['2']) * 100, 2) . '%' : '0%',
            'March' => $grandTotalByWeek2['3'] != 0 ? round(($grandTotalByWeek['3'] / $grandTotalByWeek2['3']) * 100, 2) . '%' : '0%',
            'April' => $grandTotalByWeek2['4'] != 0 ? round(($grandTotalByWeek['4'] / $grandTotalByWeek2['4']) * 100, 2) . '%' : '0%',
            'May' => $grandTotalByWeek2['5'] != 0 ? round(($grandTotalByWeek['5'] / $grandTotalByWeek2['5']) * 100, 2) . '%' : '0%',
            'June' => $grandTotalByWeek2['6'] != 0 ? round(($grandTotalByWeek['6'] / $grandTotalByWeek2['6']) * 100, 2) . '%' : '0%',
            'July' => $grandTotalByWeek2['7'] != 0 ? round(($grandTotalByWeek['7'] / $grandTotalByWeek2['7']) * 100, 2) . '%' : '0%',
            'August' => $grandTotalByWeek2['8'] != 0 ? round(($grandTotalByWeek['8'] / $grandTotalByWeek2['8']) * 100, 2) . '%' : '0%',
            'September' => $grandTotalByWeek2['9'] != 0 ? round(($grandTotalByWeek['9'] / $grandTotalByWeek2['9']) * 100, 2) . '%' : '0%',
            'October' => $grandTotalByWeek2['10'] != 0 ? round(($grandTotalByWeek['10'] / $grandTotalByWeek2['10']) * 100, 2) . '%' : '0%',
            'November' => $grandTotalByWeek2['11'] != 0 ? round(($grandTotalByWeek['11'] / $grandTotalByWeek2['11']) * 100, 2) . '%' : '0%',
            'December' => $grandTotalByWeek2['12'] != 0 ? round(($grandTotalByWeek['12'] / $grandTotalByWeek2['12']) * 100, 2) . '%' : '0%',
            'GrandTotalByProgram' => $grandTotalForAllPrograms2 != 0 ? round(($grandTotalForAllPrograms / $grandTotalForAllPrograms2) * 100, 2) . '%' : '0%',

        ];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
            }
            $grandTotal = $grandTotalByProgram[$siteName];
            if ($grandTotal != 0) {
                $mappedB2Classes[] = [
                    'Site' => $siteName,
                    'January' => $weeklyData['1'] ?: '',
                    'February' => $weeklyData['2'] ?: '',
                    'March' => $weeklyData['3'] ?: '',
                    'April' => $weeklyData['4'] ?: '',
                    'May' => $weeklyData['5'] ?: '',
                    'June' => $weeklyData['6'] ?: '',
                    'July' => $weeklyData['7'] ?: '',
                    'August' => $weeklyData['8'] ?: '',
                    'September' => $weeklyData['9'] ?: '',
                    'October' => $weeklyData['10'] ?: '',
                    'November' => $weeklyData['11'] ?: '',
                    'December' => $weeklyData['12'] ?: '',
                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $mappedB2Classes[] = [
            'Site' => 'Grand Total',
            'January' => $grandTotalByWeek['1'] ?: '',
            'February' => $grandTotalByWeek['2'] ?: '',
            'March' => $grandTotalByWeek['3'] ?: '',
            'April' => $grandTotalByWeek['4'] ?: '',
            'May' => $grandTotalByWeek['5'] ?: '',
            'June' => $grandTotalByWeek['6'] ?: '',
            'July' => $grandTotalByWeek['7'] ?: '',
            'August' => $grandTotalByWeek['8'] ?: '',
            'September' => $grandTotalByWeek['9'] ?: '',
            'October' => $grandTotalByWeek['10'] ?: '',
            'November' => $grandTotalByWeek['11'] ?: '',
            'December' => $grandTotalByWeek['12'] ?: '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        return $mappedB2Classes;
    }
}
