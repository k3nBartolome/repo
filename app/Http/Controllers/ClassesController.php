<?php

namespace App\Http\Controllers;

use App\Exports\ApplicantExport;
use App\Exports\ClassHistoryExport;
use App\Exports\DashboardClassesExport;
use App\Exports\LeadsExport;
use App\Exports\MyExport;
use App\Exports\MyExportv2;
use App\Exports\SrExport;
use App\Http\Resources\ClassesResource;
use App\Models\Applicant;
use App\Models\Classes;
use App\Models\ClassStaffing;
use App\Models\DateRange;
use App\Models\Program;
use App\Models\Site;
use App\Models\SmartRecruitData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ClassesController extends Controller
{
    public function ApplicantsID($id)
    {
        try {
            $id = intval($id);
            $applicant = Applicant::where('SR_ID', $id)->firstOrFail();

            return response()->json([
            'applicants' => $applicant,
        ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function ApplicantsDate()
    {
        try {
            $minDate = Applicant::min('ApplicationDate');
            $maxDate = Applicant::max('ApplicationDate');

            $formattedMinDate = date('m/d/Y', strtotime($minDate));
            $formattedMaxDate = date('m/d/Y', strtotime($maxDate));

            return response()->json([
            'minDate' => $formattedMinDate,
            'maxDate' => $formattedMaxDate,
        ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function Applicants(Request $request)
    {
        try {
            $regions = [
            'QC' => ['QC North Edsa'],
            'L2' => ['Bridgetowne', 'Makati', 'MOA'],
            'CLARK' => ['Clark'],
            'DAVAO' => ['Davao SM', 'Davao Robinsons', 'Davao Delta', 'Davao Centrale', 'Davao Finance Center'],
        ];

            $query = Applicant::query();

            // Log request data
            \Log::info('Request Data: ', $request->all());

            if ($request->has('filter_lastname')) {
                $filterLastName = $request->input('filter_lastname');
                if (!empty($filterLastName)) {
                    \Log::info('Applying LastName Filter: ', ['filterLastName' => $filterLastName]);
                    $query->where('LastName', 'LIKE', '%'.$filterLastName.'%');
                }
            }

            if ($request->has('filter_firstname')) {
                $filterFirstName = $request->input('filter_firstname');
                if (!empty($filterFirstName)) {
                    \Log::info('Applying FirstName Filter: ', ['filterFirstName' => $filterFirstName]);
                    $query->where('FirstName', 'LIKE', '%'.$filterFirstName.'%');
                }
            }

            if ($request->has('filter_site')) {
                $filterSite = $request->input('filter_site');
                if (!empty($filterSite)) {
                    \Log::info('Applying Site Filter: ', ['filterSite' => $filterSite]);
                    $query->where('SiteApplied', 'LIKE', '%'.$filterSite.'%');
                }
            }

            if ($request->has('startDate') && $request->has('endDate')) {
                $filterDateStart = $request->input('startDate');
                $filterDateEnd = $request->input('endDate');

                if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                    // Convert MM/DD/YYYY to YYYY-MM-DD for comparison
                    $startDate = date('Y-m-d', strtotime($filterDateStart));
                    $endDate = date('Y-m-d', strtotime($filterDateEnd.' +1 day'));

                    \Log::info('Converted Dates:', ['startDate' => $startDate, 'endDate' => $endDate]);

                    $query->whereBetween(DB::raw('CONVERT(date, ApplicationDate, 101)'), [$startDate, $endDate]);
                }
            }

            if ($request->has('filter_contact')) {
                $filterContact = $request->input('filter_contact');
                if (!empty($filterContact)) {
                    \Log::info('Applying Contact Filter: ', ['filterContact' => $filterContact]);
                    $query->where('CellphoneNumber', 'LIKE', '%'.$filterContact.'%');
                }
            }

            if ($request->has('filter_region')) {
                $filterRegion = $request->input('filter_region');
                if (!empty($filterRegion) && isset($regions[$filterRegion])) {
                    $siteIds = $regions[$filterRegion];
                    \Log::info('Applying Region Filter: ', ['filterRegion' => $filterRegion, 'siteIds' => $siteIds]);
                    $query->where(function ($q) use ($siteIds) {
                        foreach ($siteIds as $site) {
                            $q->orWhere('SiteApplied', 'LIKE', '%'.$site.'%');
                        }
                    });
                }
            }

            // Log the final query for debugging
            \Log::info('Final Query: ', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);

            $applicants = $query->get();

            return response()->json([
            'applicants' => $applicants,
        ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function ApplicantsExport(Request $request)
    {
        try {
            $regions = [
            'QC' => ['QC North Edsa'],
            'L2' => ['Bridgetowne', 'Makati', 'MOA'],
            'CLARK' => ['Clark'],
            'DAVAO' => ['Davao SM', 'Davao Robinsons', 'Davao Delta', 'Davao Centrale', 'Davao Finance Center'],
        ];

            $query = Applicant::query();

            \Log::info('Request Data: ', $request->all());

            if ($request->has('filter_lastname')) {
                $filterLastName = $request->input('filter_lastname');
                if (!empty($filterLastName)) {
                    \Log::info('Applying LastName Filter: ', ['filterLastName' => $filterLastName]);
                    $query->where('LastName', 'LIKE', '%'.$filterLastName.'%');
                }
            }

            if ($request->has('filter_firstname')) {
                $filterFirstName = $request->input('filter_firstname');
                if (!empty($filterFirstName)) {
                    \Log::info('Applying FirstName Filter: ', ['filterFirstName' => $filterFirstName]);
                    $query->where('FirstName', 'LIKE', '%'.$filterFirstName.'%');
                }
            }

            if ($request->has('filter_site')) {
                $filterSite = $request->input('filter_site');
                if (!empty($filterSite)) {
                    \Log::info('Applying Site Filter: ', ['filterSite' => $filterSite]);
                    $query->where('SiteApplied', 'LIKE', '%'.$filterSite.'%');
                }
            }

            if ($request->has('startDate') && $request->has('endDate')) {
                $filterDateStart = $request->input('startDate');
                $filterDateEnd = $request->input('endDate');

                if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                    // Convert MM/DD/YYYY to YYYY-MM-DD for comparison
                    $startDate = date('Y-m-d', strtotime($filterDateStart));
                    $endDate = date('Y-m-d', strtotime($filterDateEnd.' +1 day'));

                    \Log::info('Converted Dates:', ['startDate' => $startDate, 'endDate' => $endDate]);

                    $query->whereBetween(DB::raw('CONVERT(date, ApplicationDate, 101)'), [$startDate, $endDate]);
                }
            }

            if ($request->has('filter_contact')) {
                $filterContact = $request->input('filter_contact');
                if (!empty($filterContact)) {
                    \Log::info('Applying Contact Filter: ', ['filterContact' => $filterContact]);
                    $query->where('CellphoneNumber', 'LIKE', '%'.$filterContact.'%');
                }
            }

            if ($request->has('filter_region')) {
                $filterRegion = $request->input('filter_region');
                if (!empty($filterRegion) && isset($regions[$filterRegion])) {
                    $siteIds = $regions[$filterRegion];
                    \Log::info('Applying Region Filter: ', ['filterRegion' => $filterRegion, 'siteIds' => $siteIds]);
                    $query->where(function ($q) use ($siteIds) {
                        foreach ($siteIds as $site) {
                            $q->orWhere('SiteApplied', 'LIKE', '%'.$site.'%');
                        }
                    });
                }
            }

            // Log the final query for debugging
            \Log::info('Final Query: ', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);

            $applicants = $query->get();

            $filteredDataArray = $applicants->toArray();

            return Excel::download(new ApplicantExport($filteredDataArray), 'applicants_sr_data.xlsx');
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function srSite()
    {
        $query = SmartRecruitData::on('secondary_sqlsrv')->select('Site')->distinct()->get();

        return response()->json([
            'sites' => $query,
        ]);
    }

    public function srDate()
    {
        $minDate = SmartRecruitData::on('secondary_sqlsrv')->min('QueueDate');
        $maxDate = SmartRecruitData::on('secondary_sqlsrv')->max('QueueDate');

        $minDate = Carbon::parse($minDate)->format('Y-m-d');
        $maxDate = Carbon::parse($maxDate)->format('Y-m-d');

        return response()->json([
            'minDate' => $minDate,
            'maxDate' => $maxDate,
        ]);
    }

    public function srFilter(Request $request)
    {
        $appstepIDs = [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 32, 33, 34, 36, 40, 41, 42, 43, 44, 45, 46, 50, 53, 54, 55, 56, 59, 60, 69, 70, 73, 74, 78, 80, 81, 87, 88];
        $query = SmartRecruitData::on('secondary_sqlsrv')
            ->select(
                'ApplicationInfoId',
                'QueueDate',
                'LastName',
                'FirstName',
                'MiddleName',
                'Site',
                'MobileNo',
                'GenSource',
                'SpecSource',
                'Step',
                'AppStep'
            )
            ->whereNotNull('Site')
            ->whereIn('ApplicationStepStatusId', $appstepIDs);

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');

            if (!empty($filterSite)) {
                $query->where('Site', 'LIKE', '%'.$filterSite.'%');
            }
        }
        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');

            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd));

                $endDate = date('Y-m-d', strtotime($endDate.' +1 day'));

                $query->where('QueueDate', '>=', $startDate)
                    ->where('QueueDate', '<', $endDate);
            }
        }

        $filteredData = $query->get();

        return response()->json([
            'sr' => $filteredData,
        ]);
    }

    public function srExport(Request $request)
    {
        $appstepIDs = [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 32, 33, 34, 36, 40, 41, 42, 43, 44, 45, 46, 50, 53, 54, 55, 56, 59, 60, 69, 70, 73, 74, 78, 80, 81, 87, 88];
        $query = SmartRecruitData::on('secondary_sqlsrv')
            ->select(
                'ApplicationInfoId',
                'QueueDate',
                'LastName',
                'FirstName',
                'MiddleName',
                'Site',
                'MobileNo',
                'GenSource',
                'SpecSource',
                'Step',
                'AppStep'
            )
            ->whereNotNull('Site')
            ->whereIn('ApplicationStepStatusId', $appstepIDs);

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');

            if (!empty($filterSite)) {
                $query->where('Site', 'LIKE', '%'.$filterSite.'%');
            }
        }
        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');

            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd));

                $endDate = date('Y-m-d', strtotime($endDate));

                $query->where('QueueDate', '>=', $startDate)
                    ->where('QueueDate', '<=', $endDate);
            }
        }

        $filteredData = $query->get();

        $filteredDataArray = $filteredData->toArray();

        return Excel::download(new SrExport($filteredDataArray), 'filtered_sr_data.xlsx');
    }

    public function srCompliance(Request $request)
    {
        $appstepIDs = [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 32, 33, 34, 36, 40, 41, 42, 43, 44, 45, 46, 50, 53, 54, 55, 56, 59, 60, 69, 70, 73, 74, 78, 80, 81, 87, 88];

        $query = SmartRecruitData::on('secondary_sqlsrv')
            ->select('Step', 'AppStep', 'Site', DB::raw('COUNT(*) as Count'))
            ->groupBy('Step', 'AppStep', 'Site')
            ->orderBy('Step')
            ->orderBy('AppStep')
            ->whereIn('ApplicationStepStatusId', $appstepIDs)
            ->orderBy('Site');

        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');

            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd));

                $endDate = date('Y-m-d', strtotime($endDate));

                $query->where('QueueDate', '>=', $startDate)
                    ->where('QueueDate', '<=', $endDate);
            }
        }

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

        return response()->json(['sr' => $formattedResult]);
    }

    public function perxSite()
    {
        $query = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA')->select('Site')->whereNotNull('Site')->distinct()->get();

        return response()->json([
            'sites' => $query,
        ]);
    }

    public function perxApplicantFilterv2(Request $request)
    {
        $regions = [
            'QC' => [1, 2, 3, 4, 21],
            'L2' => [5, 6, 16, 19, 20],
            'CLARK' => [11, 12, 17],
            'DAVAO' => [7, 8, 9, 10, 13, 14, 15, 18], ];
        $query = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Applicant as ApplicantDetails')
            ->select(
                'ApplicantDetails.Id as ApplicantId',
                'ApplicationDetails.AppliedDate as DateOfApplication',
                'ApplicantDetails.FirstName as FirstName',
                'ApplicantDetails.LastName as LastName',
                'ApplicantDetails.MiddleName as MiddleName',
                'ApplicantDetails.CellphoneNumber as MobileNumber',
                DB::raw("
                    CASE
                        WHEN SitesDetails.Id IN (1, 2, 3, 4, 21) THEN 'QC'
                        WHEN SitesDetails.Id IN (5, 6, 16, 19, 20) THEN 'L2'
                        WHEN SitesDetails.Id IN (11, 12, 17) THEN 'CLARK'
                        WHEN SitesDetails.Id IN (7, 8, 9, 10, 13, 14, 15, 18) THEN 'DAVAO'
                        ELSE 'UNKNOWN'
                    END as Region
                "),
                'SitesDetails.Name as Site',
                'GeneralSource.Name as GeneralSource',
                'SourceOfApplication.Name as SpecSource',
                'Step.Description as Step',
                'Status.GeneralStatus as AppStep1',
                'Status.SpecificStatus as AppStep2',
                'Referrals.FirstName as ReferrerFirstName',
                'Referrals.MiddleName as ReferrerMiddleName',
                'Referrals.LastName as ReferrerLastName',
                'Referrals.ReferrerHRID as ReferrerHRID',
                'Referrals.ReferrerName as ReferrerName',
                'ApplicationInformation.ReferrerName as DeclaredReferrerName',
                'ApplicationInformation.ReferrerId as DeclaredReferrerId',
                'JobDetails.Title as JobTitle'
            )
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicantApplications as ApplicationDetails', 'ApplicantDetails.Id', '=', 'ApplicationDetails.ApplicantId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Status as Status', 'ApplicationDetails.Status', '=', 'Status.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.job as JobDetails', 'ApplicationDetails.JobId', '=', 'JobDetails.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Sites as SitesDetails', 'JobDetails.SiteId', '=', 'SitesDetails.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicationInformation as ApplicationInformation', 'ApplicantDetails.Id', '=', 'ApplicationInformation.UserID')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.SourceOfApplication as SourceOfApplication', 'ApplicationInformation.SourceOfApplication', '=', 'SourceOfApplication.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.GeneralSource as GeneralSource', 'GeneralSource.Id', '=', 'SourceOfApplication.GeneralSourceId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Step as Step', 'Step.Id', '=', 'Status.StepId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Referrals as Referrals', 'Referrals.UserId', '=', 'ApplicantDetails.Id');
        if ($request->has('filter_lastname')) {
            $filterLastName = $request->input('filter_lastname');
            if (!empty($filterLastName)) {
                $query->where('ApplicantDetails.LastName', 'LIKE', '%'.$filterLastName.'%');
            }
        }
        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');
            if (!empty($filterFirstName)) {
                $query->where('ApplicantDetails.FirstName', 'LIKE', '%'.$filterFirstName.'%');
            }
        }
        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');
            if (!empty($filterSite)) {
                $query->where('SitesDetails.Name', 'LIKE', '%'.$filterSite.'%');
            }
        }
        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');
            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd.' +1 day'));

                $query->whereBetween('ApplicationDetails.AppliedDate', [$startDate, $endDate]);
            }
        }
        if ($request->has('filter_contact')) {
            $filterContact = $request->input('filter_contact');
            if (!empty($filterContact)) {
                $query->where('ApplicantDetails.CellphoneNumber', 'LIKE', '%'.$filterContact.'%');
            }
        }
        if ($request->has('filter_region')) {
            $filterRegion = $request->input('filter_region');
            if (!empty($filterRegion) && isset($regions[$filterRegion])) {
                $siteIds = $regions[$filterRegion];
                $query->whereIn('SitesDetails.Id', $siteIds);
            }
        }
        if ($request->has('filter_genstat')) {
            $filterGenStat = $request->input('filter_genstat');
            if (!empty($filterGenStat)) {
                $query->where('Status.GeneralStatus', 'LIKE', '%'.$filterGenStat.'%');
            }
        }
        if ($request->has('filter_specstat')) {
            $filterSpecStat = $request->input('filter_specstat');
            if (!empty($filterSpecStat)) {
                $query->where('Status.SpecificStatus', 'LIKE', '%'.$filterSpecStat.'%');
            }
        }
        if ($request->has('filter_step')) {
            $filterStep = $request->input('filter_step');
            if (!empty($filterStep)) {
                $query->where('Step.Description', 'LIKE', '%'.$filterStep.'%');
            }
        }
        $filteredData = $query->get();

        return response()->json([
            'perx' => $filteredData,
        ]);
    }

    public function perxStatus()
    {
        $query = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Status')
            ->select('GeneralStatus as GeneralStatus',
                'SpecificStatus as SpecificStatus')
            ->distinct()->get();

        return response()->json([
            'status' => $query,
        ]);
    }

    public function perxStep()
    {
        $query = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Step')
            ->select('Description')
            ->distinct()->get();

        return response()->json([
            'step' => $query,
        ]);
    }

    public function perxSitev2(Request $request)
    {
        $regions = [
            'QC' => [1, 2, 3, 4, 21],
            'L2' => [5, 6, 16, 19, 20],
            'CLARK' => [11, 12, 17],
            'DAVAO' => [7, 8, 9, 10, 13, 14, 15, 18],
        ];

        $query = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Sites')
            ->select('Id', 'Name')
            ->whereNotNull('Name')
            ->where('isActive', 1);

        if ($request->has('filter_region')) {
            $filterRegion = $request->input('filter_region');
            if (!empty($filterRegion) && isset($regions[$filterRegion])) {
                $siteIds = $regions[$filterRegion];
                $query->whereIn('Id', $siteIds);
            }
        }

        $query->orderBy('Name');

        $sites = $query->distinct()->get();

        return response()->json([
            'sites' => $sites,
        ]);
    }

    public function perxDate()
    {
        $minDate = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA')->min('DateOfApplication');
        $maxDate = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA')->max('DateOfApplication');

        $minDate = Carbon::parse($minDate)->format('Y-m-d');
        $maxDate = Carbon::parse($maxDate)->format('Y-m-d');

        return response()->json([
            'minDate' => $minDate,
            'maxDate' => $maxDate,
        ]);
    }

    public function leadsDatev2()
    {
        $minDate = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicantApplications')->min('DateCreated');
        $maxDate = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicantApplications')->max('DateCreated');

        $minDate = Carbon::parse($minDate)->format('Y-m-d');
        $maxDate = Carbon::parse($maxDate)->format('Y-m-d');

        return response()->json([
            'minDate' => $minDate,
            'maxDate' => $maxDate,
        ]);
    }

    public function perxDatev2()
    {
        $minDate = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.UploadLeads')->min('AppliedDate');
        $maxDate = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.UploadLeads')->max('AppliedDate');

        $minDate = Carbon::parse($minDate)->format('Y-m-d');
        $maxDate = Carbon::parse($maxDate)->format('Y-m-d');

        return response()->json([
            'minDate' => $minDate,
            'maxDate' => $maxDate,
        ]);
    }

    public function perxFilterv2(Request $request)
    {
        $regions = [
            'QC' => [1, 2, 3, 4, 21],
            'L2' => [5, 6, 16, 19, 20],
            'CLARK' => [11, 12, 17],
            'DAVAO' => [7, 8, 9, 10, 13, 14, 15, 18],
        ];

        $query = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Applicant as ApplicantDetails')
            ->select(
                'ApplicantDetails.Id as ApplicantId',
                'ApplicationDetails.AppliedDate as DateOfApplication',
                'ApplicantDetails.FirstName as FirstName',
                'ApplicantDetails.LastName as LastName',
                'ApplicantDetails.MiddleName as MiddleName',
                'ApplicantDetails.CellphoneNumber as MobileNumber',
                DB::raw("
                    CASE
                        WHEN SitesDetails.Id IN (1, 2, 3, 4, 21) THEN 'QC'
                        WHEN SitesDetails.Id IN (5, 6, 16, 19, 20) THEN 'L2'
                        WHEN SitesDetails.Id IN (11, 12, 17) THEN 'CLARK'
                        WHEN SitesDetails.Id IN (7, 8, 9, 10, 13, 14, 15, 18) THEN 'DAVAO'
                        ELSE 'UNKNOWN'
                    END as Region
                "),
                'SitesDetails.Name as Site',
                'GeneralSource.Name as GeneralSource',
                'SourceOfApplication.Name as SpecSource',
                'Step.Description as Step',
                'Status.GeneralStatus as AppStep1',
                'Status.SpecificStatus as AppStep2',
                'Referrals.FirstName as ReferrerFirstName',
                'Referrals.MiddleName as ReferrerMiddleName',
                'Referrals.LastName as ReferrerLastName',
                'Referrals.ReferrerHRID as ReferrerHRID',
                'Referrals.ReferrerName as ReferrerName',
                'ApplicationInformation.ReferrerName as DeclaredReferrerName',
                'ApplicationInformation.ReferrerId as DeclaredReferrerId',
                'JobDetails.Title as JobTitle'
            )
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicantApplications as ApplicationDetails', 'ApplicantDetails.Id', '=', 'ApplicationDetails.ApplicantId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Status as Status', 'ApplicationDetails.Status', '=', 'Status.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.job as JobDetails', 'ApplicationDetails.JobId', '=', 'JobDetails.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Sites as SitesDetails', 'JobDetails.SiteId', '=', 'SitesDetails.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicationInformation as ApplicationInformation', 'ApplicantDetails.Id', '=', 'ApplicationInformation.UserID')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.SourceOfApplication as SourceOfApplication', 'ApplicationInformation.SourceOfApplication', '=', 'SourceOfApplication.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.GeneralSource as GeneralSource', 'GeneralSource.Id', '=', 'SourceOfApplication.GeneralSourceId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Step as Step', 'Step.Id', '=', 'Status.StepId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Referrals as Referrals', 'Referrals.UserId', '=', 'ApplicantDetails.Id');

        if ($request->has('filter_lastname')) {
            $filterLastName = $request->input('filter_lastname');
            if (!empty($filterLastName)) {
                $query->where('ApplicantDetails.LastName', 'LIKE', '%'.$filterLastName.'%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');
            if (!empty($filterFirstName)) {
                $query->where('ApplicantDetails.FirstName', 'LIKE', '%'.$filterFirstName.'%');
            }
        }

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');
            if (!empty($filterSite)) {
                $query->where('SitesDetails.Name', 'LIKE', '%'.$filterSite.'%');
            }
        }

        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');
            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd.' +1 day'));

                $query->whereBetween('ApplicationDetails.AppliedDate', [$startDate, $endDate]);
            }
        }

        if ($request->has('filter_contact')) {
            $filterContact = $request->input('filter_contact');
            if (!empty($filterContact)) {
                $query->where('ApplicantDetails.CellphoneNumber', 'LIKE', '%'.$filterContact.'%');
            }
        }

        if ($request->has('filter_region')) {
            $filterRegion = $request->input('filter_region');
            if (!empty($filterRegion) && isset($regions[$filterRegion])) {
                $siteIds = $regions[$filterRegion];
                $query->whereIn('SitesDetails.Id', $siteIds);
            }
        }

        $filteredData = $query->get();

        return response()->json([
            'perx' => $filteredData,
        ]);
    }

    public function perxFilterNoSrv2(Request $request)
    {
        $regions = [
            'QC' => [1, 2, 3, 4, 21],
            'L2' => [5, 6, 16, 19, 20],
            'CLARK' => [11, 12, 17],
            'DAVAO' => [7, 8, 9, 10, 13, 14, 15, 18],
        ];
        $query = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Referrals as r')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Applicant as a', 'r.id', '=', 'a.referralid')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Sites as s', 'r.Site', '=', 's.id')

            ->whereNull('a.referralid')
            ->select('r.Id',
                    /*  'r.JobId',
                     'r.UserId', */
                     'r.FirstName',
                     'r.MiddleName',
                     'r.LastName',
                     'r.EmailAddress',
                     'r.ContactNo',
                     'r.TypeOfReferral',
                     's.Name as Site',
                     'r.ReferrerHRID',
                     'r.ReferrerName',
                     'r.ReferrerSite',
                     'r.ReferrerProgram',
                     /* 'r.CreatedBy', */
                     'r.DateCreated',
                     /* 'r.UpdatedBy', */
                     'r.DateUpdated',);

                     if ($request->has('filter_lastname')) {
                        $filterLastName = $request->input('filter_lastname');
                        if (!empty($filterLastName)) {
                            $query->where('r.LastName', 'LIKE', '%'.$filterLastName.'%');
                        }
                    }
            
                    if ($request->has('filter_firstname')) {
                        $filterFirstName = $request->input('filter_firstname');
                        if (!empty($filterFirstName)) {
                            $query->where('r.FirstName', 'LIKE', '%'.$filterFirstName.'%');
                        }
                    }
            
                    if ($request->has('filter_site')) {
                        $filterSite = $request->input('filter_site');
                        if (!empty($filterSite)) {
                            $query->where('r.Site', 'LIKE', '%'.$filterSite.'%');
                        }
                    }
            
                   /*  if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
                        $filterDateStart = $request->input('filter_date_start');
                        $filterDateEnd = $request->input('filter_date_end');
                        if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                            $startDate = date('Y-m-d', strtotime($filterDateStart));
                            $endDate = date('Y-m-d', strtotime($filterDateEnd.' +1 day'));
            
                            $query->whereBetween('ApplicationDetails.AppliedDate', [$startDate, $endDate]);
                        }
                    } */
            
                    if ($request->has('filter_type')) {
                        $filterContact = $request->input('filter_type');
                        if (!empty($filterType)) {
                            $query->where('r.TypeOfReferral', 'LIKE', '%'.$filterType.'%');
                        }
                    }
            
                    if ($request->has('filter_region')) {
                        $filterRegion = $request->input('filter_region');
                        if (!empty($filterRegion) && isset($regions[$filterRegion])) {
                            $siteIds = $regions[$filterRegion];
                            $query->whereIn('S.Id', $siteIds);
                        }
                    }
            
                    $filteredData = $query->get();

        return response()->json([
            'perx' => $filteredData,
        ]);
    }

    public function perxFilterNoSrExportv2(Request $request)
    {
        $query = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Referrals as r')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Applicant as a', 'r.id', '=', 'a.referralid')
            ->whereNull('a.referralid')
            ->select('r.*');

        $filteredData = $query->get();

        return response()->json([
            'perx' => $filteredData,
        ]);
    }

    public function exportFilteredDatav2(Request $request)
    {
        $regions = [
            'QC' => [1, 2, 3, 4, 21],
            'L2' => [5, 6, 16, 19, 20],
            'CLARK' => [11, 12, 17],
            'DAVAO' => [7, 8, 9, 10, 13, 14, 15, 18],
        ];

        $query = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Applicant as ApplicantDetails')
            ->select(
                'ApplicantDetails.Id as ApplicantId',
                'ApplicationDetails.AppliedDate as DateOfApplication',
                'ApplicantDetails.FirstName as FirstName',
                'ApplicantDetails.LastName as LastName',
                'ApplicantDetails.MiddleName as MiddleName',
                'ApplicantDetails.CellphoneNumber as MobileNumber',
                DB::raw("
                    CASE
                        WHEN SitesDetails.Id IN (1, 2, 3, 4, 21) THEN 'QC'
                        WHEN SitesDetails.Id IN (5, 6, 16, 19, 20) THEN 'L2'
                        WHEN SitesDetails.Id IN (11, 12, 17) THEN 'CLARK'
                        WHEN SitesDetails.Id IN (7, 8, 9, 10, 13, 14, 15, 18) THEN 'DAVAO'
                        ELSE 'UNKNOWN'
                    END as Region
                "),
                'SitesDetails.Name as Site',
                'GeneralSource.Name as GeneralSource',
                'SourceOfApplication.Name as SpecSource',
                'Step.Description as Step',
                'Status.GeneralStatus as AppStep1',
                'Status.SpecificStatus as AppStep2',
                'Referrals.FirstName as ReferrerFirstName',
                'Referrals.MiddleName as ReferrerMiddleName',
                'Referrals.LastName as ReferrerLastName',
                'Referrals.ReferrerHRID as ReferrerHRID',
                'Referrals.ReferrerName as ReferrerName',
                'ApplicationInformation.ReferrerName as DeclaredReferrerName',
                'ApplicationInformation.ReferrerId as DeclaredReferrerId',
                'JobDetails.Title as JobTitle'
            )
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicantApplications as ApplicationDetails', 'ApplicantDetails.Id', '=', 'ApplicationDetails.ApplicantId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Status as Status', 'ApplicationDetails.Status', '=', 'Status.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.job as JobDetails', 'ApplicationDetails.JobId', '=', 'JobDetails.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Sites as SitesDetails', 'JobDetails.SiteId', '=', 'SitesDetails.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicationInformation as ApplicationInformation', 'ApplicantDetails.Id', '=', 'ApplicationInformation.UserID')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.SourceOfApplication as SourceOfApplication', 'ApplicationInformation.SourceOfApplication', '=', 'SourceOfApplication.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.GeneralSource as GeneralSource', 'GeneralSource.Id', '=', 'SourceOfApplication.GeneralSourceId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Step as Step', 'Step.Id', '=', 'Status.StepId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Referrals as Referrals', 'Referrals.UserId', '=', 'ApplicantDetails.Id');

        if ($request->has('filter_lastname')) {
            $filterLastName = $request->input('filter_lastname');
            if (!empty($filterLastName)) {
                $query->where('ApplicantDetails.LastName', 'LIKE', '%'.$filterLastName.'%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');
            if (!empty($filterFirstName)) {
                $query->where('ApplicantDetails.FirstName', 'LIKE', '%'.$filterFirstName.'%');
            }
        }

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');
            if (!empty($filterSite)) {
                $query->where('SitesDetails.Name', 'LIKE', '%'.$filterSite.'%');
            }
        }

        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');
            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd.' +1 day'));

                $query->whereBetween('ApplicationDetails.AppliedDate', [$startDate, $endDate]);
            }
        }

        if ($request->has('filter_contact')) {
            $filterContact = $request->input('filter_contact');
            if (!empty($filterContact)) {
                $query->where('ApplicantDetails.CellphoneNumber', 'LIKE', '%'.$filterContact.'%');
            }
        }

        if ($request->has('filter_region')) {
            $filterRegion = $request->input('filter_region');
            if (!empty($filterRegion) && isset($regions[$filterRegion])) {
                $siteIds = $regions[$filterRegion];
                $query->whereIn('SitesDetails.Id', $siteIds);
            }
        }

        $filteredData = $query->get();

        $filteredDataArray = $filteredData->toArray();

        return Excel::download(new MyExportv2($filteredDataArray), 'perx_data.xlsx');
    }

    public function perxFilter(Request $request)
    {
        $query = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA');

        if ($request->has('filter_lastname')) {
            $filterLastName = $request->input('filter_lastname');

            if (!empty($filterLastName)) {
                $query->where('LastName', 'LIKE', '%'.$filterLastName.'%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');

            if (!empty($filterFirstName)) {
                $query->where('FirstName', 'LIKE', '%'.$filterFirstName.'%');
            }
        }

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');

            if (!empty($filterSite)) {
                $query->where('Site', 'LIKE', '%'.$filterSite.'%');
            }
        }
        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');

            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd));

                $endDate = date('Y-m-d', strtotime($endDate.' +1 day'));

                $query->where('DateOfApplication', '>=', $startDate)
                    ->where('DateOfApplication', '<', $endDate);
            }
        }

        if ($request->has('filter_contact')) {
            $filterContact = $request->input('filter_contact');

            if (!empty($filterContact)) {
                $query->where('MobileNo', 'LIKE', '%'.$filterContact.'%');
            }
        }

        $filteredData = $query->get();

        return response()->json([
            'perx' => $filteredData,
        ]);
    }

    public function leads(Request $request)
    {
        $regions = [
            'QC' => [1, 2, 3, 4, 21],
            'L2' => [5, 6, 16, 19, 20],
            'CLARK' => [11, 12, 17],
            'DAVAO' => [7, 8, 9, 10, 13, 14, 15, 18],
        ];

        $query = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.UploadLeads as UploadLeads')
            ->select('ApplicantDetails.Id as ApplicantId',
                'ApplicationDetails.AppliedDate as DateOfApplication',
                'UploadLeads.FirstName as FirstName',
                'UploadLeads.LastName as LastName',
                'UploadLeads.MiddleName as MiddleName',
                'UploadLeads.ContactNo as MobileName',
                'UploadLeads.EmailAddress as Email',
                'SitesDetails.Name as Site',
                'GeneralSource.Name as GeneralSource',
                'Status.GeneralStatus as GeneralStatus',
                'Status.SpecificStatus as SpecificStatus',
                'JobDetails.Title as JobTitle'
            )
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Applicant as ApplicantDetails', 'UploadLeads.Id', '=', 'ApplicantDetails.UploadLeadsID')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicantApplications as ApplicationDetails', 'ApplicantDetails.Id', '=', 'ApplicationDetails.ApplicantId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Status as Status', 'ApplicationDetails.Status', '=', 'Status.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.job as JobDetails', 'ApplicationDetails.JobId', '=', 'JobDetails.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.GeneralSource as GeneralSource', 'UploadLeads.GeneralSouceId', '=', 'GeneralSource.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Step as Step', 'Step.Id', '=', 'Status.StepId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Sites as SitesDetails', 'UploadLeads.SiteId', '=', 'SitesDetails.Id');

        if ($request->has('filter_lastname')) {
            $filterLastName = $request->input('filter_lastname');
            if (!empty($filterLastName)) {
                $query->where('ApplicantDetails.LastName', 'LIKE', '%'.$filterLastName.'%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');
            if (!empty($filterFirstName)) {
                $query->where('ApplicantDetails.FirstName', 'LIKE', '%'.$filterFirstName.'%');
            }
        }

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');
            if (!empty($filterSite)) {
                $query->where('SitesDetails.Name', 'LIKE', '%'.$filterSite.'%');
            }
        }

        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');
            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd.' +1 day'));

                $query->whereBetween('UploadLeads.DateCreated', [$startDate, $endDate]);
            }
        }

        if ($request->has('filter_createdby')) {
            $filterCreatedBy = $request->input('filter_createdby');
            if (!empty($filterCreatedBy)) {
                $query->where('UploadLeads.CreatedBy', 'LIKE', '%'.$filterCreatedBy.'%');
            }
        }

        if ($request->has('filter_region')) {
            $filterRegion = $request->input('filter_region');
            if (!empty($filterRegion) && isset($regions[$filterRegion])) {
                $siteIds = $regions[$filterRegion];
                $query->whereIn('SitesDetails.Id', $siteIds);
            }
        }

        $filteredData = $query->get();

        return response()->json([
            'leads' => $filteredData,
        ]);
    }

    public function exportLeadsData(Request $request)
    {
        $regions = [
            'QC' => [1, 2, 3, 4, 21],
            'L2' => [5, 6, 16, 19, 20],
            'CLARK' => [11, 12, 17],
            'DAVAO' => [7, 8, 9, 10, 13, 14, 15, 18],
        ];

        $query = DB::connection('sqlsrv')
            ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.UploadLeads as UploadLeads')
            ->select('ApplicantDetails.Id as ApplicantId',
                'ApplicationDetails.AppliedDate as DateOfApplication',
                'UploadLeads.FirstName as FirstName',
                'UploadLeads.LastName as LastName',
                'UploadLeads.MiddleName as MiddleName',
                'UploadLeads.ContactNo as MobileName',
                'UploadLeads.EmailAddress as Email',
                'SitesDetails.Name as Site',
                'GeneralSource.Name as GeneralSource',
                'Status.GeneralStatus as GeneralStatus',
                'Status.SpecificStatus as SpecificStatus',
                'JobDetails.Title as JobTitle'
            )
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Applicant as ApplicantDetails', 'UploadLeads.Id', '=', 'ApplicantDetails.UploadLeadsID')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicantApplications as ApplicationDetails', 'ApplicantDetails.Id', '=', 'ApplicationDetails.ApplicantId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Status as Status', 'ApplicationDetails.Status', '=', 'Status.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.job as JobDetails', 'ApplicationDetails.JobId', '=', 'JobDetails.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.GeneralSource as GeneralSource', 'UploadLeads.GeneralSouceId', '=', 'GeneralSource.Id')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Step as Step', 'Step.Id', '=', 'Status.StepId')
            ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Sites as SitesDetails', 'UploadLeads.SiteId', '=', 'SitesDetails.Id');

        if ($request->has('filter_lastname')) {
            $filterLastName = $request->input('filter_lastname');
            if (!empty($filterLastName)) {
                $query->where('ApplicantDetails.LastName', 'LIKE', '%'.$filterLastName.'%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');
            if (!empty($filterFirstName)) {
                $query->where('ApplicantDetails.FirstName', 'LIKE', '%'.$filterFirstName.'%');
            }
        }

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');
            if (!empty($filterSite)) {
                $query->where('SitesDetails.Name', 'LIKE', '%'.$filterSite.'%');
            }
        }

        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');
            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd.' +1 day'));

                $query->whereBetween('UploadLeads.DateCreated', [$startDate, $endDate]);
            }
        }

        if ($request->has('filter_createdby')) {
            $filterCreatedBy = $request->input('filter_createdby');
            if (!empty($filterCreatedBy)) {
                $query->where('UploadLeads.CreatedBy', 'LIKE', '%'.$filterCreatedBy.'%');
            }
        }

        if ($request->has('filter_region')) {
            $filterRegion = $request->input('filter_region');
            if (!empty($filterRegion) && isset($regions[$filterRegion])) {
                $siteIds = $regions[$filterRegion];
                $query->whereIn('SitesDetails.Id', $siteIds);
            }
        }

        $filteredData = $query->get();

        $filteredDataArray = $filteredData->toArray();

        return Excel::download(new LeadsExport($filteredDataArray), 'filtered_leads_data.xlsx');
    }

    public function exportFilteredData(Request $request)
    {
        $query = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA');

        if ($request->has('filter_lastname')) {
            $filterLastName = $request->input('filter_lastname');

            if (!empty($filterLastName)) {
                $query->where('LastName', 'LIKE', '%'.$filterLastName.'%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');

            if (!empty($filterFirstName)) {
                $query->where('FirstName', 'LIKE', '%'.$filterFirstName.'%');
            }
        }

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');

            if (!empty($filterSite)) {
                $query->where('Site', 'LIKE', '%'.$filterSite.'%');
            }
        }
        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');

            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd));

                $endDate = date('Y-m-d', strtotime($endDate.' +1 day'));

                $query->where('DateOfApplication', '>=', $startDate)
                    ->where('DateOfApplication', '<', $endDate);
            }
        }

        if ($request->has('filter_contact')) {
            $filterContact = $request->input('filter_contact');

            if (!empty($filterContact)) {
                $query->where('MobileNo', 'LIKE', '%'.$filterContact.'%');
            }
        }

        $filteredData = $query->get();

        $filteredDataArray = $filteredData->toArray();

        return Excel::download(new MyExport($filteredDataArray), 'filtered_perx_data.xlsx');
    }

    public function index()
    {
        $minutes = 60;
        /*   $data = DB::connection('secondary_sqlsrv')
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
        )->get(); */
        $data = cache()->remember('perx_data', $minutes, function () {
            return DB::connection('sqlsrv')
                ->table('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Applicant as ApplicantDetails')
                ->select(
                    'ApplicantDetails.Id as ApplicantId',
                    'ApplicationDetails.AppliedDate as DateOfApplication',
                    'ApplicantDetails.FirstName as FirstName',
                    'ApplicantDetails.LastName as LastName',
                    'ApplicantDetails.MiddleName as MiddleName',
                    'ApplicantDetails.CellphoneNumber as MobileName',
                    'SitesDetails.Name as Site',
                    'GeneralSource.Name as GeneralSource',
                    'SourceOfApplication.Name as SpecSource',

                    'Step.Description as Step',
                    'Status.GeneralStatus as AppStep1',
                    'Status.SpecificStatus as AppStep2',
                    'Referrals.FirstName as ReferrerFirstName',
                    'Referrals.MiddleName as ReferrerMiddleName',
                    'Referrals.LastName as ReferrerLastName',
                    'Referrals.ReferrerHRID as ReffererHRID',
                    'Referrals.ReferrerName as ReffererName'
                )
                ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicantApplications as ApplicationDetails', 'ApplicantDetails.Id', '=', 'ApplicationDetails.ApplicantId')
                ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Status as Status', 'ApplicationDetails.Status', '=', 'Status.Id')
                ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.job as JobDetails', 'ApplicationDetails.JobId', '=', 'JobDetails.Id')
                ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Sites as SitesDetails', 'JobDetails.SiteId', '=', 'SitesDetails.Id')
                ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.ApplicationInformation as ApplicationInformation', 'ApplicantDetails.Id', '=', 'ApplicationInformation.UserID')
                ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.SourceOfApplication as SourceOfApplication', 'ApplicationInformation.SourceOfApplication', '=', 'SourceOfApplication.Id')
                ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.GeneralSource as GeneralSource', 'GeneralSource.Id', '=', 'SourceOfApplication.GeneralSourceId')
                ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Step as Step', 'Step.Id', '=', 'Status.StepId')
                ->leftJoin('SMART_RECRUIT.VXI_SMART_RECRUIT_PH_V2_PROD.dbo.Referrals as Referrals', 'Referrals.UserId', '=', 'ApplicantDetails.Id')
                ->get();
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
                ->where(function ($query) {
                    $query->where('status', 'Cancelled')
                        ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                })
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
        $classStaffing->open = '0';
        $classStaffing->filled = '0';
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
        /*
        $customEmail = 'padillakryss@gmail.com';

        Notification::route('mail', $customEmail)->notify(new ClassNotification($customEmail)); */

        // @ts-ignore
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

        $staffingModel = ClassStaffing::where('classes_id', $class->pushedback_id)
            ->where('active_status', 1)
            ->first();

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        return response()->json([
            'class' => $class,
            'staffingModel' => $staffingModel,
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

    public function classExists(Request $request)
    {
        $query = Classes::query()
            ->where('status', 'Active')
            ->when($request->has('sites_selected'), function ($query) use ($request) {
                $query->where('site_id', $request->sites_selected);
            })
            ->when($request->has('programs_selected'), function ($query) use ($request) {
                $query->where('program_id', $request->programs_selected);
            })
            ->when($request->has('month_selected'), function ($query) use ($request) {
                $query->whereHas('dateRange', function ($dateRangeQuery) use ($request) {
                    $dateRangeQuery->where('month_num', $request->month_selected);
                });
            })
            ->when($request->has('week_selected'), function ($query) use ($request) {
                $query->whereHas('dateRange', function ($dateRangeQuery) use ($request) {
                    $dateRangeQuery->where('date_Range_id', $request->week_selected);
                });
            });

        $classExists = $query->exists();

        return response()->json(['classExists' => $classExists]);
    }

    public function classExists2(Request $request)
    {
        $id = $request->query('id');
        $sites_selected = $request->query('sites_selected');
        $programs_selected = $request->query('programs_selected');
        $date_selected = $request->query('date_selected');
        $criteriaCount = ($sites_selected ? 1 : 0) +
            ($programs_selected ? 1 : 0) +
            ($date_selected ? 1 : 0);
        if ($criteriaCount < 2) {
            return response()->json(['classExists' => false]);
        }
        $query = Classes::query()
            ->where('status', 'Active')
            ->where('id', '!=', $id)
            ->when($sites_selected, function ($query, $sites_selected) {
                return $query->where('site_id', $sites_selected);
            })
            ->when($programs_selected, function ($query, $programs_selected) {
                return $query->where('program_id', $programs_selected);
            })
            ->when($date_selected, function ($query, $date_selected) {
                return $query->whereHas('dateRange', function ($dateRangeQuery) use ($date_selected) {
                    $dateRangeQuery->where('date_Range_id', $date_selected);
                });
            });
        $classExists = $query->exists();

        return response()->json(['classExists' => $classExists]);
    }

    public function classesall(Request $request)
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Philippines');
        })
            ->whereHas('dateRange', function ($query) {
                $query->where('year', '=', '2024');
            })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->when($request->has('sites_selected') && $request->sites_selected !== null, function ($query) use ($request) {
                $query->where('site_id', $request->sites_selected);
            })
            ->when($request->has('programs_selected') && $request->programs_selected !== null, function ($query) use ($request) {
                $query->where('program_id', $request->programs_selected);
            })
            ->when($request->has('month_selected') && $request->month_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('month_num', $request->month_selected);
                });
            })
            ->when($request->has('week_selected') && $request->week_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('date_Range_id', $request->week_selected);
                });
            })
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'site_id' => $class->site->id,
                'program_name' => $class->program->name,
                'program_id' => $class->program->id,
                'date_range' => $class->dateRange->date_range,
                'date_range_id' => $class->dateRange->id,
                'month' => $class->dateRange->month,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at ? $class->created_at->format('m-d-Y H:i') : null,
                'updated_at' => $class->updated_at ? $class->updated_at->format('m-d-Y H:i') : null,
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
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

    //hiring summary
    public function dashboardClassesExport(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
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
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new DashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExport2(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Philippines')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected')) {
            $sitesSelected = $request->input('sites_selected');

            if (!empty($sitesSelected)) {
                $query->where('site_id', $sitesSelected);
            }
        }

        if ($request->has('programs_selected')) {
            $programsSelected = $request->input('programs_selected');

            if (!empty($programsSelected)) {
                $query->where('program_id', $programsSelected);
            }
        }

        if ($request->has('month_selected')) {
            $monthSelected = $request->input('month_selected');

            if (!empty($monthSelected)) {
                $query->whereHas('dateRange', function ($query) use ($monthSelected) {
                    $query->where('month_num', $monthSelected);
                });
            }
        }

        if ($request->has('week_selected')) {
            $weekSelected = $request->input('week_selected');

            if (!empty($weekSelected)) {
                $query->whereHas('dateRange', function ($query) use ($weekSelected) {
                    $query->where('date_Range_id', $weekSelected);
                });
            }
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => $class->notice_weeks,
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        })->toArray();

        return Excel::download(new ClassHistoryExport($formattedClasses), 'Class_history_data.xlsx');
    }

    public function dashboardCancelledClassesExport(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->where(function ($query) {
                $query->where('status', 'Cancelled')
                    ->orWhere('within_sla', 'LIKE', '%Cancellation%');
            })
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Philippines')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected') && $request->sites_selected !== null) {
            $query->where('site_id', $request->sites_selected);
        }

        if ($request->has('programs_selected') && $request->programs_selected !== null) {
            $query->where('program_id', $request->programs_selected);
        }

        if ($request->has('month_selected') && $request->month_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('month_num', $request->month_selected);
            });
        }

        if ($request->has('week_selected') && $request->week_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('date_Range_id', $request->week_selected);
            });
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();
        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        })->toArray();

        return Excel::download(new ClassHistoryExport($formattedClasses), 'cancelledclasses.xlsx');
    }

    public function dashboardClassesExport3(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new DashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExport4(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new DashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExportJamaica(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
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
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExportJamaica2(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Jamaica')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected')) {
            $sitesSelected = $request->input('sites_selected');

            if (!empty($sitesSelected)) {
                $query->where('site_id', $sitesSelected);
            }
        }

        if ($request->has('programs_selected')) {
            $programsSelected = $request->input('programs_selected');

            if (!empty($programsSelected)) {
                $query->where('program_id', $programsSelected);
            }
        }

        if ($request->has('month_selected')) {
            $monthSelected = $request->input('month_selected');

            if (!empty($monthSelected)) {
                $query->whereHas('dateRange', function ($query) use ($monthSelected) {
                    $query->where('month_num', $monthSelected);
                });
            }
        }

        if ($request->has('week_selected')) {
            $weekSelected = $request->input('week_selected');

            if (!empty($weekSelected)) {
                $query->whereHas('dateRange', function ($query) use ($weekSelected) {
                    $query->where('date_Range_id', $weekSelected);
                });
            }
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => $class->notice_weeks,
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        })->toArray();

        return Excel::download(new ClassHistoryExport($formattedClasses), 'Class_history_data.xlsx');
    }

    public function dashboardClassesExportJamaica3(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExportJamaica4(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExportGuatemala(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
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
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExportGuatemala2(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Guatemala')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected')) {
            $sitesSelected = $request->input('sites_selected');

            if (!empty($sitesSelected)) {
                $query->where('site_id', $sitesSelected);
            }
        }

        if ($request->has('programs_selected')) {
            $programsSelected = $request->input('programs_selected');

            if (!empty($programsSelected)) {
                $query->where('program_id', $programsSelected);
            }
        }

        if ($request->has('month_selected')) {
            $monthSelected = $request->input('month_selected');

            if (!empty($monthSelected)) {
                $query->whereHas('dateRange', function ($query) use ($monthSelected) {
                    $query->where('month_num', $monthSelected);
                });
            }
        }

        if ($request->has('week_selected')) {
            $weekSelected = $request->input('week_selected');

            if (!empty($weekSelected)) {
                $query->whereHas('dateRange', function ($query) use ($weekSelected) {
                    $query->where('date_Range_id', $weekSelected);
                });
            }
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => $class->notice_weeks,
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        })->toArray();

        return Excel::download(new ClassHistoryExport($formattedClasses), 'Class_history_data.xlsx');
    }

    public function dashboardClassesExportGuatemala3(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExportGuatemala4(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardSiteClasses(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardSiteOos(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('within_sla', 'Outside SLA - Increase in Demand')
                /* ->orWhere('within_sla', 'Outside SLA - Decrease in Demand (Cancellation)') */
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('out_of_sla');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByProgram[$siteName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['out_of_sla' => 0];
                }
                $groupedClasses[$siteName][$month]['out_of_sla'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['out_of_sla']) ? $weekData['out_of_sla'] : 0;
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardSiteCancelledOos(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();

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
                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where(function ($query) {
                        $query->where('status', 'Active')
                            ->orWhere('status', 'Cancelled');
                    })
                    ->where('site_id', $programId)
                    ->where('within_sla', 'Outside SLA - Decrease in Demand (Cancellation)')
                    ->get();

                $totalTarget = $classes->sum('out_of_sla');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByProgram[$siteName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['out_of_sla' => 0];
                }
                $groupedClasses[$siteName][$month]['out_of_sla'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['out_of_sla']) ? $weekData['out_of_sla'] : 0;
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardSiteClassesMoved(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Moved')
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardSiteClassesCancelled(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardSiteClassesGuatemala(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Guatemala')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardSiteClassesGuatemalaMoved(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Guatemala')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Moved')
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardSiteClassesGuatemalaCancelled(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Guatemala')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardSiteClassesJamaica(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Jamaica')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardSiteClassesJamaicaMoved(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Jamaica')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Moved')
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardSiteClassesJamaicaCancelled(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Jamaica')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardExternalClasses(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('external_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByProgram[$siteName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['external_target' => 0];
                }
                $groupedClasses[$siteName][$month]['external_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['external_target']) ? $weekData['external_target'] : 0;
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardInternalClasses(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();

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

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('internal_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByProgram[$siteName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['internal_target' => 0];
                }
                $groupedClasses[$siteName][$month]['internal_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['internal_target']) ? $weekData['internal_target'] : 0;
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
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    /* public function dashboardClasses(Request $request)
    {
    $siteId = $request->input('site_id');
    $programId = $request->input('program_id');

    $programs = Program::with('site')
    ->when(!empty($siteId), function ($query) use ($siteId) {
    $query->whereIn('site_id', $siteId);
    })
    ->when(!empty($programId), function ($query) use ($programId) {
    $query->whereIn('program_id', $programId);
    })
    ->when(true, function ($query) {
    $query->whereHas('site', function ($subquery) {
    $subquery->where('is_active', 1);
    });

    $query->whereHas('site', function ($subquery) {
    $subquery->where('country', 'Philippines');
    });
    })
    ->where('is_active', 1)
    ->get();

    $year = 2024;
    $dateRanges = DateRange::where('year', $year)->get();

    $groupedClasses = [];
    $grandTotalByWeek = [];
    $grandTotalByMonth = [];
    $grandTotalByProgram = [];
    $grandTotalBySiteByWeek = [];

    foreach ($programs as $program) {
    $siteName = $program->site->name;
    $programName = $program->name;

    if (!isset($grandTotalByProgram[$siteName])) {
    $grandTotalByProgram[$siteName] = [];
    }
    if (!isset($grandTotalBySiteByWeek[$siteName])) {
    $grandTotalBySiteByWeek[$siteName] = [];
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
    $classes1 = Classes::where('site_id', $program->site_id)
    ->where('date_range_id', $dateRange->id)
    ->where('status', 'Active')
    ->get();

    $totalTarget = $classes->sum('total_target');
    $totalTarget1 = $classes1->sum('total_target');

    if (!isset($grandTotalByWeek[$week])) {
    $grandTotalByWeek[$week] = 0;
    }
    if (!isset($grandTotalByMonth[$month])) {
    $grandTotalByMonth[$month] = 0;
    }
    if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
    $grandTotalBySiteByWeek[$siteName][$week] = 0;
    }

    $grandTotalByWeek[$week] += $totalTarget;
    $grandTotalByMonth[$month] += $totalTarget;
    $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;

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

    $mappedGroupedClasses = [];

    foreach ($groupedClasses as $siteName => $siteData) {
    // Insert grand totals by week before individual program data
    $totalGrandWeekly = [];
    foreach (range(53, 104) as $week) {
    $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
    }
    $mappedGroupedClasses[] = array_merge(['Site' => $siteName, 'Program' => 'Grand Total'], $totalGrandWeekly);

    foreach ($siteData as $programName => $programData) {
    $weeklyData = array_fill_keys(range(53, 104), 0);

    foreach ($programData as $week => $weekData) {
    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
    }

    $grandTotal = $grandTotalByProgram[$siteName][$programName];
    if ($grandTotal != 0) {
    $mappedGroupedClasses[] = [
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
    'Aug' => collect($weeklyData)->only([83, 84, 85, 86])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86])->sum() : '',
    'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
    'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
    'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
    'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
    'Sep' => collect($weeklyData)->only([87, 88, 89, 90])->sum() != 0 ? collect($weeklyData)->only([87, 88, 89, 90])->sum() : '',
    'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
    'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
    'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
    'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
    'Oct' => collect($weeklyData)->only([91, 92, 93, 94])->sum() != 0 ? collect($weeklyData)->only([91, 92, 93, 94])->sum() : '',
    'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
    'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
    'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
    'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
    'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
    'Nov' => collect($weeklyData)->only([95, 96, 97, 98, 99])->sum() != 0 ? collect($weeklyData)->only([95, 96, 97, 98, 99])->sum() : '',
    'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
    'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
    'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
    'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
    'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
    'Dec' => collect($weeklyData)->only([100, 101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([100, 101, 102, 103, 104])->sum() : '',
    'Total' => $grandTotal,
    ];
    }
    }
    }

    return $mappedGroupedClasses;
    } */
    public function dashboardClasses(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
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
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClasses2(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Philippines')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected') && $request->sites_selected !== null) {
            $query->where('site_id', $request->sites_selected);
        }

        if ($request->has('programs_selected') && $request->programs_selected !== null) {
            $query->where('program_id', $request->programs_selected);
        }

        if ($request->has('month_selected') && $request->month_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('month_num', $request->month_selected);
            });
        }

        if ($request->has('week_selected') && $request->week_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('date_Range_id', $request->week_selected);
            });
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();
        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
        ]);
    }

    public function dashboardCancelledClasses(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->where(function ($query) {
                $query->where('status', 'Cancelled')
                    ->orWhere('within_sla', 'LIKE', '%Cancellation%');
            })
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Philippines')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected') && $request->sites_selected !== null) {
            $query->where('site_id', $request->sites_selected);
        }

        if ($request->has('programs_selected') && $request->programs_selected !== null) {
            $query->where('program_id', $request->programs_selected);
        }

        if ($request->has('month_selected') && $request->month_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('month_num', $request->month_selected);
            });
        }

        if ($request->has('week_selected') && $request->week_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('date_Range_id', $request->week_selected);
            });
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();
        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
        ]);
    }

    public function dashboardClasses3(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClasses4(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClassesJamaica(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
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
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClassesJamaica2(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Jamaica')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected') && $request->sites_selected !== null) {
            $query->where('site_id', $request->sites_selected);
        }

        if ($request->has('programs_selected') && $request->programs_selected !== null) {
            $query->where('program_id', $request->programs_selected);
        }

        if ($request->has('month_selected') && $request->month_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('month_num', $request->month_selected);
            });
        }

        if ($request->has('week_selected') && $request->week_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('date_Range_id', $request->week_selected);
            });
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();
        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
        ]);
    }

    public function dashboardClassesJamaica3(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClassesJamaica4(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClassesGuatemala(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
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
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClassesGuatemala2(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Guatemala')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected') && $request->sites_selected !== null) {
            $query->where('site_id', $request->sites_selected);
        }

        if ($request->has('programs_selected') && $request->programs_selected !== null) {
            $query->where('program_id', $request->programs_selected);
        }

        if ($request->has('month_selected') && $request->month_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('month_num', $request->month_selected);
            });
        }

        if ($request->has('week_selected') && $request->week_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('date_Range_id', $request->week_selected);
            });
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();
        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
        ]);
    }

    public function dashboardClassesGuatemala3(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where(function ($query) {
                        $query->where('status', 'Cancelled')
                            ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                    })
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClassesGuatemala4(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })
            ->where('is_active', 1)
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];
        $grandTotalBySiteByWeek = [];
        $overallGrandTotalByWeek = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            if (!isset($grandTotalBySiteByWeek[$siteName])) {
                $grandTotalBySiteByWeek[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();
                $classes1 = Classes::where('site_id', $program->site_id)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();

                $totalTarget = $classes->sum('total_target');
                $totalTarget1 = $classes1->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                if (!isset($grandTotalBySiteByWeek[$siteName][$week])) {
                    $grandTotalBySiteByWeek[$siteName][$week] = 0;
                }
                if (!isset($overallGrandTotalByWeek[$week])) {
                    $overallGrandTotalByWeek[$week] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;
                $grandTotalBySiteByWeek[$siteName][$week] += $totalTarget;
                $overallGrandTotalByWeek[$week] += $totalTarget;

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

        $mappedGroupedClasses = [];

        $totalGrandOverallWeekly = [];

        foreach (range(53, 104) as $week) {
            $totalGrandOverallWeekly['Week'.($week - 52)] = isset($overallGrandTotalByWeek[$week]) ? $overallGrandTotalByWeek[$week] : '';
        }
        $mappedtotalGrandOverallWeekly = [
            'Site' => 'GRAND TOTAL',
            'Program' => '',
            'Week1' => $totalGrandOverallWeekly['Week1'] != 0 ? $totalGrandOverallWeekly['Week1'] : '',
            'Week2' => $totalGrandOverallWeekly['Week2'] != 0 ? $totalGrandOverallWeekly['Week2'] : '',
            'Week3' => $totalGrandOverallWeekly['Week3'] != 0 ? $totalGrandOverallWeekly['Week3'] : '',
            'Week4' => $totalGrandOverallWeekly['Week4'] != 0 ? $totalGrandOverallWeekly['Week4'] : '',
            'Jan' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
            'Week5' => $totalGrandOverallWeekly['Week5'] != 0 ? $totalGrandOverallWeekly['Week5'] : '',
            'Week6' => $totalGrandOverallWeekly['Week6'] != 0 ? $totalGrandOverallWeekly['Week6'] : '',
            'Week7' => $totalGrandOverallWeekly['Week7'] != 0 ? $totalGrandOverallWeekly['Week7'] : '',
            'Week8' => $totalGrandOverallWeekly['Week8'] != 0 ? $totalGrandOverallWeekly['Week8'] : '',
            'Feb' => collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
            'Week9' => $totalGrandOverallWeekly['Week9'] != 0 ? $totalGrandOverallWeekly['Week9'] : '',
            'Week10' => $totalGrandOverallWeekly['Week10'] != 0 ? $totalGrandOverallWeekly['Week10'] : '',
            'Week11' => $totalGrandOverallWeekly['Week11'] != 0 ? $totalGrandOverallWeekly['Week11'] : '',
            'Week12' => $totalGrandOverallWeekly['Week12'] != 0 ? $totalGrandOverallWeekly['Week12'] : '',
            'Week13' => $totalGrandOverallWeekly['Week13'] != 0 ? $totalGrandOverallWeekly['Week13'] : '',
            'Mar' => collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
            'Week14' => $totalGrandOverallWeekly['Week14'] != 0 ? $totalGrandOverallWeekly['Week14'] : '',
            'Week15' => $totalGrandOverallWeekly['Week15'] != 0 ? $totalGrandOverallWeekly['Week15'] : '',
            'Week16' => $totalGrandOverallWeekly['Week16'] != 0 ? $totalGrandOverallWeekly['Week16'] : '',
            'Week17' => $totalGrandOverallWeekly['Week17'] != 0 ? $totalGrandOverallWeekly['Week17'] : '',
            'Apr' => collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
            'Week18' => $totalGrandOverallWeekly['Week18'] != 0 ? $totalGrandOverallWeekly['Week18'] : '',
            'Week19' => $totalGrandOverallWeekly['Week19'] != 0 ? $totalGrandOverallWeekly['Week19'] : '',
            'Week20' => $totalGrandOverallWeekly['Week20'] != 0 ? $totalGrandOverallWeekly['Week20'] : '',
            'Week21' => $totalGrandOverallWeekly['Week21'] != 0 ? $totalGrandOverallWeekly['Week21'] : '',
            'May' => collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
            'Week22' => $totalGrandOverallWeekly['Week22'] != 0 ? $totalGrandOverallWeekly['Week22'] : '',
            'Week23' => $totalGrandOverallWeekly['Week23'] != 0 ? $totalGrandOverallWeekly['Week23'] : '',
            'Week24' => $totalGrandOverallWeekly['Week24'] != 0 ? $totalGrandOverallWeekly['Week24'] : '',
            'Week25' => $totalGrandOverallWeekly['Week25'] != 0 ? $totalGrandOverallWeekly['Week25'] : '',
            'Week26' => $totalGrandOverallWeekly['Week26'] != 0 ? $totalGrandOverallWeekly['Week26'] : '',
            'Jun' => collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
            'Week27' => $totalGrandOverallWeekly['Week27'] != 0 ? $totalGrandOverallWeekly['Week27'] : '',
            'Week28' => $totalGrandOverallWeekly['Week28'] != 0 ? $totalGrandOverallWeekly['Week28'] : '',
            'Week29' => $totalGrandOverallWeekly['Week29'] != 0 ? $totalGrandOverallWeekly['Week29'] : '',
            'Week30' => $totalGrandOverallWeekly['Week30'] != 0 ? $totalGrandOverallWeekly['Week30'] : '',
            'Jul' => collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
            'Week31' => $totalGrandOverallWeekly['Week31'] != 0 ? $totalGrandOverallWeekly['Week31'] : '',
            'Week32' => $totalGrandOverallWeekly['Week32'] != 0 ? $totalGrandOverallWeekly['Week32'] : '',
            'Week33' => $totalGrandOverallWeekly['Week33'] != 0 ? $totalGrandOverallWeekly['Week33'] : '',
            'Week34' => $totalGrandOverallWeekly['Week34'] != 0 ? $totalGrandOverallWeekly['Week34'] : '',
            'Week35' => $totalGrandOverallWeekly['Week35'] != 0 ? $totalGrandOverallWeekly['Week35'] : '',
            'Aug' => collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
            'Week36' => $totalGrandOverallWeekly['Week36'] != 0 ? $totalGrandOverallWeekly['Week36'] : '',
            'Week37' => $totalGrandOverallWeekly['Week37'] != 0 ? $totalGrandOverallWeekly['Week37'] : '',
            'Week38' => $totalGrandOverallWeekly['Week38'] != 0 ? $totalGrandOverallWeekly['Week38'] : '',
            'Week39' => $totalGrandOverallWeekly['Week39'] != 0 ? $totalGrandOverallWeekly['Week39'] : '',
            'Sep' => collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
            'Week40' => $totalGrandOverallWeekly['Week40'] != 0 ? $totalGrandOverallWeekly['Week40'] : '',
            'Week41' => $totalGrandOverallWeekly['Week41'] != 0 ? $totalGrandOverallWeekly['Week41'] : '',
            'Week42' => $totalGrandOverallWeekly['Week42'] != 0 ? $totalGrandOverallWeekly['Week42'] : '',
            'Week43' => $totalGrandOverallWeekly['Week43'] != 0 ? $totalGrandOverallWeekly['Week43'] : '',
            'Oct' => collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
            'Week44' => $totalGrandOverallWeekly['Week44'] != 0 ? $totalGrandOverallWeekly['Week44'] : '',
            'Week45' => $totalGrandOverallWeekly['Week45'] != 0 ? $totalGrandOverallWeekly['Week45'] : '',
            'Week46' => $totalGrandOverallWeekly['Week46'] != 0 ? $totalGrandOverallWeekly['Week46'] : '',
            'Week47' => $totalGrandOverallWeekly['Week47'] != 0 ? $totalGrandOverallWeekly['Week47'] : '',
            'Week48' => $totalGrandOverallWeekly['Week48'] != 0 ? $totalGrandOverallWeekly['Week48'] : '',
            'Nov' => collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
            'Week49' => $totalGrandOverallWeekly['Week49'] != 0 ? $totalGrandOverallWeekly['Week49'] : '',
            'Week50' => $totalGrandOverallWeekly['Week50'] != 0 ? $totalGrandOverallWeekly['Week50'] : '',
            'Week51' => $totalGrandOverallWeekly['Week51'] != 0 ? $totalGrandOverallWeekly['Week51'] : '',
            'Week52' => $totalGrandOverallWeekly['Week52'] != 0 ? $totalGrandOverallWeekly['Week52'] : '',
            'Dec' => collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            'GrandTotalByProgram' => collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandOverallWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
        ];
        $mappedGroupedClasses[] = $mappedtotalGrandOverallWeekly;
        foreach ($groupedClasses as $siteName => $siteData) {
            $totalGrandWeekly = [];
            foreach (range(53, 104) as $week) {
                $totalGrandWeekly['Week'.($week - 52)] = isset($grandTotalBySiteByWeek[$siteName][$week]) ? $grandTotalBySiteByWeek[$siteName][$week] : '';
            }
            $mappedTotalGrandWeekly = [
                'Site' => $siteName,
                'Program' => '',
                'Week1' => $totalGrandWeekly['Week1'] != 0 ? $totalGrandWeekly['Week1'] : '',
                'Week2' => $totalGrandWeekly['Week2'] != 0 ? $totalGrandWeekly['Week2'] : '',
                'Week3' => $totalGrandWeekly['Week3'] != 0 ? $totalGrandWeekly['Week3'] : '',
                'Week4' => $totalGrandWeekly['Week4'] != 0 ? $totalGrandWeekly['Week4'] : '',
                'Jan' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4'])->sum() : '',
                'Week5' => $totalGrandWeekly['Week5'] != 0 ? $totalGrandWeekly['Week5'] : '',
                'Week6' => $totalGrandWeekly['Week6'] != 0 ? $totalGrandWeekly['Week6'] : '',
                'Week7' => $totalGrandWeekly['Week7'] != 0 ? $totalGrandWeekly['Week7'] : '',
                'Week8' => $totalGrandWeekly['Week8'] != 0 ? $totalGrandWeekly['Week8'] : '',
                'Feb' => collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week5', 'Week6', 'Week7', 'Week8'])->sum() : '',
                'Week9' => $totalGrandWeekly['Week9'] != 0 ? $totalGrandWeekly['Week9'] : '',
                'Week10' => $totalGrandWeekly['Week10'] != 0 ? $totalGrandWeekly['Week10'] : '',
                'Week11' => $totalGrandWeekly['Week11'] != 0 ? $totalGrandWeekly['Week11'] : '',
                'Week12' => $totalGrandWeekly['Week12'] != 0 ? $totalGrandWeekly['Week12'] : '',
                'Week13' => $totalGrandWeekly['Week13'] != 0 ? $totalGrandWeekly['Week13'] : '',
                'Mar' => collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week9', 'Week10', 'Week11', 'Week12', 'Week13'])->sum() : '',
                'Week14' => $totalGrandWeekly['Week14'] != 0 ? $totalGrandWeekly['Week14'] : '',
                'Week15' => $totalGrandWeekly['Week15'] != 0 ? $totalGrandWeekly['Week15'] : '',
                'Week16' => $totalGrandWeekly['Week16'] != 0 ? $totalGrandWeekly['Week16'] : '',
                'Week17' => $totalGrandWeekly['Week17'] != 0 ? $totalGrandWeekly['Week17'] : '',
                'Apr' => collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week14', 'Week15', 'Week16', 'Week17'])->sum() : '',
                'Week18' => $totalGrandWeekly['Week18'] != 0 ? $totalGrandWeekly['Week18'] : '',
                'Week19' => $totalGrandWeekly['Week19'] != 0 ? $totalGrandWeekly['Week19'] : '',
                'Week20' => $totalGrandWeekly['Week20'] != 0 ? $totalGrandWeekly['Week20'] : '',
                'Week21' => $totalGrandWeekly['Week21'] != 0 ? $totalGrandWeekly['Week21'] : '',
                'May' => collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week18', 'Week19', 'Week20', 'Week21'])->sum() : '',
                'Week22' => $totalGrandWeekly['Week22'] != 0 ? $totalGrandWeekly['Week22'] : '',
                'Week23' => $totalGrandWeekly['Week23'] != 0 ? $totalGrandWeekly['Week23'] : '',
                'Week24' => $totalGrandWeekly['Week24'] != 0 ? $totalGrandWeekly['Week24'] : '',
                'Week25' => $totalGrandWeekly['Week25'] != 0 ? $totalGrandWeekly['Week25'] : '',
                'Week26' => $totalGrandWeekly['Week26'] != 0 ? $totalGrandWeekly['Week26'] : '',
                'Jun' => collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week22', 'Week23', 'Week24', 'Week25', 'Week26'])->sum() : '',
                'Week27' => $totalGrandWeekly['Week27'] != 0 ? $totalGrandWeekly['Week27'] : '',
                'Week28' => $totalGrandWeekly['Week28'] != 0 ? $totalGrandWeekly['Week28'] : '',
                'Week29' => $totalGrandWeekly['Week29'] != 0 ? $totalGrandWeekly['Week29'] : '',
                'Week30' => $totalGrandWeekly['Week30'] != 0 ? $totalGrandWeekly['Week30'] : '',
                'Jul' => collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week27', 'Week28', 'Week29', 'Week30'])->sum() : '',
                'Week31' => $totalGrandWeekly['Week31'] != 0 ? $totalGrandWeekly['Week31'] : '',
                'Week32' => $totalGrandWeekly['Week32'] != 0 ? $totalGrandWeekly['Week32'] : '',
                'Week33' => $totalGrandWeekly['Week33'] != 0 ? $totalGrandWeekly['Week33'] : '',
                'Week34' => $totalGrandWeekly['Week34'] != 0 ? $totalGrandWeekly['Week34'] : '',
                'Week35' => $totalGrandWeekly['Week35'] != 0 ? $totalGrandWeekly['Week35'] : '',
                'Aug' => collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week31', 'Week32', 'Week33', 'Week34', 'Week35'])->sum() : '',
                'Week36' => $totalGrandWeekly['Week36'] != 0 ? $totalGrandWeekly['Week36'] : '',
                'Week37' => $totalGrandWeekly['Week37'] != 0 ? $totalGrandWeekly['Week37'] : '',
                'Week38' => $totalGrandWeekly['Week38'] != 0 ? $totalGrandWeekly['Week38'] : '',
                'Week39' => $totalGrandWeekly['Week39'] != 0 ? $totalGrandWeekly['Week39'] : '',
                'Sep' => collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week36', 'Week37', 'Week38', 'Week39'])->sum() : '',
                'Week40' => $totalGrandWeekly['Week40'] != 0 ? $totalGrandWeekly['Week40'] : '',
                'Week41' => $totalGrandWeekly['Week41'] != 0 ? $totalGrandWeekly['Week41'] : '',
                'Week42' => $totalGrandWeekly['Week42'] != 0 ? $totalGrandWeekly['Week42'] : '',
                'Week43' => $totalGrandWeekly['Week43'] != 0 ? $totalGrandWeekly['Week43'] : '',
                'Oct' => collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week40', 'Week41', 'Week42', 'Week43'])->sum() : '',
                'Week44' => $totalGrandWeekly['Week44'] != 0 ? $totalGrandWeekly['Week44'] : '',
                'Week45' => $totalGrandWeekly['Week45'] != 0 ? $totalGrandWeekly['Week45'] : '',
                'Week46' => $totalGrandWeekly['Week46'] != 0 ? $totalGrandWeekly['Week46'] : '',
                'Week47' => $totalGrandWeekly['Week47'] != 0 ? $totalGrandWeekly['Week47'] : '',
                'Week48' => $totalGrandWeekly['Week48'] != 0 ? $totalGrandWeekly['Week48'] : '',
                'Nov' => collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week44', 'Week45', 'Week46', 'Week47', 'Week48'])->sum() : '',
                'Week49' => $totalGrandWeekly['Week49'] != 0 ? $totalGrandWeekly['Week49'] : '',
                'Week50' => $totalGrandWeekly['Week50'] != 0 ? $totalGrandWeekly['Week50'] : '',
                'Week51' => $totalGrandWeekly['Week51'] != 0 ? $totalGrandWeekly['Week51'] : '',
                'Week52' => $totalGrandWeekly['Week52'] != 0 ? $totalGrandWeekly['Week52'] : '',
                'Dec' => collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
                'GrandTotalByProgram' => collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() != 0 ? collect($totalGrandWeekly)->only(['Week1', 'Week2', 'Week3', 'Week4', 'Week5', 'Week6', 'Week7', 'Week8', 'Week9', 'Week10', 'Week11', 'Week12', 'Week13', 'Week14', 'Week15', 'Week16', 'Week17', 'Week18', 'Week19', 'Week20', 'Week21', 'Week22', 'Week23', 'Week24', 'Week25', 'Week26', 'Week27', 'Week28', 'Week29', 'Week30', 'Week31', 'Week32', 'Week33', 'Week34', 'Week35', 'Week36', 'Week37', 'Week38', 'Week39', 'Week40', 'Week41', 'Week42', 'Week43', 'Week44', 'Week45', 'Week46', 'Week47', 'Week48', 'Week49', 'Week50', 'Week51', 'Week52'])->sum() : '',
            ];

            if ($mappedTotalGrandWeekly['GrandTotalByProgram'] != 0) {
                $mappedGroupedClasses[] = $mappedTotalGrandWeekly;
            }

            foreach ($siteData as $programName => $programData) {
                $weeklyData = array_fill_keys(range(53, 104), 0);

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }

                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
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

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function classesallJam(Request $request)
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Jamaica');
        })
            ->whereHas('dateRange', function ($query) {
                $query->where('year', '=', '2024');
            })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->when($request->has('sites_selected') && $request->sites_selected !== null, function ($query) use ($request) {
                $query->where('site_id', $request->sites_selected);
            })
            ->when($request->has('programs_selected') && $request->programs_selected !== null, function ($query) use ($request) {
                $query->where('program_id', $request->programs_selected);
            })
            ->when($request->has('month_selected') && $request->month_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('month_num', $request->month_selected);
                });
            })
            ->when($request->has('week_selected') && $request->week_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('date_Range_id', $request->week_selected);
                });
            })
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'site_id' => $class->site->id,
                'program_name' => $class->program->name,
                'program_id' => $class->program->id,
                'date_range' => $class->dateRange->date_range,
                'date_range_id' => $class->dateRange->id,
                'month' => $class->dateRange->month,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at ? $class->created_at->format('m-d-Y H:i') : null,
                'updated_at' => $class->updated_at ? $class->updated_at->format('m-d-Y H:i') : null,
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
        ]);
    }

    public function classesallGua(Request $request)
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Guatemala');
        })
            ->whereHas('dateRange', function ($query) {
                $query->where('year', '=', '2024');
            })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->when($request->has('sites_selected') && $request->sites_selected !== null, function ($query) use ($request) {
                $query->where('site_id', $request->sites_selected);
            })
            ->when($request->has('programs_selected') && $request->programs_selected !== null, function ($query) use ($request) {
                $query->where('program_id', $request->programs_selected);
            })
            ->when($request->has('month_selected') && $request->month_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('month_num', $request->month_selected);
                });
            })
            ->when($request->has('week_selected') && $request->week_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('date_Range_id', $request->week_selected);
                });
            })
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'site_id' => $class->site->id,
                'program_name' => $class->program->name,
                'program_id' => $class->program->id,
                'date_range' => $class->dateRange->date_range,
                'date_range_id' => $class->dateRange->id,
                'month' => $class->dateRange->month,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at ? $class->created_at->format('m-d-Y H:i') : null,
                'updated_at' => $class->updated_at ? $class->updated_at->format('m-d-Y H:i') : null,
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
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
        $changes = [$request->changes];
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);
        $newClass = $class->replicate();
        $newClass->update_status = $class->update_status + 1;
        $newClass->status = $request->status;
        $newClass->requested_by = json_encode($requested_by);
        $newClass->changes = json_encode($changes);
        $newClass->save();

        $class->fill($request->all());
        $class->changes = 'Add Class';
        $class->status = 'Active';
        $class->updated_at = Carbon::now()->format('Y-m-d H:i');
        $class->save();

        if ($request->within_sla == 'Outside SLA - Increase in Demand' || $request->within_sla == 'Outside SLA - Decrease in Demand (Cancellation)') {
            $out_of_sla_difference = abs($newClass->total_target - $class->total_target);
            $class->out_of_sla = $out_of_sla_difference;
            $class->save();
        }

        $staffingModel = ClassStaffing::where('classes_id', $class->pushedback_id)
            ->where('active_status', 1)
            ->first();

        $classTarget = $class->total_target ?? 0;
        $showUpsTotal = $staffingModel->show_ups_total ?? 0;
        $showUpsInternal = $staffingModel->show_ups_internal ?? 0;
        $pipelineTotal = $staffingModel->pipeline_total ?? 0;
        $filled = $staffingModel->filled ?? 0;
        $deficit = max(0, floatval($classTarget) - floatval($showUpsTotal));
        $percentage = intval($classTarget) === 0 ? '0%' : number_format((intval($showUpsTotal) / intval($classTarget)) * 100, 2).'%';
        $overHires = max(0, intval($showUpsTotal) - intval($classTarget));
        $classNumber = intval($classTarget) % 15 > 1
        ? floor(intval($classTarget) / 15) + 1
        : floor(intval($classTarget) / 15);
        $open = max(0, intval($classNumber) - intval($filled));
        $capStart = intval($showUpsTotal) > intval($classTarget)
        ? intval($classTarget)
        : intval($showUpsTotal);
        $internalHires = ($showUpsInternal >= $classTarget && ($deficit === '' || $deficit === 0)) ? 1 : 0;
        $pipelineTarget = ($pipelineTotal > $classTarget) ? $classTarget : $pipelineTotal;

        $staffingModel->cap_starts = $open;
        $staffingModel->open = $capStart;
        $staffingModel->classes_number = $classNumber;
        $staffingModel->deficit = $deficit;
        $staffingModel->percentage = $percentage;
        $staffingModel->over_hires = $overHires;
        $staffingModel->internals_hires_all = $internalHires;
        $staffingModel->pipeline_target = $pipelineTarget;
        $staffingModel->save();

        return new ClassesResource($newClass);
    }

    public function editCancelled(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pipeline_offered' => 'required',
            'pipeline_utilized' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);
        $class->fill($request->all());
        $class->save();

        // @ts-ignore
        return new ClassesResource($class);
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

        // @ts-ignore
        return new ClassesResource($class);
    }

    public function cancel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'site_id' => 'required',
            'program_id' => 'required',
            'date_range_id' => 'required',
            'cancelled_by' => 'required',
            'pipeline_utilized' => 'required',
            'pipeline_offered' => 'required',
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
        $newClass = $class->replicate();
        $newClass->cancelled_by = json_encode($cancelled_by);
        $newClass->changes = 'Cancellation';
        $newClass->cancelled_date = $request->input('cancelled_date');
        $newClass->status = 'Cancelled';
        $newClass->save();
        $class->status = 'Cancelled Class';
        $class->cancelled_date = $request->input('cancelled_date');
        $class->save();

        $staffingModel = ClassStaffing::where('classes_id', $class->pushedback_id)
            ->where('active_status', 1)
            ->first();
        $staffingModel->active_status = 0;
        $staffingModel->save();

        // @ts-ignore
        return new ClassesResource($class);
    }

    public function retrieveB2DataForEmail(Request $request)
    {
        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();
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
                        $subquery->where('is_active', 1)->where('program_type', 'B2')->orWhere('program_type', 'COMCAST');
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
            'January' => $grandTotalByWeek2['1'] != 0 ? round(($grandTotalByWeek['1'] / $grandTotalByWeek2['1']) * 100, 2).'%' : '0%',
            'February' => $grandTotalByWeek2['2'] != 0 ? round(($grandTotalByWeek['2'] / $grandTotalByWeek2['2']) * 100, 2).'%' : '0%',
            'March' => $grandTotalByWeek2['3'] != 0 ? round(($grandTotalByWeek['3'] / $grandTotalByWeek2['3']) * 100, 2).'%' : '0%',
            'April' => $grandTotalByWeek2['4'] != 0 ? round(($grandTotalByWeek['4'] / $grandTotalByWeek2['4']) * 100, 2).'%' : '0%',
            'May' => $grandTotalByWeek2['5'] != 0 ? round(($grandTotalByWeek['5'] / $grandTotalByWeek2['5']) * 100, 2).'%' : '0%',
            'June' => $grandTotalByWeek2['6'] != 0 ? round(($grandTotalByWeek['6'] / $grandTotalByWeek2['6']) * 100, 2).'%' : '0%',
            'July' => $grandTotalByWeek2['7'] != 0 ? round(($grandTotalByWeek['7'] / $grandTotalByWeek2['7']) * 100, 2).'%' : '0%',
            'August' => $grandTotalByWeek2['8'] != 0 ? round(($grandTotalByWeek['8'] / $grandTotalByWeek2['8']) * 100, 2).'%' : '0%',
            'September' => $grandTotalByWeek2['9'] != 0 ? round(($grandTotalByWeek['9'] / $grandTotalByWeek2['9']) * 100, 2).'%' : '0%',
            'October' => $grandTotalByWeek2['10'] != 0 ? round(($grandTotalByWeek['10'] / $grandTotalByWeek2['10']) * 100, 2).'%' : '0%',
            'November' => $grandTotalByWeek2['11'] != 0 ? round(($grandTotalByWeek['11'] / $grandTotalByWeek2['11']) * 100, 2).'%' : '0%',
            'December' => $grandTotalByWeek2['12'] != 0 ? round(($grandTotalByWeek['12'] / $grandTotalByWeek2['12']) * 100, 2).'%' : '0%',
            'GrandTotalByProgram' => $grandTotalForAllPrograms2 != 0 ? round(($grandTotalForAllPrograms / $grandTotalForAllPrograms2) * 100, 2).'%' : '0%',
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

        $response = ['data' => $mappedB2Classes];

        return response()->json($response);
    }

    public function retrieveB2DataForEmailJamaica(Request $request)
    {
        $programs = Site::where('is_active', 1)->where('country', 'Jamaica')->get();
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
                        $subquery->where('is_active', 1)->where('program_type', 'B2')->orWhere('program_type', 'COMCAST');
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
            'January' => $grandTotalByWeek2['1'] != 0 ? round(($grandTotalByWeek['1'] / $grandTotalByWeek2['1']) * 100, 2).'%' : '0%',
            'February' => $grandTotalByWeek2['2'] != 0 ? round(($grandTotalByWeek['2'] / $grandTotalByWeek2['2']) * 100, 2).'%' : '0%',
            'March' => $grandTotalByWeek2['3'] != 0 ? round(($grandTotalByWeek['3'] / $grandTotalByWeek2['3']) * 100, 2).'%' : '0%',
            'April' => $grandTotalByWeek2['4'] != 0 ? round(($grandTotalByWeek['4'] / $grandTotalByWeek2['4']) * 100, 2).'%' : '0%',
            'May' => $grandTotalByWeek2['5'] != 0 ? round(($grandTotalByWeek['5'] / $grandTotalByWeek2['5']) * 100, 2).'%' : '0%',
            'June' => $grandTotalByWeek2['6'] != 0 ? round(($grandTotalByWeek['6'] / $grandTotalByWeek2['6']) * 100, 2).'%' : '0%',
            'July' => $grandTotalByWeek2['7'] != 0 ? round(($grandTotalByWeek['7'] / $grandTotalByWeek2['7']) * 100, 2).'%' : '0%',
            'August' => $grandTotalByWeek2['8'] != 0 ? round(($grandTotalByWeek['8'] / $grandTotalByWeek2['8']) * 100, 2).'%' : '0%',
            'September' => $grandTotalByWeek2['9'] != 0 ? round(($grandTotalByWeek['9'] / $grandTotalByWeek2['9']) * 100, 2).'%' : '0%',
            'October' => $grandTotalByWeek2['10'] != 0 ? round(($grandTotalByWeek['10'] / $grandTotalByWeek2['10']) * 100, 2).'%' : '0%',
            'November' => $grandTotalByWeek2['11'] != 0 ? round(($grandTotalByWeek['11'] / $grandTotalByWeek2['11']) * 100, 2).'%' : '0%',
            'December' => $grandTotalByWeek2['12'] != 0 ? round(($grandTotalByWeek['12'] / $grandTotalByWeek2['12']) * 100, 2).'%' : '0%',
            'GrandTotalByProgram' => $grandTotalForAllPrograms2 != 0 ? round(($grandTotalForAllPrograms / $grandTotalForAllPrograms2) * 100, 2).'%' : '0%',
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

        $response = ['data' => $mappedB2Classes];

        return response()->json($response);
    }

    public function retrieveB2DataForEmailGuatemala(Request $request)
    {
        $programs = Site::where('is_active', 1)->where('country', 'Guatemala')->get();
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
                        $subquery->where('is_active', 1)->where('program_type', 'B2')->orWhere('program_type', 'COMCAST');
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
            'January' => $grandTotalByWeek2['1'] != 0 ? round(($grandTotalByWeek['1'] / $grandTotalByWeek2['1']) * 100, 2).'%' : '0%',
            'February' => $grandTotalByWeek2['2'] != 0 ? round(($grandTotalByWeek['2'] / $grandTotalByWeek2['2']) * 100, 2).'%' : '0%',
            'March' => $grandTotalByWeek2['3'] != 0 ? round(($grandTotalByWeek['3'] / $grandTotalByWeek2['3']) * 100, 2).'%' : '0%',
            'April' => $grandTotalByWeek2['4'] != 0 ? round(($grandTotalByWeek['4'] / $grandTotalByWeek2['4']) * 100, 2).'%' : '0%',
            'May' => $grandTotalByWeek2['5'] != 0 ? round(($grandTotalByWeek['5'] / $grandTotalByWeek2['5']) * 100, 2).'%' : '0%',
            'June' => $grandTotalByWeek2['6'] != 0 ? round(($grandTotalByWeek['6'] / $grandTotalByWeek2['6']) * 100, 2).'%' : '0%',
            'July' => $grandTotalByWeek2['7'] != 0 ? round(($grandTotalByWeek['7'] / $grandTotalByWeek2['7']) * 100, 2).'%' : '0%',
            'August' => $grandTotalByWeek2['8'] != 0 ? round(($grandTotalByWeek['8'] / $grandTotalByWeek2['8']) * 100, 2).'%' : '0%',
            'September' => $grandTotalByWeek2['9'] != 0 ? round(($grandTotalByWeek['9'] / $grandTotalByWeek2['9']) * 100, 2).'%' : '0%',
            'October' => $grandTotalByWeek2['10'] != 0 ? round(($grandTotalByWeek['10'] / $grandTotalByWeek2['10']) * 100, 2).'%' : '0%',
            'November' => $grandTotalByWeek2['11'] != 0 ? round(($grandTotalByWeek['11'] / $grandTotalByWeek2['11']) * 100, 2).'%' : '0%',
            'December' => $grandTotalByWeek2['12'] != 0 ? round(($grandTotalByWeek['12'] / $grandTotalByWeek2['12']) * 100, 2).'%' : '0%',
            'GrandTotalByProgram' => $grandTotalForAllPrograms2 != 0 ? round(($grandTotalForAllPrograms / $grandTotalForAllPrograms2) * 100, 2).'%' : '0%',
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

        $response = ['data' => $mappedB2Classes];

        return response()->json($response);
    }

    //For Web Routes
    public function WebDashboardSiteClasses(Request $request, CapEmailController $emailController)
    {
        $mappedGroupedClasses = $emailController->retrieveDataForEmail();

        return view('email.view', compact('mappedGroupedClasses'));
    }

    public function WebDashboardExternalClasses(Request $request, CapEmailController $emailController)
    {
        $mappedGroupedClasses = $emailController->retrieveExternalForEmail();

        return view('email.view', compact('mappedExternalClasses'));
    }

    public function WebDashboardInternalClasses(Request $request, CapEmailController $emailController)
    {
        $mappedGroupedClasses = $emailController->retrieveInternalForEmail();

        return view('email.view', compact('mappedInternalClasses'));
    }

    public function WebDashboardClasses(Request $request, CapEmailController $emailController)
    {
        $mappedClasses = $emailController->retrieveDataForClassesEmail();

        return view('email.view', compact('mappedClasses'));
    }

    public function WebDashboardB2Classes(Request $request, CapEmailController $emailController)
    {
        $mappedB2Classes = $emailController->retrieveB2DataForEmail();

        return view('email.view', compact('mappedB2Classes'));
    }

    public function AutomatedSr(Request $request, CapEmailController $emailController)
    {
        $mappedResult = $emailController->srComplianceExport();

        return view('sr_pending_email.view', compact('mappedResult'));
    }

    public function AutomatedSrExportData(Request $request, CapEmailController $emailController)
    {
        $formattedResult = $emailController->AutomatedSrExport();

        return view('sr_pending_email.view', compact('formattedResult'));
    }

    public function weeklyPipeForEmail(Request $request, CapEmailController $emailController)
    {
        $weeklyPipe = $emailController->weeklyPipe();

        return view('staffing.view', compact('weeklyPipe'));
    }

    public function wtdForEmail(Request $request)
    {
        $wtd = $this->wtd();

        return view('staffing.view', compact('wtd'));
    }

    public function ytdForEmail(Request $request)
    {
        $ytd = $this->ytd();

        return view('staffing.view', compact('ytd'));
    }

    public function OutOfSla()
    {
        $sites = Site::where('is_active', 1)->where('country', 'Philippines')->get();
        $year = 2024;
        $grandTotalByProgram = [];
        $grandTotalByWeeks = []; // Initialize array to accumulate notice weeks
        $maxProgramBySite = [];

        foreach ($sites as $site) {
            $siteName = $site->name;
            $siteId = $site->id;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            if (!isset($grandTotalByWeeks[$siteName])) {
                $grandTotalByWeeks[$siteName] = 0; // Initialize notice weeks accumulator
            }
            $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                ->whereHas('dateRange', function ($subquery) use ($year) {
                    $subquery->where('year', $year);
                })
                ->whereHas('program', function ($subquery) {
                    $subquery->where('is_active', 1);
                })
                ->where('site_id', $siteId)
                ->where('status', 'Active')
                ->where('within_sla', 'Outside SLA-New class added')
                ->get();
            $totalTarget = $classes->sum('total_target');
            $notice_weeks = $classes->avg('notice_weeks');
            $grandTotalByProgram[$siteName] += $totalTarget;
            $grandTotalByWeeks[$siteName] += $notice_weeks; // Accumulate notice weeks
            $maxTotalTarget = $classes->max('total_target');
            $classesWithMaxTarget = $classes->filter(function ($class) use ($maxTotalTarget) {
                return $class->total_target == $maxTotalTarget;
            });
            $maxProgramIds = $classesWithMaxTarget->pluck('program_id')->toArray();
            $maxProgramNames = Program::whereIn('id', $maxProgramIds)->pluck('program_group')->toArray();

            $maxProgramBySite[$siteId] = [
                'program_ids' => $maxProgramIds,
                'program_names' => $maxProgramNames,
            ];
        }
        $mappedGroupedClasses = [];
        $totalHC = 0;
        $totalNoticeWeeks = 0; // Initialize total notice weeks
        foreach ($sites as $site) {
            $siteName = $site->name;
            $siteId = $site->id;
            $notice_weeks_avg = $grandTotalByWeeks[$siteName]; // Use the accumulated notice weeks
            $totalHC += $grandTotalByProgram[$siteName]; // Accumulate total HC

            $maxPrograms = isset($maxProgramBySite[$siteId]) ? $maxProgramBySite[$siteId]['program_names'] : [];

            $mappedGroupedClasses[] = [
                'Site' => $siteName,
                'HC' => $grandTotalByProgram[$siteName],
                'Notice Weeks' => number_format($notice_weeks_avg, 2), // Format to two decimal places
                'Drivers' => $maxPrograms,
            ];

            // Accumulate total notice weeks
            $totalNoticeWeeks += $notice_weeks_avg;
        }

        // Calculate total average notice weeks
        $totalAverageNoticeWeeks = $totalNoticeWeeks / count($sites);

        // Add totals to the result
        $mappedGroupedClasses[] = [
            'Site' => 'Total',
            'HC' => $totalHC,
            'Notice Weeks' => number_format($totalAverageNoticeWeeks, 2), // Format to two decimal places
            'Drivers' => [], // No need to include Drivers for the total row
        ];

        return [
            'grouped_classes' => $mappedGroupedClasses,
        ];
    }

    public function OutOfSlaMonth()
    {
        $sites = Site::where('is_active', 1)->where('country', 'Philippines')->get();
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
        $grandTotalByProgram = [];
        $grandTotalByWeeks = []; // Initialize array to accumulate notice weeks
        $maxProgramBySite = [];

        foreach ($sites as $site) {
            $siteName = $site->name;
            $siteId = $site->id;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            if (!isset($grandTotalByWeeks[$siteName])) {
                $grandTotalByWeeks[$siteName] = 0; // Initialize notice weeks accumulator
            }
            $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                    $subquery->where('month_num', $month)->where('year', $year);
                })
                ->whereHas('program', function ($subquery) {
                    $subquery->where('is_active', 1);
                })
                ->where('site_id', $siteId)
                ->where('status', 'Active')
                ->where('within_sla', 'Outside SLA-New class added')
                ->get();
            $totalTarget = $classes->sum('total_target');
            $notice_weeks = $classes->avg('notice_weeks');
            $grandTotalByProgram[$siteName] += $totalTarget;
            $grandTotalByWeeks[$siteName] += $notice_weeks; // Accumulate notice weeks
            $maxTotalTarget = $classes->max('total_target');
            $classesWithMaxTarget = $classes->filter(function ($class) use ($maxTotalTarget) {
                return $class->total_target == $maxTotalTarget;
            });
            $maxProgramIds = $classesWithMaxTarget->pluck('program_id')->toArray();
            $maxProgramNames = Program::whereIn('id', $maxProgramIds)->pluck('program_group')->toArray();

            $maxProgramBySite[$siteId] = [
                'program_ids' => $maxProgramIds,
                'program_names' => $maxProgramNames,
            ];
        }
        $mappedGroupedClasses = [];
        $totalHC = 0;
        $totalNoticeWeeks = 0; // Initialize total notice weeks
        foreach ($sites as $site) {
            $siteName = $site->name;
            $siteId = $site->id;
            $notice_weeks_avg = $grandTotalByWeeks[$siteName]; // Use the accumulated notice weeks
            $totalHC += $grandTotalByProgram[$siteName]; // Accumulate total HC

            $maxPrograms = isset($maxProgramBySite[$siteId]) ? $maxProgramBySite[$siteId]['program_names'] : [];

            $mappedGroupedClasses[] = [
                'Site' => $siteName,
                'HC' => $grandTotalByProgram[$siteName],
                'Notice Weeks' => number_format($notice_weeks_avg, 2), // Format to two decimal places
                'Drivers' => $maxPrograms,
            ];

            // Accumulate total notice weeks
            $totalNoticeWeeks += $notice_weeks_avg;
        }

        // Calculate total average notice weeks
        $totalAverageNoticeWeeks = $totalNoticeWeeks / count($sites);

        // Add totals to the result
        $mappedGroupedClasses[] = [
            'Site' => 'Total',
            'HC' => $totalHC,
            'Notice Weeks' => number_format($totalAverageNoticeWeeks, 2), // Format to two decimal places
            'Drivers' => [], // No need to include Drivers for the total row
        ];

        return [
            'grouped_classes' => $mappedGroupedClasses,
        ];
    }

    public function CancelledMonth()
    {
        $sites = Site::where('is_active', 1)->where('country', 'Philippines')->get();
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

        $grandTotalByProgram = [];
        $grandTotalByWeeks = [];
        $grandTotalByPipeline = [];
        $maxProgramBySite = [];
        foreach ($sites as $site) {
            $siteName = $site->name;
            $siteId = $site->id;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            if (!isset($grandTotalByWeeks[$siteName])) {
                $grandTotalByWeeks[$siteName] = 0;
            }
            if (!isset($grandTotalByPipeline[$siteName])) {
                $grandTotalByPipeline[$siteName] = 0;
            }
            $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                    $subquery->where('month_num', $month)->where('year', $year);
                })
                ->whereHas('program', function ($subquery) {
                    $subquery->where('is_active', 1);
                })
                ->where('site_id', $siteId)
                ->where(function ($query) {
                    $query->where('status', 'Cancelled')
                        ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                })
                ->get();
            $totalTarget = $classes->sum('total_target');
            $pipelineOffered = $classes->sum('pipeline_offered');
            $notice_weeks = $classes->avg('notice_weeks');
            $grandTotalByProgram[$siteName] += $totalTarget;
            $grandTotalByWeeks[$siteName] += $notice_weeks;
            $grandTotalByPipeline[$siteName] += $pipelineOffered;
            $maxTotalTarget = $classes->max('total_target');
            $classesWithMaxTarget = $classes->filter(function ($class) use ($maxTotalTarget) {
                return $class->total_target == $maxTotalTarget;
            });
            $maxProgramIds = $classesWithMaxTarget->pluck('program_id')->toArray();
            $maxProgramNames = Program::whereIn('id', $maxProgramIds)->pluck('program_group')->toArray();

            $maxProgramBySite[$siteId] = [
                'program_ids' => $maxProgramIds,
                'program_names' => $maxProgramNames,
            ];
        }
        $totalHC = 0;
        $totalPipelineOffered = 0;
        foreach ($sites as $site) {
            $siteId = $site->id;
            $totalHC += $grandTotalByProgram[$site->name];
            $totalPipelineOffered += $grandTotalByPipeline[$site->name];
        }
        $totalNoticeWeeks = 0;
        foreach ($grandTotalByWeeks as $notice_weeks) {
            $totalNoticeWeeks += $notice_weeks;
        }
        $totalNoticeWeeks /= count($sites);
        $mappedGroupedClasses = [];
        foreach ($sites as $site) {
            $siteId = $site->id;
            $maxPrograms = isset($maxProgramBySite[$siteId]) ? $maxProgramBySite[$siteId]['program_names'] : [];
            $mappedGroupedClasses[] = [
                'Site' => $site->name,
                'HC' => $grandTotalByProgram[$site->name],
                'Notice Weeks' => number_format($grandTotalByWeeks[$site->name], 2),
                'Pipeline Offered' => $grandTotalByPipeline[$site->name],
                'Drivers' => $maxPrograms,
            ];
        }
        $mappedGroupedClasses[] = [
            'Site' => 'Total',
            'HC' => $totalHC,
            'Notice Weeks' => number_format($totalNoticeWeeks, 2),
            'Pipeline Offered' => $totalPipelineOffered,
            'Drivers' => [],
        ];

        return [
            'grouped_classes' => $mappedGroupedClasses,
        ];
    }

    public function Cancelled()
    {
        $sites = Site::where('is_active', 1)->where('country', 'Philippines')->get();
        $year = 2024;

        $grandTotalByProgram = [];
        $grandTotalByWeeks = [];
        $grandTotalByPipeline = [];
        $maxProgramBySite = [];
        foreach ($sites as $site) {
            $siteName = $site->name;
            $siteId = $site->id;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            if (!isset($grandTotalByWeeks[$siteName])) {
                $grandTotalByWeeks[$siteName] = 0;
            }
            if (!isset($grandTotalByPipeline[$siteName])) {
                $grandTotalByPipeline[$siteName] = 0;
            }
            $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                ->whereHas('dateRange', function ($subquery) use ($year) {
                    $subquery->where('year', $year);
                })
                ->whereHas('program', function ($subquery) {
                    $subquery->where('is_active', 1);
                })
                ->where('site_id', $siteId)
                ->where(function ($query) {
                    $query->where('status', 'Cancelled')
                        ->orWhere('within_sla', 'LIKE', '%Cancellation%');
                })
                ->get();

            $totalTarget = $classes->sum('total_target');
            $pipelineOffered = $classes->sum('pipeline_offered');
            $notice_weeks = $classes->avg('notice_weeks');
            $grandTotalByProgram[$siteName] += $totalTarget;
            $grandTotalByWeeks[$siteName] += $notice_weeks;
            $grandTotalByPipeline[$siteName] += $pipelineOffered;
            $maxTotalTarget = $classes->max('total_target');
            $classesWithMaxTarget = $classes->filter(function ($class) use ($maxTotalTarget) {
                return $class->total_target == $maxTotalTarget;
            });
            $maxProgramIds = $classesWithMaxTarget->pluck('program_id')->toArray();
            $maxProgramNames = Program::whereIn('id', $maxProgramIds)->pluck('program_group')->toArray();

            $maxProgramBySite[$siteId] = [
                'program_ids' => $maxProgramIds,
                'program_names' => $maxProgramNames,
            ];
        }
        $totalHC = 0;
        $totalPipelineOffered = 0;
        foreach ($sites as $site) {
            $siteId = $site->id;
            $totalHC += $grandTotalByProgram[$site->name];
            $totalPipelineOffered += $grandTotalByPipeline[$site->name];
        }
        $totalNoticeWeeks = 0;
        foreach ($grandTotalByWeeks as $notice_weeks) {
            $totalNoticeWeeks += $notice_weeks;
        }
        $totalNoticeWeeks /= count($sites);
        $mappedGroupedClasses = [];
        foreach ($sites as $site) {
            $siteId = $site->id;
            $maxPrograms = isset($maxProgramBySite[$siteId]) ? $maxProgramBySite[$siteId]['program_names'] : [];
            $mappedGroupedClasses[] = [
                'Site' => $site->name,
                'HC' => $grandTotalByProgram[$site->name],
                'Notice Weeks' => number_format($grandTotalByWeeks[$site->name], 2),
                'Pipeline Offered' => $grandTotalByPipeline[$site->name],
                'Drivers' => $maxPrograms,
            ];
        }
        $mappedGroupedClasses[] = [
            'Site' => 'Total',
            'HC' => $totalHC,
            'Notice Weeks' => number_format($totalNoticeWeeks, 2),
            'Pipeline Offered' => $totalPipelineOffered,
            'Drivers' => [],
        ];

        return [
            'grouped_classes' => $mappedGroupedClasses,
        ];
    }
}
