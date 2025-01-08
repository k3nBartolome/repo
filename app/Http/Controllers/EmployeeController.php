<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeImport;
use App\Models\Employee;
use App\Models\Requirements;
use App\Models\Lob;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use App\Exports\EmployeeExport;


class EmployeeController extends Controller
{
    public function getEmployee($id)
{
    $employee = Employee::findOrFail($id);

    return response()->json([
        'employee_id' => $employee->employee_id,
        'first_name' => $employee->first_name,
        'middle_name' => $employee->middle_name,
        'last_name' => $employee->last_name,
        'employee_status' => $employee->employee_status,
        'hired_date' => $employee->hired_date,
        'hired_month' => $employee->hired_month,
        'birthdate' => $employee->birthdate,
        'contact_number' => $employee->contact_number,
        'email' => $employee->email,
        'employment_status' => $employee->employment_status,
        'position' => $employee->account_associate,
    ]);
}
public function getLob($id)
{
    $employee = Lob::where('employee_tbl_id', $id)->first();

    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found.',
        ], 404);
    }

    return response()->json([
        'data' => $employee,
    ]);
}
public function getNbi($id)
{
    Log::info('Get NBI Request Received', ['id' => $id]);
    $requirement = Requirements::where('employee_tbl_id', $id)->first();

    if (!$requirement) {
        Log::error('Requirement not found', ['id' => $id]);
        return response()->json(['error' => 'Requirement not found'], 404);
    }
    $data = [
        'nbi_final_status' => $requirement->nbi_final_status,
        'nbi_validity_date' => $requirement->nbi_validity_date,
        'nbi_submitted_date' => $requirement->nbi_submitted_date,
        'nbi_printed_date' => $requirement->nbi_printed_date,
        'nbi_remarks' => $requirement->nbi_remarks,
        'nbi_updated_by' => $requirement->nbi_updated_by,
        'nbi_last_updated_at' => $requirement->nbi_last_updated_at,
        'nbi_file_name' => $requirement->nbi_file_name ? asset('storage/nbi_files/' . $requirement->nbi_file_name) : null,
    ];

    Log::info('NBI Data Retrieved Successfully', ['data' => $data]);

    return response()->json(['data' => $data]);
}

public function getDT($id)
{
    Log::info('Get DT Request Received', ['id' => $id]);
    $requirement = Requirements::where('employee_tbl_id', $id)->first();

    if (!$requirement) {
        Log::error('Requirement not found', ['id' => $id]);
        return response()->json(['error' => 'Requirement not found'], 404);
    }
    $data = [
        'dt_final_status' => $requirement->dt_final_status,
        'dt_results_date' => $requirement->dt_results_date,
        'dt_transaction_date' => $requirement->dt_transaction_date,
        'dt_endorsed_date' => $requirement->dt_endorsed_date,
        'dt_remarks' => $requirement->dt_remarks,
        'dt_updated_by' => $requirement->dt_updated_by,
        'dt_last_updated_at' => $requirement->dt_last_updated_at,
        'dt_file_name' => $requirement->dt_file_name ? asset('storage/dt_files/' . $requirement->dt_file_name) : null,
    ];

    Log::info('DT Data Retrieved Successfully', ['data' => $data]);

    return response()->json(['data' => $data]);
}
public function getPeme($id)
{
    Log::info('Get DT Request Received', ['id' => $id]);
    $requirement = Requirements::where('employee_tbl_id', $id)->first();

    if (!$requirement) {
        Log::error('Requirement not found', ['id' => $id]);
        return response()->json(['error' => 'Requirement not found'], 404);
    }
    $data = [
        'dt_final_status' => $requirement->dt_final_status,
        'dt_results_date' => $requirement->dt_results_date,
        'dt_transaction_date' => $requirement->dt_transaction_date,
        'dt_endorsed_date' => $requirement->dt_endorsed_date,
        'dt_remarks' => $requirement->dt_remarks,
        'dt_updated_by' => $requirement->dt_updated_by,
        'dt_last_updated_at' => $requirement->dt_last_updated_at,
        'dt_file_name' => $requirement->dt_file_name ? asset('storage/dt_files/' . $requirement->dt_file_name) : null,
    ];

    Log::info('DT Data Retrieved Successfully', ['data' => $data]);

    return response()->json(['data' => $data]);
}

    public function exportTest(Request $request ,$site = null)
    {
        $site = is_numeric($site) ? (int) $site: null;
        $employeeQuery  = Employee::with(
            'userAddedBy',
            'userUpdatedBy',
            'requirements',
            'requirements.nbiUpdatedBy',
            'requirements.tinUpdatedBy',
            'requirements.dtUpdatedBy',
            'requirements.pemeUpdatedBy',
            'requirements.sssUpdatedBy',
            'requirements.phicUpdatedBy',
            'requirements.pagibigUpdatedBy',
            'requirements.healthCertificateUpdatedBy',
            'requirements.occupationalPermitUpdatedBy',
            'requirements.ofacUpdatedBy',
            'requirements.samUpdatedBy',
            'requirements.oigUpdatedBy',
            'requirements.cibiUpdatedBy',
            'requirements.bgcUpdatedBy',
            'requirements.birthCertificateUpdatedBy',
            'requirements.dependentBirthCertificateUpdatedBy',
            'requirements.marriageCertificateUpdatedBy',
            'requirements.scholasticRecordUpdatedBy',
            'requirements.previousEmploymentUpdatedBy',
            'requirements.supportingDocumentsUpdatedBy',
            'lob',
            'lob.siteName'
        );
        if ($request->filled('employee_status')) {
            $employeeQuery->where('employee_status', $request->employee_status);
        }

        if ($request->filled('employment_status')) {
            $employeeQuery->where('employment_status', $request->employment_status);
        }

        if ($request->filled('hired_date_from') && $request->filled('hired_date_to')) {
            $employeeQuery->whereBetween('hired_date', [
                $request->hired_date_from,
                $request->hired_date_to
            ]);
        }
        if ($site) {
            $employeeQuery->whereHas('lob.siteName', function ($query) use ($site) {
                $query->where('id', $site);
            });
        }


        // Get the filtered data
        $employee_info = $employeeQuery->get();
        $mappedEmployees = $employee_info->map(function ($employee) {

            return [
                'employee_id' => $employee->employee_id ?? 'TBA',
                'employee_last_name' => $employee->last_name ?? 'N/A',
                'employee_first_name' => $employee->first_name ?? 'N/A',
                'employee_middle_name' => $employee->middle_name ?? 'N/A',
                'employee_email' => $employee->email ?? 'N/A',
                'employee_contact_number' => $employee->contact_number ?? 'N/A',
                'employee_birth_date' => $employee->birthdate ?? 'N/A',
                'employee_hired_month' => $employee->hired_month ?? 'N/A',
                'employee_hired_date' => $employee->hired_date ?? 'N/A',
                'employee_position' => $employee->account_associate ?? 'N/A',
                'employee_employee_status' => $employee->employee_status ?? 'N/A',
                'employee_employment_status' => $employee->employment_status ?? 'N/A',
                'employee_added_by' => optional($employee->userAddedBy)->name ?? 'N/A',
                'employee_created_at' => $employee->created_at
                    ? $employee->created_at->format('Y-m-d')
                    : 'N/A',
                'employee_updated_by' => $employee->updated_by ?? 'N/A',
                'employee_updated_at' => $employee->updated_at
                    ? $employee->created_at->format('Y-m-d')
                    : 'N/A',
                'region' => optional($employee->lob->first())->region ?? 'N/A',
                'site' => optional(optional($employee->lob->first())->siteName)->name ?? 'N/A',
                'lob' => optional($employee->lob->first())->lob ?? 'N/A',
                'team_name' => optional($employee->lob->first())->team_name ?? 'N/A',
                'project_code' => optional($employee->lob->first())->project_code ?? 'N/A',
                'updated_at' => $employee->created_at
                    ? $employee->updated_at->format('Y-m-d')
                    : 'N/A',
                'nbi_final_status' => optional($employee->requirements->first())->nbi_final_status ?? 'N/A',
                'nbi_validity_date' => optional($employee->requirements->first())->nbi_validity_date ?? 'N/A',
                'nbi_submitted_date' => optional($employee->requirements->first())->nbi_submitted_date ?? 'N/A',
                'nbi_printed_date' => optional($employee->requirements->first())->nbi_printed_date ?? 'N/A',
                'nbi_remarks' => optional($employee->requirements->first())->nbi_remarks ?? 'N/A',
                'nbi' => optional($employee->requirements->first())->nbi_file_name ? 'Yes' : 'No',
                'nbi_last_updated_at' => optional($employee->requirements->first())->nbi_last_updated_at ?? 'N/A',
                'nbi_updated_by' => optional(optional($employee->requirements->first())->nbiUpdatedBy)->name ?? 'N/A',
                'dt_final_status' => optional($employee->requirements->first())->dt_final_status ?? 'N/A',
                'dt_results_date' => optional($employee->requirements->first())->dt_results_date ?? 'N/A',
                'dt_transaction_date' => optional($employee->requirements->first())->dt_transaction_date ?? 'N/A',
                'dt_endorsed_date' => optional($employee->requirements->first())->dt_endorsed_date ?? 'N/A',
                'dt_remarks' => optional($employee->requirements->first())->dt_remarks ?? 'N/A',
                'dt' => optional($employee->requirements->first())->dt_file_name ? 'Yes' : 'No',
                'dt_last_updated_at' => optional($employee->requirements->first())->dt_last_updated_at ?? 'N/A',
                'dt_updated_by' => optional(optional($employee->requirements->first())->dtUpdatedBy)->name ?? 'N/A',
                'peme' => optional($employee->requirements->first())->peme_file_name ? 'Yes' : 'No',
                'peme_remarks' => optional($employee->requirements->first())->peme_remarks ?? 'N/A',
                'peme_endorsed_date' => optional($employee->requirements->first())->peme_endorsed_date ?? 'N/A',
                'peme_results_date' => optional($employee->requirements->first())->peme_results_date ?? 'N/A',
                'peme_transaction_date' => optional($employee->requirements->first())->peme_transaction_date ?? 'N/A',
                'peme_final_status' => optional($employee->requirements->first())->peme_final_status ?? 'N/A',
                'peme_last_updated_at' => optional($employee->requirements->first())->peme_last_updated_at ?? 'N/A',
                'peme_updated_by' => optional(optional($employee->requirements->first())->pemeUpdatedBy)->name ?? 'N/A',
                'sss_proof_submitted_type' => optional($employee->requirements->first())->sss_proof_submitted_type ?? 'N/A',
                'sss_final_status' => optional($employee->requirements->first())->sss_final_status ?? 'N/A',
                'sss_submitted_date' => optional($employee->requirements->first())->sss_submitted_date ?? 'N/A',
                'sss_remarks' => optional($employee->requirements->first())->sss_remarks ?? 'N/A',
                'sss_number' => optional($employee->requirements->first())->sss_number ?? 'N/A',
                'sss' => optional($employee->requirements->first())->sss_file_name ? 'Yes' : 'No',
                'sss_last_updated_at' => optional($employee->requirements->first())->sss_last_updated_at ?? 'N/A',
                'sss_updated_by' => optional(optional($employee->requirements->first())->sssUpdatedBy)->name ?? 'N/A',
                'phic_submitted_date' => optional($employee->requirements->first())->phic_submitted_date ?? 'N/A',
                'phic_final_status' => optional($employee->requirements->first())->phic_final_status ?? 'N/A',
                'phic_proof_submitted_type' => optional($employee->requirements->first())->phic_proof_submitted_type ?? 'N/A',
                'phic_remarks' => optional($employee->requirements->first())->phic_remarks ?? 'N/A',
                'phic_number' => optional($employee->requirements->first())->phic_number ?? 'N/A',
                'phic' => optional($employee->requirements->first())->phic_file_name ? 'Yes' : 'No',
                'phic_last_updated_at' => optional($employee->requirements->first())->phic_last_updated_at ?? 'N/A',
                'phic_updated_by' => optional(optional($employee->requirements->first())->phicUpdatedBy)->name ?? 'N/A',
                'pagibig_submitted_date' => optional($employee->requirements->first())->pagibig_submitted_date ?? 'N/A',
                'pagibig_final_status' => optional($employee->requirements->first())->pagibig_final_status ?? 'N/A',
                'pagibig_proof_submitted_type' => optional($employee->requirements->first())->pagibig_proof_submitted_type ?? 'N/A',
                'pagibig_remarks' => optional($employee->requirements->first())->pagibig_remarks ?? 'N/A',
                'pagibig_number' => optional($employee->requirements->first())->pagibig_number ?? 'N/A',
                'pagibig' => optional($employee->requirements->first())->pagibig_file_name ? 'Yes' : 'No',
                'pagibig_last_updated_at' => optional($employee->requirements->first())->pagibig_last_updated_at ?? 'N/A',
                'pagibig_updated_by' => optional(optional($employee->requirements->first())->pagibigUpdatedBy)->name ?? 'N/A',
                'tin_submitted_date' => optional($employee->requirements->first())->tin_submitted_date ?? 'N/A',
                'tin_final_status' => optional($employee->requirements->first())->tin_final_status ?? 'N/A',
                'tin_proof_submitted_type' => optional($employee->requirements->first())->tin_proof_submitted_type ?? 'N/A',
                'tin_remarks' => optional($employee->requirements->first())->tin_remarks ?? 'N/A',
                'tin_number' => optional($employee->requirements->first())->tin_number ?? 'N/A',
                'tin' => optional($employee->requirements->first())->tin_file_name ? 'Yes' : 'No',
                'tin_last_updated_at' => optional($employee->requirements->first())->tin_last_updated_at ?? 'N/A',
                'tin_updated_by' => optional(optional($employee->requirements->first())->tinUpdatedBy)->name ?? 'N/A',
                'health_certificate_validity_date' => optional($employee->requirements->first())->health_certificate_validity_date ?? 'N/A',
                'health_certificate_submitted_date' => optional($employee->requirements->first())->health_certificate_submitted_date ?? 'N/A',
                'health_certificate_remarks' => optional($employee->requirements->first())->health_certificate_remarks ?? 'N/A',
                'health_certificate' => optional($employee->requirements->first())->health_certificate_file_name ? 'Yes' : 'No',
                'health_certificate_final_status' => optional($employee->requirements->first())->health_certificate_final_status ?? 'N/A',
                'health_certificate_last_updated_at' => optional($employee->requirements->first())->health_certificate_last_updated_at ?? 'N/A',
                'health_certificate_updated_by' => optional(optional($employee->requirements->first())->healthCertificateUpdatedBy)->name ?? 'N/A',
                'occupational_permit_validity_date' => optional($employee->requirements->first())->occupational_permit_validity_date ?? 'N/A',
                'occupational_permit_submitted_date' => optional($employee->requirements->first())->occupational_permit_submitted_date ?? 'N/A',
                'occupational_permit_remarks' => optional($employee->requirements->first())->occupational_permit_remarks ?? 'N/A',
                'occupational_permit' => optional($employee->requirements->first())->occupational_permit_file_name ? 'Yes' : 'No',
                'occupational_permit_final_status' => optional($employee->requirements->first())->occupational_permit_final_status ?? 'N/A',
                'occupational_permit_last_updated_at' => optional($employee->requirements->first())->occupational_permit_last_updated_at ?? 'N/A',
                'occupational_permit_updated_by' => optional(optional($employee->requirements->first())->occupationalPermitUpdatedBy)->name ?? 'N/A',
                'ofac_checked_date' => optional($employee->requirements->first())->ofac_checked_date ?? 'N/A',
                'ofac_final_status' => optional($employee->requirements->first())->ofac_final_status ?? 'N/A',
                'ofac_remarks' => optional($employee->requirements->first())->ofac_remarks ?? 'N/A',
                'ofac' => optional($employee->requirements->first())->ofac_file_name ? 'Yes' : 'No',
                'ofac_last_updated_at' => optional($employee->requirements->first())->ofac_last_updated_at ?? 'N/A',
                'ofac_updated_by' => optional(optional($employee->requirements->first())->ofacUpdatedBy)->name ?? 'N/A',
                'sam_checked_date' => optional($employee->requirements->first())->sam_checked_date ?? 'N/A',
                'sam_final_status' => optional($employee->requirements->first())->sam_final_status ?? 'N/A',
                'sam_remarks' => optional($employee->requirements->first())->sam_remarks ?? 'N/A',
                'sam' => optional($employee->requirements->first())->sam_file_name ? 'Yes' : 'No',
                'sam_last_updated_at' => optional($employee->requirements->first())->sam_last_updated_at ?? 'N/A',
                'sam_updated_by' => optional(optional($employee->requirements->first())->samUpdatedBy)->name ?? 'N/A',
                'oig_checked_date' => optional($employee->requirements->first())->oig_checked_date ?? 'N/A',
                'oig_final_status' => optional($employee->requirements->first())->oig_final_status ?? 'N/A',
                'oig_remarks' => optional($employee->requirements->first())->oig_remarks ?? 'N/A',
                'oig' => optional($employee->requirements->first())->oig_file_name ? 'Yes' : 'No',
                'oig_last_updated_at' => optional($employee->requirements->first())->oig_last_updated_at ?? 'N/A',
                'oig_updated_by' => optional(optional($employee->requirements->first())->oigUpdatedBy)->name ?? 'N/A',
                'cibi_checked_date' => optional($employee->requirements->first())->cibi_checked_date ?? 'N/A',
                'cibi_final_status' => optional($employee->requirements->first())->cibi_final_status ?? 'N/A',
                'cibi_remarks' => optional($employee->requirements->first())->cibi_remarks ?? 'N/A',
                'cibi' => optional($employee->requirements->first())->cibi_file_name ? 'Yes' : 'No',
                'cibi_last_updated_at' => optional($employee->requirements->first())->cibi_last_updated_at ?? 'N/A',
                'cibi_updated_by' => optional(optional($employee->requirements->first())->cibiUpdatedBy)->name ?? 'N/A',
                'bgc_endorsed_date' => optional($employee->requirements->first())->bgc_endorsed_date ?? 'N/A',
                'bgc_results_date' => optional($employee->requirements->first())->bgc_results_date ?? 'N/A',
                'bgc_final_status' => optional($employee->requirements->first())->bgc_final_status ?? 'N/A',
                'bgc_remarks' => optional($employee->requirements->first())->bgc_remarks ?? 'N/A',
                'bgc' => optional($employee->requirements->first())->bgc_file_name ? 'Yes' : 'No',
                'bgc_last_updated_at' => optional($employee->requirements->first())->bgc_last_updated_at ?? 'N/A',
                'bgc_updated_by' => optional(optional($employee->requirements->first())->bgcUpdatedBy)->name ?? 'N/A',
                'bc' => optional($employee->requirements->first())->bc_file_name ? 'Yes' : 'No',
                'bc_submitted_date' => optional($employee->requirements->first())->bc_submitted_date ?? 'N/A',
                'bc_proof_type' => optional($employee->requirements->first())->bc_proof_type ?? 'N/A',
                'bc_remarks' => optional($employee->requirements->first())->bc_remarks ?? 'N/A',
                'bc_last_updated_at' => optional($employee->requirements->first())->bc_last_updated_at ?? 'N/A',
                'bc_updated_by' => optional(optional($employee->requirements->first())->birthCertificateUpdatedBy)->name ?? 'N/A',
                'dbc' => optional($employee->requirements->first())->dbc_file_name ? 'Yes' : 'No',
                'dbc_submitted_date' => optional($employee->requirements->first())->dbc_submitted_date ?? 'N/A',
                'dbc_proof_type' => optional($employee->requirements->first())->dbc_proof_type ?? 'N/A',
                'dbc_remarks' => optional($employee->requirements->first())->dbc_remarks ?? 'N/A',
                'dbc_last_updated_at' => optional($employee->requirements->first())->dbc_last_updated_at ?? 'N/A',
                'dbc_updated_by' => optional(optional($employee->requirements->first())->dependentBirthCertificateUpdatedBy)->name ?? 'N/A',
                'mc' => optional($employee->requirements->first())->mc_file_name ? 'Yes' : 'No',
                'mc_submitted_date' => optional($employee->requirements->first())->mc_submitted_date ?? 'N/A',
                'mc_proof_type' => optional($employee->requirements->first())->mc_proof_type ?? 'N/A',
                'mc_remarks' => optional($employee->requirements->first())->mc_remarks ?? 'N/A',
                'mc_last_updated_at' => optional($employee->requirements->first())->mc_last_updated_at ?? 'N/A',
                'mc_updated_by' => optional(optional($employee->requirements->first())->marriageCertificateUpdatedBy)->name ?? 'N/A',
                'sr' => optional($employee->requirements->first())->sr_file_name ? 'Yes' : 'No',
                'sr_submitted_date' => optional($employee->requirements->first())->sr_submitted_date ?? 'N/A',
                'sr_proof_type' => optional($employee->requirements->first())->sr_proof_type ?? 'N/A',
                'sr_remarks' => optional($employee->requirements->first())->sr_remarks ?? 'N/A',
                'sr_last_updated_at' => optional($employee->requirements->first())->sr_last_updated_at ?? 'N/A',
                'sr_updated_by' => optional(optional($employee->requirements->first())->scholasticRecordUpdatedBy)->name ?? 'N/A',
                'pe' => optional($employee->requirements->first())->pe_file_name ? 'Yes' : 'No',
                'pe_submitted_date' => optional($employee->requirements->first())->pe_submitted_date ?? 'N/A',
                'pe_proof_type' => optional($employee->requirements->first())->pe_proof_type ?? 'N/A',
                'pe_remarks' => optional($employee->requirements->first())->pe_remarks ?? 'N/A',
                'pe_last_updated_at' => optional($employee->requirements->first())->pe_last_updated_at ?? 'N/A',
                'pe_updated_by' => optional(optional($employee->requirements->first())->previousEmploymentUpdatedBy)->name ?? 'N/A',
                'sd' => optional($employee->requirements->first())->sd_file_name ? 'Yes' : 'No',
                'sd_submitted_date' => optional($employee->requirements->first())->sd_submitted_date ?? 'N/A',
                'sd_proof_type' => optional($employee->requirements->first())->sd_proof_type ?? 'N/A',
                'sd_remarks' => optional($employee->requirements->first())->sd_remarks ?? 'N/A',
                'sd_last_updated_at' => optional($employee->requirements->first())->sd_last_updated_at ?? 'N/A',
                'sd_updated_by' => optional(optional($employee->requirements->first())->supportingDocumentsUpdatedBy)->name ?? 'N/A',

            ];
        });


        return Excel::download(new EmployeeExport($mappedEmployees->toArray()), 'employee_report.xlsx');
        /*  return response()->json([
            'message' => $mappedEmployees,
        ], 201); */
    }

    /* public function export(Request $request)
    {
        // Retrieve filter parameters from the request (status, lob, and date)
        $statusFilter = $request->input('status');
        $lobFilter = $request->input('lob');
        $dateFilter = $request->input('date');

        // Query employees based on the filters
        $employeesQuery = Employee::with(['lob']);

        // Apply the filters
        if ($statusFilter) {
            $employeesQuery->where('employee_status', $statusFilter);
        }
        if ($lobFilter) {
            $employeesQuery->whereHas('lob', function ($query) use ($lobFilter) {
                $query->where('lob', $lobFilter);
            });
        }
        if ($dateFilter) {
            $employeesQuery->whereDate('hired_date', '=', $dateFilter);
        }

        // Retrieve the filtered employees data
        $employees = $employeesQuery->get();

        // Map the data to select only the necessary fields
        $mappedEmployees = $employees->map(function ($employee) {
            // Map the data for each employee
            return [
                'employee_id' => $employee->id,
                'employee_name' => $employee->first_name . ' ' . $employee->last_name, // Assuming first_name and last_name fields exist
                'site' => $employee->lob->site ?? '', // Null coalescing in case lob relationship is not loaded
                'region' => $employee->lob->region ?? '', // Same for region
            ];
        });

        // Convert the collection to an array before passing to the export class
        $mappedEmployeesArray = $mappedEmployees->toArray();

        // Return the mapped data as JSON for API testing
        return response()->json($mappedEmployeesArray);

        // If you want to export to Excel, use this instead:
        // return Excel::download(new EmployeesExport($mappedEmployeesArray), 'employee_report.xlsx');
    } */

    public function generate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:employees,id',  // Using 'id' here
        ]);

        $employee = Employee::findOrFail($request->id);

        $qrData = [
            'id' => $employee->id,
            'email' => $employee->email,
            'contact' => $employee->contact_number,
        ];

        $qrString = json_encode($qrData);
        $fileName = "qr_code_paths/employee_{$employee->id}.png";
        $path = Storage::disk('public')->put($fileName, QrCode::format('png')->size(300)->generate($qrString));

        $employee->qr_code_path_path = $fileName;
        $employee->save();

        return response()->json([
            'message' => 'QR Code generated successfully!',
            'file_path' => Storage::url($fileName),
        ], 201);
    }
    public function saveQRCode(Request $request, $employeeId)
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|image|mimes:png,jpg,jpeg,gif|max:2048', // Limit size to 2MB
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid QR code file.',
                'errors' => $validator->errors(),
            ], 400);
        }
    
        $employee = Employee::find($employeeId);
    
        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found.',
            ], 404);
        }
    
        try {
            // Store the uploaded QR code image in the 'public/qr_codes' directory
            $path = $request->file('qr_code')->store('qr_codes', 'public');
    
            // Save the file path in the `qr_code_path` column
            $employee->qr_code_path = $path;
            $employee->save();
    
            return response()->json([
                'status' => 'success',
                'message' => 'QR code saved successfully.',
                'qr_code_path' => Storage::url($path), // Return the URL to access the file
            ], 200);
        } catch (\Exception $e) {
            \Log::error('QR Code save error: ' . $e->getMessage());
    
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while saving the QR code.',
            ], 500);
        }
    }
    
    
    public function storeEmployees(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable',
            'last_name' => 'nullable',
            'first_name' => 'nullable',
            'middle_name' => 'nullable',
            'employee_status' => 'nullable',
            'hired_date' => 'nullable',
            'hired_month' => 'nullable',
            'birthdate' => 'nullable',
            'contact_number' => 'nullable',
            'email' => 'nullable|email|unique:employees,email',
            'account_associate' => 'nullable',
            'account_type' => 'nullable',
            'employment_status' => 'nullable',
            'employee_added_by' => 'nullable|integer',
            'lob_id' => 'nullable|integer',
            'site_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create the employee
        $employee_info = new Employee();
        $employee_info->fill($request->all());
        $employee_info->save();

        // Get the employee ID
        $employee_info_id = $employee_info->id;

        // Create the requirement and associate with the employee
        $requirement = new Requirements();
        $requirement->employee_tbl_id = $employee_info_id;  // Assign employee ID to the requirement
        $requirement->save();

        // Create the lob and associate with the employee
        $lob = new Lob();
        $lob->employee_tbl_id = $employee_info_id;
        $lob->site = $request->site_id;
        $lob->save();

        return response()->json([
            'employee' => $employee_info,
            'requirement' => $requirement,
            'lob' => $lob,
        ]);
    }
    public function index(Request $request)
    {
        // Initialize the query with relationships
        $employeeQuery = Employee::with('userAddedBy', 'userUpdatedBy');

        // Apply filters if provided in the request
        if ($request->filled('employee_status')) {
            $employeeQuery->where('employee_status', $request->employee_status);
        }

        if ($request->filled('employment_status')) {
            $employeeQuery->where('employment_status', $request->employment_status);
        }

        if ($request->filled('hired_date_from') && $request->filled('hired_date_to')) {
            $employeeQuery->whereBetween('hired_date', [
                $request->hired_date_from,
                $request->hired_date_to
            ]);
        }

        // Fetch employee information with pagination
        $employee_info = $employeeQuery->paginate(10);

        // Add the QR code URL to each employee
        $employees = $employee_info->items();

        foreach ($employees as $employee) {
            // Assuming you have a column `qr_code_path` in your `employees` table
            if ($employee->qr_code_path) {
                // Generate the URL to the QR code image stored in the public directory
                // Make sure to adjust the path if the storage is in another directory
                $employee->qr_code_url = asset('storage/' . $employee->qr_code_path);
            } else {
                // If no QR code exists, set to null or default
                $employee->qr_code_url = null;
            }
        }

        // Return the response with employee data and pagination
        return response()->json([
            'employees' => $employees,
            'pagination' => [
                'total' => $employee_info->total(),
                'current_page' => $employee_info->currentPage(),
                'first_page' => 1, // First page is always 1
                'last_page' => $employee_info->lastPage(),
                'next_page' => $employee_info->currentPage() < $employee_info->lastPage()
                    ? $employee_info->currentPage() + 1
                    : null,
                'prev_page' => $employee_info->currentPage() > 1
                    ? $employee_info->currentPage() - 1
                    : null,
                'per_page' => $employee_info->perPage(),
                'total_pages' => $employee_info->lastPage(),
            ],
        ]);
    }


    public function indexEmployees(Request $request ,$site = null)
    {
        $site = is_numeric($site) ? (int) $site: null;
        $employeeQuery = Employee::with(
            'userAddedBy',
            'userUpdatedBy',
            'requirements',
            'requirements.nbiUpdatedBy',
            'requirements.tinUpdatedBy',
            'requirements.dtUpdatedBy',
            'requirements.pemeUpdatedBy',
            'requirements.sssUpdatedBy',
            'requirements.phicUpdatedBy',
            'requirements.pagibigUpdatedBy',
            'requirements.healthCertificateUpdatedBy',
            'requirements.occupationalPermitUpdatedBy',
            'requirements.ofacUpdatedBy',
            'requirements.samUpdatedBy',
            'requirements.oigUpdatedBy',
            'requirements.cibiUpdatedBy',
            'requirements.bgcUpdatedBy',
            'requirements.birthCertificateUpdatedBy',
            'requirements.dependentBirthCertificateUpdatedBy',
            'requirements.marriageCertificateUpdatedBy',
            'requirements.scholasticRecordUpdatedBy',
            'requirements.previousEmploymentUpdatedBy',
            'requirements.supportingDocumentsUpdatedBy',
            'lob',
            'lob.siteName'
        );

        // Apply filters based on the request
        if ($request->filled('employee_status')) {
            $employeeQuery->where('employee_status', $request->employee_status);
        }

        if ($request->filled('employment_status')) {
            $employeeQuery->where('employment_status', $request->employment_status);
        }

        if ($request->filled('hired_date_from') && $request->filled('hired_date_to')) {
            $employeeQuery->whereBetween('hired_date', [
                $request->hired_date_from,
                $request->hired_date_to
            ]);
        }
        if ($site) {
            $employeeQuery->whereHas('lob.siteName', function ($query) use ($site) {
                $query->where('id', $site);
            });
        }
        $employee_info = $employeeQuery->paginate(10);
        $mappedEmployees = collect($employee_info->items())->map(function ($employee) {

            return [
                'id' => $employee->id ?? 'TBA',
                'employee_id' => $employee->employee_id ?? 'TBA',
                'employee_qr_code_url' => $employee->qr_code_path ? asset('storage/' . $employee->qr_code_path) : null,
                'employee_last_name' => $employee->last_name ?? 'N/A',
                'employee_first_name' => $employee->first_name ?? 'N/A',
                'employee_middle_name' => $employee->middle_name ?? 'N/A',
                'employee_email' => $employee->email ?? 'N/A',
                'employee_contact_number' => $employee->contact_number ?? 'N/A',
                'employee_birth_date' => $employee->birthdate ?? 'N/A',
                'employee_hired_month' => $employee->hired_month ?? 'N/A',
                'employee_hired_date' => $employee->hired_date ?? 'N/A',
                'employee_position' => $employee->account_associate ?? 'N/A',
                'employee_employee_status' => $employee->employee_status ?? 'N/A',
                'employee_employment_status' => $employee->employment_status ?? 'N/A',
                'employee_added_by' => optional($employee->userAddedBy)->name ?? 'N/A',
                'employee_created_at' => $employee->created_at
                    ? $employee->created_at->format('Y-m-d')
                    : 'N/A',
                'employee_updated_by' => $employee->updated_by ?? 'N/A',
                'employee_updated_at' => $employee->updated_at
                    ? $employee->created_at->format('Y-m-d')
                    : 'N/A',
                'region' => optional($employee->lob->first())->region ?? 'N/A',
                'site' => optional(optional($employee->lob->first())->siteName)->name ?? 'N/A',
                'lob' => optional($employee->lob->first())->lob ?? 'N/A',
                'team_name' => optional($employee->lob->first())->team_name ?? 'N/A',
                'project_code' => optional($employee->lob->first())->project_code ?? 'N/A',
                'updated_at' => $employee->created_at
                    ? $employee->updated_at->format('Y-m-d')
                    : 'N/A',
                'nbi_final_status' => optional($employee->requirements->first())->nbi_final_status ?? 'N/A',
                'nbi_validity_date' => optional($employee->requirements->first())->nbi_validity_date ?? 'N/A',
                'nbi_submitted_date' => optional($employee->requirements->first())->nbi_submitted_date ?? 'N/A',
                'nbi_printed_date' => optional($employee->requirements->first())->nbi_printed_date ?? 'N/A',
                'nbi_remarks' => optional($employee->requirements->first())->nbi_remarks ?? 'N/A',
                'nbi' => optional($employee->requirements->first())->nbi_file_name ? 'Yes' : 'No',
                'nbi_last_updated_at' => optional($employee->requirements->first())->nbi_last_updated_at ?? 'N/A',
                'nbi_updated_by' => optional(optional($employee->requirements->first())->nbiUpdatedBy)->name ?? 'N/A',
                'dt_final_status' => optional($employee->requirements->first())->dt_final_status ?? 'N/A',
                'dt_results_date' => optional($employee->requirements->first())->dt_results_date ?? 'N/A',
                'dt_transaction_date' => optional($employee->requirements->first())->dt_transaction_date ?? 'N/A',
                'dt_endorsed_date' => optional($employee->requirements->first())->dt_endorsed_date ?? 'N/A',
                'dt_remarks' => optional($employee->requirements->first())->dt_remarks ?? 'N/A',
                'dt' => optional($employee->requirements->first())->dt_file_name ? 'Yes' : 'No',
                'dt_last_updated_at' => optional($employee->requirements->first())->dt_last_updated_at ?? 'N/A',
                'dt_updated_by' => optional(optional($employee->requirements->first())->dtUpdatedBy)->name ?? 'N/A',
                'peme' => optional($employee->requirements->first())->peme_file_name ? 'Yes' : 'No',
                'peme_remarks' => optional($employee->requirements->first())->peme_remarks ?? 'N/A',
                'peme_endorsed_date' => optional($employee->requirements->first())->peme_endorsed_date ?? 'N/A',
                'peme_results_date' => optional($employee->requirements->first())->peme_results_date ?? 'N/A',
                'peme_transaction_date' => optional($employee->requirements->first())->peme_transaction_date ?? 'N/A',
                'peme_final_status' => optional($employee->requirements->first())->peme_final_status ?? 'N/A',
                'peme_last_updated_at' => optional($employee->requirements->first())->peme_last_updated_at ?? 'N/A',
                'peme_updated_by' => optional(optional($employee->requirements->first())->pemeUpdatedBy)->name ?? 'N/A',
                'sss_proof_submitted_type' => optional($employee->requirements->first())->sss_proof_submitted_type ?? 'N/A',
                'sss_final_status' => optional($employee->requirements->first())->sss_final_status ?? 'N/A',
                'sss_submitted_date' => optional($employee->requirements->first())->sss_submitted_date ?? 'N/A',
                'sss_remarks' => optional($employee->requirements->first())->sss_remarks ?? 'N/A',
                'sss_number' => optional($employee->requirements->first())->sss_number ?? 'N/A',
                'sss' => optional($employee->requirements->first())->sss_file_name ? 'Yes' : 'No',
                'sss_last_updated_at' => optional($employee->requirements->first())->sss_last_updated_at ?? 'N/A',
                'sss_updated_by' => optional(optional($employee->requirements->first())->sssUpdatedBy)->name ?? 'N/A',
                'phic_submitted_date' => optional($employee->requirements->first())->phic_submitted_date ?? 'N/A',
                'phic_final_status' => optional($employee->requirements->first())->phic_final_status ?? 'N/A',
                'phic_proof_submitted_type' => optional($employee->requirements->first())->phic_proof_submitted_type ?? 'N/A',
                'phic_remarks' => optional($employee->requirements->first())->phic_remarks ?? 'N/A',
                'phic_number' => optional($employee->requirements->first())->phic_number ?? 'N/A',
                'phic' => optional($employee->requirements->first())->phic_file_name ? 'Yes' : 'No',
                'phic_last_updated_at' => optional($employee->requirements->first())->phic_last_updated_at ?? 'N/A',
                'phic_updated_by' => optional(optional($employee->requirements->first())->phicUpdatedBy)->name ?? 'N/A',
                'pagibig_submitted_date' => optional($employee->requirements->first())->pagibig_submitted_date ?? 'N/A',
                'pagibig_final_status' => optional($employee->requirements->first())->pagibig_final_status ?? 'N/A',
                'pagibig_proof_submitted_type' => optional($employee->requirements->first())->pagibig_proof_submitted_type ?? 'N/A',
                'pagibig_remarks' => optional($employee->requirements->first())->pagibig_remarks ?? 'N/A',
                'pagibig_number' => optional($employee->requirements->first())->pagibig_number ?? 'N/A',
                'pagibig' => optional($employee->requirements->first())->pagibig_file_name ? 'Yes' : 'No',
                'pagibig_last_updated_at' => optional($employee->requirements->first())->pagibig_last_updated_at ?? 'N/A',
                'pagibig_updated_by' => optional(optional($employee->requirements->first())->pagibigUpdatedBy)->name ?? 'N/A',
                'tin_submitted_date' => optional($employee->requirements->first())->tin_submitted_date ?? 'N/A',
                'tin_final_status' => optional($employee->requirements->first())->tin_final_status ?? 'N/A',
                'tin_proof_submitted_type' => optional($employee->requirements->first())->tin_proof_submitted_type ?? 'N/A',
                'tin_remarks' => optional($employee->requirements->first())->tin_remarks ?? 'N/A',
                'tin_number' => optional($employee->requirements->first())->tin_number ?? 'N/A',
                'tin' => optional($employee->requirements->first())->tin_file_name ? 'Yes' : 'No',
                'tin_last_updated_at' => optional($employee->requirements->first())->tin_last_updated_at ?? 'N/A',
                'tin_updated_by' => optional(optional($employee->requirements->first())->tinUpdatedBy)->name ?? 'N/A',
                'health_certificate_validity_date' => optional($employee->requirements->first())->health_certificate_validity_date ?? 'N/A',
                'health_certificate_submitted_date' => optional($employee->requirements->first())->health_certificate_submitted_date ?? 'N/A',
                'health_certificate_remarks' => optional($employee->requirements->first())->health_certificate_remarks ?? 'N/A',
                'health_certificate' => optional($employee->requirements->first())->health_certificate_file_name ? 'Yes' : 'No',
                'health_certificate_final_status' => optional($employee->requirements->first())->health_certificate_final_status ?? 'N/A',
                'health_certificate_last_updated_at' => optional($employee->requirements->first())->health_certificate_last_updated_at ?? 'N/A',
                'health_certificate_updated_by' => optional(optional($employee->requirements->first())->healthCertificateUpdatedBy)->name ?? 'N/A',
                'occupational_permit_validity_date' => optional($employee->requirements->first())->occupational_permit_validity_date ?? 'N/A',
                'occupational_permit_submitted_date' => optional($employee->requirements->first())->occupational_permit_submitted_date ?? 'N/A',
                'occupational_permit_remarks' => optional($employee->requirements->first())->occupational_permit_remarks ?? 'N/A',
                'occupational_permit' => optional($employee->requirements->first())->occupational_permit_file_name ? 'Yes' : 'No',
                'occupational_permit_final_status' => optional($employee->requirements->first())->occupational_permit_final_status ?? 'N/A',
                'occupational_permit_last_updated_at' => optional($employee->requirements->first())->occupational_permit_last_updated_at ?? 'N/A',
                'occupational_permit_updated_by' => optional(optional($employee->requirements->first())->occupationalPermitUpdatedBy)->name ?? 'N/A',
                'ofac_checked_date' => optional($employee->requirements->first())->ofac_checked_date ?? 'N/A',
                'ofac_final_status' => optional($employee->requirements->first())->ofac_final_status ?? 'N/A',
                'ofac_remarks' => optional($employee->requirements->first())->ofac_remarks ?? 'N/A',
                'ofac' => optional($employee->requirements->first())->ofac_file_name ? 'Yes' : 'No',
                'ofac_last_updated_at' => optional($employee->requirements->first())->ofac_last_updated_at ?? 'N/A',
                'ofac_updated_by' => optional(optional($employee->requirements->first())->ofacUpdatedBy)->name ?? 'N/A',
                'sam_checked_date' => optional($employee->requirements->first())->sam_checked_date ?? 'N/A',
                'sam_final_status' => optional($employee->requirements->first())->sam_final_status ?? 'N/A',
                'sam_remarks' => optional($employee->requirements->first())->sam_remarks ?? 'N/A',
                'sam' => optional($employee->requirements->first())->sam_file_name ? 'Yes' : 'No',
                'sam_last_updated_at' => optional($employee->requirements->first())->sam_last_updated_at ?? 'N/A',
                'sam_updated_by' => optional(optional($employee->requirements->first())->samUpdatedBy)->name ?? 'N/A',
                'oig_checked_date' => optional($employee->requirements->first())->oig_checked_date ?? 'N/A',
                'oig_final_status' => optional($employee->requirements->first())->oig_final_status ?? 'N/A',
                'oig_remarks' => optional($employee->requirements->first())->oig_remarks ?? 'N/A',
                'oig' => optional($employee->requirements->first())->oig_file_name ? 'Yes' : 'No',
                'oig_last_updated_at' => optional($employee->requirements->first())->oig_last_updated_at ?? 'N/A',
                'oig_updated_by' => optional(optional($employee->requirements->first())->oigUpdatedBy)->name ?? 'N/A',
                'cibi_checked_date' => optional($employee->requirements->first())->cibi_checked_date ?? 'N/A',
                'cibi_final_status' => optional($employee->requirements->first())->cibi_final_status ?? 'N/A',
                'cibi_remarks' => optional($employee->requirements->first())->cibi_remarks ?? 'N/A',
                'cibi' => optional($employee->requirements->first())->cibi_file_name ? 'Yes' : 'No',
                'cibi_last_updated_at' => optional($employee->requirements->first())->cibi_last_updated_at ?? 'N/A',
                'cibi_updated_by' => optional(optional($employee->requirements->first())->cibiUpdatedBy)->name ?? 'N/A',
                'bgc_endorsed_date' => optional($employee->requirements->first())->bgc_endorsed_date ?? 'N/A',
                'bgc_results_date' => optional($employee->requirements->first())->bgc_results_date ?? 'N/A',
                'bgc_final_status' => optional($employee->requirements->first())->bgc_final_status ?? 'N/A',
                'bgc_remarks' => optional($employee->requirements->first())->bgc_remarks ?? 'N/A',
                'bgc' => optional($employee->requirements->first())->bgc_file_name ? 'Yes' : 'No',
                'bgc_last_updated_at' => optional($employee->requirements->first())->bgc_last_updated_at ?? 'N/A',
                'bgc_updated_by' => optional(optional($employee->requirements->first())->bgcUpdatedBy)->name ?? 'N/A',
                'bc' => optional($employee->requirements->first())->bc_file_name ? 'Yes' : 'No',
                'bc_submitted_date' => optional($employee->requirements->first())->bc_submitted_date ?? 'N/A',
                'bc_proof_type' => optional($employee->requirements->first())->bc_proof_type ?? 'N/A',
                'bc_remarks' => optional($employee->requirements->first())->bc_remarks ?? 'N/A',
                'bc_last_updated_at' => optional($employee->requirements->first())->bc_last_updated_at ?? 'N/A',
                'bc_updated_by' => optional(optional($employee->requirements->first())->birthCertificateUpdatedBy)->name ?? 'N/A',
                'dbc' => optional($employee->requirements->first())->dbc_file_name ? 'Yes' : 'No',
                'dbc_submitted_date' => optional($employee->requirements->first())->dbc_submitted_date ?? 'N/A',
                'dbc_proof_type' => optional($employee->requirements->first())->dbc_proof_type ?? 'N/A',
                'dbc_remarks' => optional($employee->requirements->first())->dbc_remarks ?? 'N/A',
                'dbc_last_updated_at' => optional($employee->requirements->first())->dbc_last_updated_at ?? 'N/A',
                'dbc_updated_by' => optional(optional($employee->requirements->first())->dependentBirthCertificateUpdatedBy)->name ?? 'N/A',
                'mc' => optional($employee->requirements->first())->mc_file_name ? 'Yes' : 'No',
                'mc_submitted_date' => optional($employee->requirements->first())->mc_submitted_date ?? 'N/A',
                'mc_proof_type' => optional($employee->requirements->first())->mc_proof_type ?? 'N/A',
                'mc_remarks' => optional($employee->requirements->first())->mc_remarks ?? 'N/A',
                'mc_last_updated_at' => optional($employee->requirements->first())->mc_last_updated_at ?? 'N/A',
                'mc_updated_by' => optional(optional($employee->requirements->first())->marriageCertificateUpdatedBy)->name ?? 'N/A',
                'sr' => optional($employee->requirements->first())->sr_file_name ? 'Yes' : 'No',
                'sr_submitted_date' => optional($employee->requirements->first())->sr_submitted_date ?? 'N/A',
                'sr_proof_type' => optional($employee->requirements->first())->sr_proof_type ?? 'N/A',
                'sr_remarks' => optional($employee->requirements->first())->sr_remarks ?? 'N/A',
                'sr_last_updated_at' => optional($employee->requirements->first())->sr_last_updated_at ?? 'N/A',
                'sr_updated_by' => optional(optional($employee->requirements->first())->scholasticRecordUpdatedBy)->name ?? 'N/A',
                'pe' => optional($employee->requirements->first())->pe_file_name ? 'Yes' : 'No',
                'pe_submitted_date' => optional($employee->requirements->first())->pe_submitted_date ?? 'N/A',
                'pe_proof_type' => optional($employee->requirements->first())->pe_proof_type ?? 'N/A',
                'pe_remarks' => optional($employee->requirements->first())->pe_remarks ?? 'N/A',
                'pe_last_updated_at' => optional($employee->requirements->first())->pe_last_updated_at ?? 'N/A',
                'pe_updated_by' => optional(optional($employee->requirements->first())->previousEmploymentUpdatedBy)->name ?? 'N/A',
                'sd' => optional($employee->requirements->first())->sd_file_name ? 'Yes' : 'No',
                'sd_submitted_date' => optional($employee->requirements->first())->sd_submitted_date ?? 'N/A',
                'sd_proof_type' => optional($employee->requirements->first())->sd_proof_type ?? 'N/A',
                'sd_remarks' => optional($employee->requirements->first())->sd_remarks ?? 'N/A',
                'sd_last_updated_at' => optional($employee->requirements->first())->sd_last_updated_at ?? 'N/A',
                'sd_updated_by' => optional(optional($employee->requirements->first())->supportingDocumentsUpdatedBy)->name ?? 'N/A',

            ];
        });
        return response()->json([
          
                'employees' => $mappedEmployees,
                'pagination' => [
                    'total' => $employee_info->total(),
                    'current_page' => $employee_info->currentPage(),
                    'first_page' => 1, // First page is always 1
                    'last_page' => $employee_info->lastPage(),
                    'next_page' => $employee_info->currentPage() < $employee_info->lastPage()
                        ? $employee_info->currentPage() + 1
                        : null,
                    'prev_page' => $employee_info->currentPage() > 1
                        ? $employee_info->currentPage() - 1
                        : null,
                    'per_page' => $employee_info->perPage(),
                    'total_pages' => $employee_info->lastPage(),
               
            ],
        ]);
    }


    /**
     * Map the requirements data for each employee
     *
     * @param  \Illuminate\Database\Eloquent\Collection $requirements
     * @return array
     */


    /**
     * Map the LOB data for each employee
     *
     * @param  \Illuminate\Database\Eloquent\Collection $lob
     * @return array
     */
    private function mapLobData($lob)
    {
        return $lob->map(function ($lobEntry) {
            return [
                'region' => $lobEntry->region ?? 'N/A',
                'site' => optional($lobEntry->siteName)->name ?? 'N/A',  // Safely access site name
                'lob' => $lobEntry->lob ?? 'N/A',
                'team_name' => $lobEntry->team_name ?? 'N/A',
                'project_code' => $lobEntry->project_code ?? 'N/A',
            ];
        })->toArray(); // Convert the collection to an array
    }
    private function mapEmployeeData($employee)
    {
        return [
            'employee_id' => $employee->employee_id  ?? 'TBA',
            'full_name' => $employee->last_name . ',' . $employee->first_name . ' ' . $employee->middle_name,
            'status' => $employee->employee_status,
            'hired_date' => $employee->hired_date,
            'birthdate' => $employee->birthdate,
            'contact_number' => $employee->contact_number,
            'email' => $employee->email,
            'account_associate' => $employee->account_associate,
            'employment_status' => $employee->employment_status,
            'updated_by' => $employee->userUpdatedBy ? $employee->userUpdatedBy->name : 'N/A',
            'updated_at' => $employee->updated_at->format('Y-m-d H:i'),
            'employee_qr_code_url' => $employee->qr_code_path ? asset('storage/' . $employee->qr_code_path) : null,
        ];
    }

    private function mapRequirementsData($requirements)
    {
        // Map the requirements data, processing each section as needed
        return $requirements->map(function ($requirement) {
            return [
                'nbi_final_status' => $requirement->nbi_final_status,
                'nbi_validity_date' => $requirement->nbi_validity_date,
                'nbi_submitted_date' => $requirement->nbi_submitted_date,
                'nbi_printed_date' => $requirement->nbi_printed_date,
                'nbi_remarks' => $requirement->nbi_remarks,
                'nbi_file_url' => $requirement->nbi_file_name ? asset('storage/nbi_files/' . $requirement->nbi_file_name) : null,
                'nbi_last_updated_at' => \Carbon\Carbon::parse($requirement->nbi_last_updated_at)->format('Y-m-d H:i'),
                'nbi_updated_by' => $requirement->nbiUpdatedBy ? $requirement->nbiUpdatedBy->name : 'N/A',

                'dt_final_status' => $requirement->dt_final_status,
                'dt_results_date' => $requirement->dt_results_date,
                'dt_transaction_date' => $requirement->dt_transaction_date,
                'dt_endorsed_date' => $requirement->dt_endorsed_date,
                'dt_remarks' => $requirement->dt_remarks,
                'dt_file_url' => $requirement->dt_file_name ? asset('storage/dt_files/' . $requirement->dt_file_name) : null,
                'dt_last_updated_at' => \Carbon\Carbon::parse($requirement->dt_last_updated_at)->format('Y-m-d H:i'),
                'dt_updated_by' => $requirement->dtUpdatedBy ? $requirement->dtUpdatedBy->name : 'N/A',

                'peme_file_url' => $requirement->peme_file_name ? asset('storage/peme_files/' . $requirement->peme_file_name) : null,
                'peme_remarks' => $requirement->peme_remarks,
                'peme_endorsed_date' => $requirement->peme_endorsed_date,
                'peme_results_date' => $requirement->peme_results_date,
                'peme_transaction_date' => $requirement->peme_transaction_date,
                'peme_final_status' => $requirement->peme_final_status,
                'peme_last_updated_at' => \Carbon\Carbon::parse($requirement->peme_last_updated_at)->format('Y-m-d H:i'),
                'peme_updated_by' => $requirement->pemeUpdatedBy ? $requirement->pemeUpdatedBy->name : 'N/A',

                'sss_proof_submitted_type' => $requirement->sss_proof_submitted_type,
                'sss_final_status' => $requirement->sss_final_status,
                'sss_submitted_date' => $requirement->sss_submitted_date,
                'sss_remarks' => $requirement->sss_remarks,
                'sss_number' => $requirement->sss_number,
                'sss_file_url' => $requirement->sss_file_name ? asset('storage/sss_files/' . $requirement->sss_file_name) : null,
                'sss_last_updated_at' => \Carbon\Carbon::parse($requirement->sss_last_updated_at)->format('Y-m-d H:i'),
                'sss_updated_by' => $requirement->sssUpdatedBy ? $requirement->sssUpdatedBy->name : 'N/A',

                'phic_submitted_date' => $requirement->phic_submitted_date,
                'phic_final_status' => $requirement->phic_final_status,
                'phic_proof_submitted_type' => $requirement->phic_proof_submitted_type,
                'phic_remarks' => $requirement->phic_remarks,
                'phic_number' => $requirement->phic_number,
                'phic_file_url' => $requirement->phic_file_name ? asset('storage/phic_files/' . $requirement->phic_file_name) : null,
                'phic_last_updated_at' => \Carbon\Carbon::parse($requirement->phic_last_updated_at)->format('Y-m-d H:i'),
                'phic_updated_by' => $requirement->phicUpdatedBy ? $requirement->phicUpdatedBy->name : 'N/A',

                'pagibig_submitted_date' => $requirement->pagibig_submitted_date,
                'pagibig_final_status' => $requirement->pagibig_final_status,
                'pagibig_proof_submitted_type' => $requirement->pagibig_proof_submitted_type,
                'pagibig_remarks' => $requirement->pagibig_remarks,
                'pagibig_number' => $requirement->pagibig_number,
                'pagibig_file_url' => $requirement->pagibig_file_name ? asset('storage/pagibig_files/' . $requirement->pagibig_file_name) : null,
                'pagibig_last_updated_at' => \Carbon\Carbon::parse($requirement->pagibig_last_updated_at)->format('Y-m-d H:i'),
                'pagibig_updated_by' => $requirement->pagibigUpdatedBy ? $requirement->pagibigUpdatedBy->name : 'N/A',

                'tin_submitted_date' => $requirement->tin_submitted_date,
                'tin_final_status' => $requirement->tin_final_status,
                'tin_proof_submitted_type' => $requirement->tin_proof_submitted_type,
                'tin_remarks' => $requirement->tin_remarks,
                'tin_number' => $requirement->tin_number,
                'tin_file_url' => $requirement->tin_file_name ? asset('storage/tin_files/' . $requirement->tin_file_name) : null,
                'tin_last_updated_at' => \Carbon\Carbon::parse($requirement->tin_last_updated_at)->format('Y-m-d H:i'),
                'tin_updated_by' => $requirement->tinUpdatedBy ? $requirement->tinUpdatedBy->name : 'N/A',

                'health_certificate_validity_date' => $requirement->health_certificate_validity_date,
                'health_certificate_submitted_date' => $requirement->health_certificate_submitted_date,
                'health_certificate_remarks' => $requirement->health_certificate_remarks,
                'health_certificate_file_url' => $requirement->health_certificate_file_name ? asset('storage/health_certificate_files/' . $requirement->health_certificate_file_name) : null,
                'health_certificate_final_status' => $requirement->health_certificate_final_status,
                'health_certificate_last_updated_at' => \Carbon\Carbon::parse($requirement->health_certificate_last_updated_at)->format('Y-m-d H:i'),
                'health_certificate_updated_by' => $requirement->healthCertificateUpdatedBy ? $requirement->healthCertificateUpdatedBy->name : 'N/A',

                'occupational_permit_validity_date' => $requirement->occupational_permit_validity_date,
                'occupational_permit_submitted_date' => $requirement->occupational_permit_submitted_date,
                'occupational_permit_remarks' => $requirement->occupational_permit_remarks,
                'occupational_permit_file_url' => $requirement->occupational_permit_file_name ? asset('storage/occupational_permit_files/' . $requirement->occupational_permit_file_name) : null,
                'occupational_permit_final_status' => $requirement->occupational_permit_final_status,
                'occupational_permit_last_updated_at' => \Carbon\Carbon::parse($requirement->occupational_permit_last_updated_at)->format('Y-m-d H:i'),
                'occupational_permit_updated_by' => $requirement->occupationalPermitUpdatedBy ? $requirement->occupationalPermitUpdatedBy->name : 'N/A',

                'ofac_checked_date' => $requirement->ofac_checked_date,
                'ofac_final_status' => $requirement->ofac_final_status,
                'ofac_remarks' => $requirement->ofac_remarks,
                'ofac_file_url' => $requirement->ofac_file_name ? asset('storage/ofac_files/' . $requirement->ofac_file_name) : null,
                'ofac_last_updated_at' => \Carbon\Carbon::parse($requirement->ofac_last_updated_at)->format('Y-m-d H:i'),
                'ofac_updated_by' => $requirement->ofacUpdatedBy ? $requirement->ofacUpdatedBy->name : 'N/A',

                'sam_checked_date' => $requirement->sam_checked_date,
                'sam_final_status' => $requirement->sam_final_status,
                'sam_remarks' => $requirement->sam_remarks,
                'sam_file_url' => $requirement->sam_file_name ? asset('storage/sam_files/' . $requirement->sam_file_name) : null,
                'sam_last_updated_at' => \Carbon\Carbon::parse($requirement->sam_last_updated_at)->format('Y-m-d H:i'),
                'sam_updated_by' => $requirement->samUpdatedBy ? $requirement->samUpdatedBy->name : 'N/A',

                'oig_checked_date' => $requirement->oig_checked_date,
                'oig_final_status' => $requirement->oig_final_status,
                'oig_remarks' => $requirement->oig_remarks,
                'oig_file_url' => $requirement->oig_file_name ? asset('storage/oig_files/' . $requirement->oig_file_name) : null,
                'oig_last_updated_at' => \Carbon\Carbon::parse($requirement->oig_last_updated_at)->format('Y-m-d H:i'),
                'oig_updated_by' => $requirement->oigUpdatedBy ? $requirement->oigUpdatedBy->name : 'N/A',

                'cibi_checked_date' => $requirement->cibi_checked_date,
                'cibi_final_status' => $requirement->cibi_final_status,
                'cibi_remarks' => $requirement->cibi_remarks,
                'cibi_file_url' => $requirement->cibi_file_name ? asset('storage/cibi_files/' . $requirement->cibi_file_name) : null,
                'cibi_last_updated_at' => \Carbon\Carbon::parse($requirement->cibi_last_updated_at)->format('Y-m-d H:i'),
                'cibi_updated_by' => $requirement->cibiUpdatedBy ? $requirement->cibiUpdatedBy->name : 'N/A',

                'bgc_endorsed_date' => $requirement->bgc_endorsed_date,
                'bgc_results_date' => $requirement->bgc_results_date,
                'bgc_final_status' => $requirement->bgc_final_status,
                'bgc_remarks' => $requirement->bgc_remarks,
                'bgc_file_url' => $requirement->bgc_file_name ? asset('storage/bgc_files/' . $requirement->bgc_file_name) : null,
                'bgc_last_updated_at' => \Carbon\Carbon::parse($requirement->bgc_last_updated_at)->format('Y-m-d H:i'),
                'bgc_updated_by' => $requirement->bgcUpdatedBy ? $requirement->bgcUpdatedBy->name : 'N/A',

                'bc_file_url' => $requirement->birth_certificate_file_name ? asset('storage/birth_certificate_files/' . $requirement->birth_certificate_file_name) : null,
                'bc_submitted_date' => $requirement->birth_certificate_submitted_date,
                'bc_proof_type' => $requirement->birth_certificate_proof_type,
                'bc_remarks' => $requirement->birth_certificate_remarks,
                'bc_last_updated_at' => \Carbon\Carbon::parse($requirement->birth_certificate_last_updated_at)->format('Y-m-d H:i'),
                'bc_updated_by' => $requirement->birthCertificateUpdatedBy ? $requirement->birthCertificateUpdatedBy->name : 'N/A',

                'dbc_file_url' => $requirement->dependent_birth_certificate_file_name ? asset('storage/dependent_birth_certificate_files/' . $requirement->dependent_birth_certificate_file_name) : null,
                'dbc_submitted_date' => $requirement->dependent_birth_certificate_submitted_date,
                'dbc_proof_type' => $requirement->dependent_birth_certificate_proof_type,
                'dbc_remarks' => $requirement->dependent_birth_certificate_remarks,
                'dbc_last_updated_at' => \Carbon\Carbon::parse($requirement->dependent_birth_certificate_last_updated_at)->format('Y-m-d H:i'),
                'dbc_updated_by' => $requirement->dependentBirthCertificateUpdatedBy ? $requirement->dependentBirthCertificateUpdatedBy->name : 'N/A',

                'mc_file_url' => $requirement->marriage_certificate_file_name ? asset('storage/marriage_certificate_files/' . $requirement->marriage_certificate_file_name) : null,
                'mc_submitted_date' => $requirement->marriage_certificate_submitted_date,
                'mc_proof_type' => $requirement->marriage_certificate_proof_type,
                'mc_remarks' => $requirement->marriage_certificate_remarks,
                'mc_last_updated_at' => \Carbon\Carbon::parse($requirement->marriage_certificate_last_updated_at)->format('Y-m-d H:i'),
                'mc_updated_by' => $requirement->marriageCertificateUpdatedBy ? $requirement->marriageCertificateUpdatedBy->name : 'N/A',

                'sr_file_url' => $requirement->scholastic_record_file_name ? asset('storage/scholastic_record_files/' . $requirement->scholastic_record_file_name) : null,
                'sr_submitted_date' => $requirement->scholastic_record_submitted_date,
                'sr_proof_type' => $requirement->scholastic_record_proof_type,
                'sr_remarks' => $requirement->scholastic_record_remarks,
                'sr_last_updated_at' => \Carbon\Carbon::parse($requirement->scholastic_record_last_updated_at)->format('Y-m-d H:i'),
                'sr_updated_by' => $requirement->scholasticRecordUpdatedBy ? $requirement->scholasticRecordUpdatedBy->name : 'N/A',

                'pe_file_url' => $requirement->previous_employment_file_name ? asset('storage/previous_employment_files/' . $requirement->previous_employment_file_name) : null,
                'pe_submitted_date' => $requirement->previous_employment_submitted_date,
                'pe_proof_type' => $requirement->previous_employment_proof_type,
                'pe_remarks' => $requirement->previous_employment_remarks,
                'pe_last_updated_at' => \Carbon\Carbon::parse($requirement->previous_employment_last_updated_at)->format('Y-m-d H:i'),
                'pe_updated_by' => $requirement->previousEmploymentUpdatedBy ? $requirement->previousEmploymentUpdatedBy->name : 'N/A',

                'sd_file_url' => $requirement->supporting_documents_file_name ? asset('storage/supporting_documents_files/' . $requirement->supporting_documents_file_name) : null,
                'sd_submitted_date' => $requirement->supporting_documents_submitted_date,
                'sd_proof_type' => $requirement->supporting_documents_proof_type,
                'sd_remarks' => $requirement->supporting_documents_remarks,
                'sd_last_updated_at' => \Carbon\Carbon::parse($requirement->supporting_documents_last_updated_at)->format('Y-m-d H:i'),
                'sd_updated_by' => $requirement->supportingDocumentsUpdatedBy ? $requirement->supportingDocumentsUpdatedBy->name : 'N/A',

            ];
        });
    }

    public function show($id)
    {
        $employee = Employee::with([

            'requirements',
            'requirements.nbiUpdatedBy',
            'requirements.updatedBy',
            'requirements.dtUpdatedBy',
            'requirements.pemeUpdatedBy',
            'requirements.sssUpdatedBy',
            'requirements.phicUpdatedBy',
            'requirements.pagibigUpdatedBy',
            'requirements.healthCertificateUpdatedBy',
            'requirements.occupationalPermitUpdatedBy',
            'requirements.ofacUpdatedBy',
            'requirements.samUpdatedBy',
            'requirements.oigUpdatedBy',
            'requirements.cibiUpdatedBy',
            'requirements.bgcUpdatedBy',
            'requirements.birthCertificateUpdatedBy',
            'requirements.dependentBirthCertificateUpdatedBy',
            'requirements.marriageCertificateUpdatedBy',
            'requirements.scholasticRecordUpdatedBy',
            'requirements.previousEmploymentUpdatedBy',
            'requirements.supportingDocumentsUpdatedBy',
            'lob',
            'lob.siteName'
        ])->find($id);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        // Return employee data as JSON
        return response()->json(['employee' => $employee]);
    }
    public function showEmployee($id)
    {
        // Fetch the employee with related data using eager loading
        $employee = Employee::with([

            'requirements',
            'requirements.nbiUpdatedBy',
            'requirements.updatedBy',
            'requirements.dtUpdatedBy',
            'requirements.pemeUpdatedBy',
            'requirements.sssUpdatedBy',
            'requirements.phicUpdatedBy',
            'requirements.pagibigUpdatedBy',
            'requirements.healthCertificateUpdatedBy',
            'requirements.occupationalPermitUpdatedBy',
            'requirements.ofacUpdatedBy',
            'requirements.samUpdatedBy',
            'requirements.oigUpdatedBy',
            'requirements.cibiUpdatedBy',
            'requirements.bgcUpdatedBy',
            'requirements.birthCertificateUpdatedBy',
            'requirements.dependentBirthCertificateUpdatedBy',
            'requirements.marriageCertificateUpdatedBy',
            'requirements.scholasticRecordUpdatedBy',
            'requirements.previousEmploymentUpdatedBy',
            'requirements.supportingDocumentsUpdatedBy',
            'lob',
            'lob.siteName'
        ])->find($id);

        // Check if the employee exists
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        // Map the employee data
        $employee_data = $this->mapEmployeeData($employee);

        // Check if related data exists before mapping
        $requirements_data = $employee->requirements ? $this->mapRequirementsData($employee->requirements) : null;
        $lob_data = $employee->lob ? $this->mapLobData($employee->lob) : null;

        // Return the mapped data in the response
        return response()->json([
            'employee_data' => $employee_data,
            'requirements_data' => $requirements_data,
            'lob_data' => $lob_data,
        ]);
    }




    public function storeBulkEmployees(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
            'employee_added_by' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            Excel::import(new EmployeeImport($request->employee_added_by), $request->file('file'));

            return response()->json(['success' => 'Employees imported successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error importing Employee: ' . $e->getMessage()], 500);
        }
    }

    /*  public function update(Request $request, $id)
    {

        $validatedData  = Validator::make($request->all(), [
            // NBI Section
            'nbi_final_status' => 'nullable|string',
            'nbi_validity_date' => 'nullable|date',
            'nbi_submitted_date' => 'nullable|date',
            'nbi_printed_date' => 'nullable|date',
            'nbi_remarks' => 'nullable|string',
            'nbi_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // DT Section
            'dt_final_status' => 'nullable|string',
            'dt_results_date' => 'nullable|date',
            'dt_transaction_date' => 'nullable|date',
            'dt_endorsed_date' => 'nullable|date',
            'dt_remarks' => 'nullable|string',
            'dt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // PEME Section
            'peme_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'peme_remarks' => 'nullable|string',
            'peme_endorsed_date' => 'nullable|date',
            'peme_results_date' => 'nullable|date',
            'peme_transaction_date' => 'nullable|date',
            'peme_final_status' => 'nullable|string',

            // SSS Section
            'sss_proof_submitted_type' => 'nullable|string',
            'sss_final_status' => 'nullable|string',
            'sss_submitted_date' => 'nullable|date',
            'sss_remarks' => 'nullable|string',
            'sss_number' => 'nullable|string',
            'sss_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // PHIC Section
            'phic_submitted_date' => 'nullable|date',
            'phic_final_status' => 'nullable|string',
            'phic_proof_submitted_type' => 'nullable|string',
            'phic_remarks' => 'nullable|string',
            'phic_number' => 'nullable|string',
            'phic_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // Pagibig Section
            'pagibig_submitted_date' => 'nullable|date',
            'pagibig_final_status' => 'nullable|string',
            'pagibig_proof_submitted_type' => 'nullable|string',
            'pagibig_remarks' => 'nullable|string',
            'pagibig_number' => 'nullable|string',
            'pagibig_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // Health Certificate Section
            'health_certificate_validity_date' => 'nullable|date',
            'health_certificate_submitted_date' => 'nullable|date',
            'health_certificate_remarks' => 'nullable|string',
            'health_certificate_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'health_certificate_final_status' => 'nullable|string',

            // Occupational Permit Section
            'occupational_permit_validity_date' => 'nullable|date',
            'occupational_permit_submitted_date' => 'nullable|date',
            'occupational_permit_remarks' => 'nullable|string',
            'occupational_permit_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'occupational_permit_final_status' => 'nullable|string',

            // OFAC Section
            'ofac_checked_date' => 'nullable|date',
            'ofac_final_status' => 'nullable|string',
            'ofac_remarks' => 'nullable|string',
            'ofac_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // SAM Section
            'sam_checked_date' => 'nullable|date',
            'sam_final_status' => 'nullable|string',
            'sam_remarks' => 'nullable|string',
            'sam_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // OIG Section
            'oig_checked_date' => 'nullable|date',
            'oig_final_status' => 'nullable|string',
            'oig_remarks' => 'nullable|string',
            'oig_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // CIBI Section
            'cibi_checked_date' => 'nullable|date',
            'cibi_final_status' => 'nullable|string',
            'cibi_remarks' => 'nullable|string',
            'cibi_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // BGC Section
            'bgc_endorsed_date' => 'nullable|date',
            'bgc_results_date' => 'nullable|date',
            'bgc_final_status' => 'nullable|string',
            'bgc_remarks' => 'nullable|string',
            'bgc_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // BC Section
            'birth_certificate_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'birth_certificate_submitted_date' => 'nullable|date',
            'birth_certificate_proof_type' => 'nullable|string',
            'birth_certificate_remarks' => 'nullable|string',

            // DBC Section
            'dependent_birth_certificate_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dependent_birth_certificate_submitted_date' => 'nullable|date',
            'dependent_birth_certificate_proof_type' => 'nullable|string',
            'dependent_birth_certificate_remarks' => 'nullable|string',

            // MC Section
            'marriage_certificate_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'marriage_certificate_submitted_date' => 'nullable|date',
            'marriage_certificate_proof_type' => 'nullable|string',
            'marriage_certificate_remarks' => 'nullable|string',

            // SR Section
            'scholastic_record_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'scholastic_record_submitted_date' => 'nullable|date',
            'scholastic_record_proof_type' => 'nullable|string',
            'scholastic_record_remarks' => 'nullable|string',

            // PE Section
            'previous_employment_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'previous_employment_submitted_date' => 'nullable|date',
            'previous_employment_proof_type' => 'nullable|string',
            'previous_employment_remarks' => 'nullable|string',

            // SD Section
            'supporting_documents_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'supporting_documents_submitted_date' => 'nullable|date',
            'supporting_documents_proof_type' => 'nullable|string',
            'supporting_documents_remarks' => 'nullable|string',
        ])->validate();


        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            // Handle the case when the requirement is not found
            // For example, return an error message or create a new requirement record
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        // NBI Section
        $requirement->nbi_final_status = $validatedData['nbi_final_status'];
        $requirement->nbi_validity_date = $validatedData['nbi_validity_date'];
        $requirement->nbi_submitted_date = $validatedData['nbi_submitted_date'];
        $requirement->nbi_printed_date = $validatedData['nbi_printed_date'];
        $requirement->nbi_remarks = $validatedData['nbi_remarks'];
        if ($request->hasFile('nbi_file')) {
            $file = $request->file('nbi_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('nbi_files', $fileName, 'public');
            $requirement->nbi_file_name = $fileName;
        }
        $requirement->nbi_last_updated_at = now();
        $requirement->nbi_updated_by = auth()->id();

        // DT Section
        $requirement->dt_final_status = $validatedData['dt_final_status'];
        $requirement->dt_results_date = $validatedData['dt_results_date'];
        $requirement->dt_transaction_date = $validatedData['dt_transaction_date'];
        $requirement->dt_endorsed_date = $validatedData['dt_endorsed_date'];
        $requirement->dt_remarks = $validatedData['dt_remarks'];
        if ($request->hasFile('dt_file')) {
            $file = $request->file('dt_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dt_files', $fileName, 'public');
            $requirement->dt_file_name = $fileName;
        }
        $requirement->dt_last_updated_at = now();
        $requirement->dt_updated_by = auth()->id();

        // PEME Section
        $requirement->peme_remarks = $validatedData['peme_remarks'];
        $requirement->peme_endorsed_date = $validatedData['peme_endorsed_date'];
        $requirement->peme_results_date = $validatedData['peme_results_date'];
        $requirement->peme_transaction_date = $validatedData['peme_transaction_date'];
        $requirement->peme_final_status = $validatedData['peme_final_status'];
        if ($request->hasFile('peme_file')) {
            $file = $request->file('peme_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('peme_files', $fileName, 'public');
            $requirement->peme_file_name = $fileName;
        }
        $requirement->peme_last_updated_at = now();
        $requirement->peme_updated_by = auth()->id();

        // SSS Section
        $requirement->sss_proof_submitted_type = $validatedData['sss_proof_submitted_type'];
        $requirement->sss_final_status = $validatedData['sss_final_status'];
        $requirement->sss_submitted_date = $validatedData['sss_submitted_date'];
        $requirement->sss_remarks = $validatedData['sss_remarks'];
        $requirement->sss_number = $validatedData['sss_number'];
        if ($request->hasFile('sss_file')) {
            $file = $request->file('sss_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('sss_files', $fileName, 'public');
            $requirement->sss_file_name = $fileName;
        }
        $requirement->sss_last_updated_at = now();
        $requirement->sss_updated_by = auth()->id();

        // PHIC Section
        $requirement->phic_submitted_date = $validatedData['phic_submitted_date'];
        $requirement->phic_final_status = $validatedData['phic_final_status'];
        $requirement->phic_proof_submitted_type = $validatedData['phic_proof_submitted_type'];
        $requirement->phic_remarks = $validatedData['phic_remarks'];
        $requirement->phic_number = $validatedData['phic_number'];
        if ($request->hasFile('phic_file')) {
            $file = $request->file('phic_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('phic_files', $fileName, 'public');
            $requirement->phic_file_name = $fileName;
        }
        $requirement->phic_last_updated_at = now();
        $requirement->phic_updated_by = auth()->id();

        // Pagibig Section
        $requirement->pagibig_submitted_date = $validatedData['pagibig_submitted_date'];
        $requirement->pagibig_final_status = $validatedData['pagibig_final_status'];
        $requirement->pagibig_proof_submitted_type = $validatedData['pagibig_proof_submitted_type'];
        $requirement->pagibig_remarks = $validatedData['pagibig_remarks'];
        $requirement->pagibig_number = $validatedData['pagibig_number'];
        if ($request->hasFile('pagibig_file')) {
            $file = $request->file('pagibig_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('pagibig_files', $fileName, 'public');
            $requirement->pagibig_file_name = $fileName;
        }
        $requirement->pagibig_last_updated_at = now();
        $requirement->pagibig_updated_by = auth()->id();

        // Health Certificate Section
        $requirement->health_certificate_validity_date = $validatedData['health_certificate_validity_date'];
        $requirement->health_certificate_submitted_date = $validatedData['health_certificate_submitted_date'];
        $requirement->health_certificate_remarks = $validatedData['health_certificate_remarks'];
        $requirement->health_certificate_final_status = $validatedData['health_certificate_final_status'];
        if ($request->hasFile('health_certificate_file')) {
            $file = $request->file('health_certificate_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('health_certificate_files', $fileName, 'public');
            $requirement->health_certificate_file_name = $fileName;
        }
        $requirement->health_certificate_last_updated_at = now();
        $requirement->health_certificate_updated_by = auth()->id();

        // Occupational Permit Section
        $requirement->occupational_permit_validity_date = $validatedData['occupational_permit_validity_date'];
        $requirement->occupational_permit_submitted_date = $validatedData['occupational_permit_submitted_date'];
        $requirement->occupational_permit_remarks = $validatedData['occupational_permit_remarks'];
        $requirement->occupational_permit_final_status = $validatedData['occupational_permit_final_status'];
        if ($request->hasFile('occupational_permit_file')) {
            $file = $request->file('occupational_permit_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('occupational_permit_files', $fileName, 'public');
            $requirement->occupational_permit_file_name = $fileName;
        }
        $requirement->occupational_permit_last_updated_at = now();
        $requirement->occupational_permit_updated_by = auth()->id();

        // OFAC Section
        $requirement->ofac_checked_date = $validatedData['ofac_checked_date'];
        $requirement->ofac_final_status = $validatedData['ofac_final_status'];
        $requirement->ofac_remarks = $validatedData['ofac_remarks'];
        if ($request->hasFile('ofac_file')) {
            $file = $request->file('ofac_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('ofac_files', $fileName, 'public');
            $requirement->ofac_file_name = $fileName;
        }
        $requirement->ofac_last_updated_at = now();
        $requirement->ofac_updated_by = auth()->id();

        // SAM Section
        $requirement->sam_checked_date = $validatedData['sam_checked_date'];
        $requirement->sam_final_status = $validatedData['sam_final_status'];
        $requirement->sam_remarks = $validatedData['sam_remarks'];
        if ($request->hasFile('sam_file')) {
            $file = $request->file('sam_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('sam_files', $fileName, 'public');
            $requirement->sam_file_name = $fileName;
        }
        $requirement->sam_last_updated_at = now();
        $requirement->sam_updated_by = auth()->id();

        // OIG Section
        $requirement->oig_checked_date = $validatedData['oig_checked_date'];
        $requirement->oig_final_status = $validatedData['oig_final_status'];
        $requirement->oig_remarks = $validatedData['oig_remarks'];
        if ($request->hasFile('oig_file')) {
            $file = $request->file('oig_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('oig_files', $fileName, 'public');
            $requirement->oig_file_name = $fileName;
        }
        $requirement->oig_last_updated_at = now();
        $requirement->oig_updated_by = auth()->id();

        // CIBI Section
        $requirement->cibi_final_status = $validatedData['cibi_final_status'];
        $requirement->cibi_checked_date = $validatedData['cibi_checked_date'];
        $requirement->cibi_remarks = $validatedData['cibi_remarks'];
        if ($request->hasFile('cibi_file')) {
            $file = $request->file('cibi_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('cibi_files', $fileName, 'public');
            $requirement->cibi_file_name = $fileName;
        }
        $requirement->cibi_last_updated_at = now();
        $requirement->cibi_updated_by = auth()->id();
        //BGC
        $requirement->bgc_endorsed_date = $validatedData['bgc_endorsed_date'];
        $requirement->bgc_results_date = $validatedData['bgc_results_date'];
        $requirement->bgc_final_status = $validatedData['bgc_final_status'];
        $requirement->bgc_remarks = $validatedData['bgc_remarks'];
        if ($request->hasFile('bgc_file_name')) {
            $file = $request->file('bgc_file_name');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('bgc_files', $fileName, 'public');
            $requirement->bgc_file_name = $fileName;
        }
        $requirement->bgc_last_updated_at = now();
        $requirement->bgc_updated_by = auth()->id();

        // BC Section
        $requirement->birth_certificate_file_name = $validatedData['birth_certificate_file_name'];
        $requirement->birth_certificate_submitted_date = $validatedData['birth_certificate_submitted_date'];
        $requirement->birth_certificate_proof_type = $validatedData['birth_certificate_proof_type'];
        $requirement->birth_certificate_remarks = $validatedData['birth_certificate_remarks'];
        if ($request->hasFile('birth_certificate_file_name')) {
            $file = $request->file('birth_certificate_file_name');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('birth_certificate_files', $fileName, 'public');
            $requirement->birth_certificate_file_name = $fileName;
        }
        $requirement->birth_certificate_last_updated_at = now();
        $requirement->birth_certificate_updated_by = auth()->id();

        // DBC Section
        $requirement->dependent_birth_certificate_file_name = $validatedData['dependent_birth_certificate_file_name'];
        $requirement->dependent_birth_certificate_submitted_date = $validatedData['dependent_birth_certificate_submitted_date'];
        $requirement->dependent_birth_certificate_proof_type = $validatedData['dependent_birth_certificate_proof_type'];
        $requirement->dependent_birth_certificate_remarks = $validatedData['dependent_birth_certificate_remarks'];
        if ($request->hasFile('dependent_birth_certificate_file_name')) {
            $file = $request->file('dependent_birth_certificate_file_name');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dependent_birth_certificate_files', $fileName, 'public');
            $requirement->dependent_birth_certificate_file_name = $fileName;
        }
        $requirement->dependent_birth_certificate_last_updated_at = now();
        $requirement->dependent_birth_certificate_updated_by = auth()->id();

        // MC Section
        $requirement->marriage_certificate_file_name = $validatedData['marriage_certificate_file_name'];
        $requirement->marriage_certificate_submitted_date = $validatedData['marriage_certificate_submitted_date'];
        $requirement->marriage_certificate_proof_type = $validatedData['marriage_certificate_proof_type'];
        $requirement->marriage_certificate_remarks = $validatedData['marriage_certificate_remarks'];
        if ($request->hasFile('marriage_certificate_file_name')) {
            $file = $request->file('marriage_certificate_file_name');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('marriage_certificate_files', $fileName, 'public');
            $requirement->marriage_certificate_file_name = $fileName;
        }
        $requirement->marriage_certificate_last_updated_at = now();
        $requirement->marriage_certificate_updated_by = auth()->id();

        // SR Section
        $requirement->scholastic_record_file_name = $validatedData['scholastic_record_file_name'];
        $requirement->scholastic_record_submitted_date = $validatedData['scholastic_record_submitted_date'];
        $requirement->scholastic_record_proof_type = $validatedData['scholastic_record_proof_type'];
        $requirement->scholastic_record_remarks = $validatedData['scholastic_record_remarks'];
        if ($request->hasFile('scholastic_record_file_name')) {
            $file = $request->file('scholastic_record_file_name');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('scholastic_record_files', $fileName, 'public');
            $requirement->scholastic_record_file_name = $fileName;
        }
        $requirement->scholastic_record_last_updated_at = now();
        $requirement->scholastic_record_updated_by = auth()->id();

        // PE Section
        $requirement->previous_employment_file_name = $validatedData['previous_employment_file_name'];
        $requirement->previous_employment_submitted_date = $validatedData['previous_employment_submitted_date'];
        $requirement->previous_employment_proof_type = $validatedData['previous_employment_proof_type'];
        $requirement->previous_employment_remarks = $validatedData['previous_employment_remarks'];
        if ($request->hasFile('previous_employment_file_name')) {
            $file = $request->file('previous_employment_file_name');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('previous_employment_files', $fileName, 'public');
            $requirement->previous_employment_file_name = $fileName;
        }
        $requirement->previous_employment_last_updated_at = now();
        $requirement->previous_employment_updated_by = auth()->id();

        // SD Section
        $requirement->supporting_documents_file_name = $validatedData['supporting_documents_file_name'];
        $requirement->supporting_documents_submitted_date = $validatedData['supporting_documents_submitted_date'];
        $requirement->supporting_documents_proof_type = $validatedData['supporting_documents_proof_type'];
        $requirement->supporting_documents_remarks = $validatedData['supporting_documents_remarks'];
        if ($request->hasFile('supporting_documents_file_name')) {
            $file = $request->file('supporting_documents_file_name');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('supporting_documents_files', $fileName, 'public');
            $requirement->supporting_documents_file_name = $fileName;
        }
        $requirement->supporting_documents_last_updated_at = now();
        $requirement->supporting_documents_updated_by = auth()->id();
        $requirement->save();

        return response()->json([
            'message' => 'Requirement updated successfully',
            'requirement' => $requirement,
        ]);
    }
 */

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'employee_tbl_id' => 'required|exists:employees,employee_id',
            'nbi' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'nbi_remarks' => 'nullable|string',
            'nbi_validity_date' => 'nullable|date',
            'nbi_printed_date' => 'nullable|date',
            'dt_results' => 'nullable|string',
            'dt_transaction_date' => 'nullable|date',
            'dt_results_date' => 'nullable|date',
            'peme_status' => 'nullable|string',
            'peme_remarks' => 'nullable|string',
            'bgc' => 'nullable|string',
            'bgc_remarks' => 'nullable|string',
            'bgc_endorsed_date' => 'nullable|date',
            'bgc_received_date' => 'nullable|date',
            'sss_number' => 'nullable|string',
            'sss_remarks' => 'nullable|string',
            'phic_number' => 'nullable|string',
            'phic_remarks' => 'nullable|string',
            'hdmf_number' => 'nullable|string',
            'hdmf_remarks' => 'nullable|string',
            'tin' => 'nullable|string',
            'tin_remarks' => 'nullable|string',
            'form_1902' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'attachment_1902' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'health_certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'vaccination_card' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'two_by_two' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'form_2316' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'nso_birth_certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'dependents_nso_birth_certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'marriage_certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'cibi' => 'nullable|string',
            'cibi_search_date' => 'nullable|date',
            'ofac' => 'nullable|string',
            'sam' => 'nullable|string',
            'oig' => 'nullable|string',
            'month_milestone' => 'nullable|string',
            'week_ending' => 'nullable|date',
            'fifteenth_day_deadline' => 'nullable|date',
            'end_of_product_training' => 'nullable|date',
            'past_due' => 'nullable|string',
            'on_track' => 'nullable|string',
            'nbi_dt' => 'nullable|string',
            'job_offer_letter' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'interview_form_compliance' => 'nullable|string',
            'tmp_notes' => 'nullable|string',
            'tmp_ii' => 'nullable|string',
            'tmp_id' => 'nullable|string',
            'tmp_ov' => 'nullable|string',
            'tmp_status' => 'nullable|string',
            'jo_hr_contract_compliance' => 'nullable|string',
            'data_privacy_form' => 'nullable|string',
            'undertaking_non_employment_agreement' => 'nullable|string',
            'addendum_att' => 'nullable|string',
            'addendum_language_assessment' => 'nullable|string',
            'social_media_policy' => 'nullable|string',
            'contract_tmp_remarks' => 'nullable|string',
            'endorsed_by_hs' => 'nullable|string',
            'endorsed_to_compliance' => 'nullable|string',
            'return_to_hs_with_findings' => 'nullable|string',
            'last_received_from_hs_with_findings' => 'nullable|string',
            'status_201' => 'nullable|string',
            'compliance_remarks' => 'nullable|string',
            'with_findings' => 'nullable|string',
            'transmittal_to_act_hris_email_subject_sent' => 'nullable|string',
            'act_hris_remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $validatedData = $request->all();
        foreach (['nbi', 'dt', 'peme', 'bgc', 'sss', 'phic', 'hdmf', 'tin', '', '1902', 'health_certificate', 'vaccination_card', 'two_by_two', 'form_2316', 'nso_birth_certificate', 'dependents_nso_birth_certificate', 'marriage_certificate'] as $field) {
            if ($request->hasFile($field)) {
                $imagePath = $request->file($field)->store('uploads/requirements', 'public');
                $validatedData[$field . '_path'] = $imagePath;
                $validatedData[$field . '_file_name'] = $request->file($field)->getClientOriginalName();
            }
        }
        $requirements = Requirements::create($validatedData);

        return response()->json([
            'requirements' => $requirements,
        ]);
    }
    /*  public function update(Request $request, $id)
    {
        Log::info('Update Request Received', ['id' => $id, 'data' => $request->all()]);
        // Validate input data
        $validatedData = Validator::make($request->all(), [
            'nbi_final_status' => 'nullable|string',
            'nbi_validity_date' => 'nullable|date',
            'nbi_submitted_date' => 'nullable|date',
            'nbi_printed_date' => 'nullable|date',
            'nbi_remarks' => 'nullable|string',
            'nbi_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // DT Section
            'dt_final_status' => 'nullable|string',
            'dt_results_date' => 'nullable|date',
            'dt_transaction_date' => 'nullable|date',
            'dt_endorsed_date' => 'nullable|date',
            'dt_remarks' => 'nullable|string',
            'dt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // PEME Section
            'peme_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'peme_remarks' => 'nullable|string',
            'peme_endorsed_date' => 'nullable|date',
            'peme_results_date' => 'nullable|date',
            'peme_transaction_date' => 'nullable|date',
            'peme_final_status' => 'nullable|string',

            // SSS Section
            'sss_proof_submitted_type' => 'nullable|string',
            'sss_final_status' => 'nullable|string',
            'sss_submitted_date' => 'nullable|date',
            'sss_remarks' => 'nullable|string',
            'sss_number' => 'nullable|string',
            'sss_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // PHIC Section
            'phic_submitted_date' => 'nullable|date',
            'phic_final_status' => 'nullable|string',
            'phic_proof_submitted_type' => 'nullable|string',
            'phic_remarks' => 'nullable|string',
            'phic_number' => 'nullable|string',
            'phic_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // Pagibig Section
            'pagibig_submitted_date' => 'nullable|date',
            'pagibig_final_status' => 'nullable|string',
            'pagibig_proof_submitted_type' => 'nullable|string',
            'pagibig_remarks' => 'nullable|string',
            'pagibig_number' => 'nullable|string',
            'pagibig_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // Health Certificate Section
            'health_certificate_validity_date' => 'nullable|date',
            'health_certificate_submitted_date' => 'nullable|date',
            'health_certificate_remarks' => 'nullable|string',
            'health_certificate_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'health_certificate_final_status' => 'nullable|string',

            // Occupational Permit Section
            'occupational_permit_validity_date' => 'nullable|date',
            'occupational_permit_submitted_date' => 'nullable|date',
            'occupational_permit_remarks' => 'nullable|string',
            'occupational_permit_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'occupational_permit_final_status' => 'nullable|string',

            // OFAC Section
            'ofac_checked_date' => 'nullable|date',
            'ofac_final_status' => 'nullable|string',
            'ofac_remarks' => 'nullable|string',
            'ofac_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // SAM Section
            'sam_checked_date' => 'nullable|date',
            'sam_final_status' => 'nullable|string',
            'sam_remarks' => 'nullable|string',
            'sam_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // OIG Section
            'oig_checked_date' => 'nullable|date',
            'oig_final_status' => 'nullable|string',
            'oig_remarks' => 'nullable|string',
            'oig_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // CIBI Section
            'cibi_checked_date' => 'nullable|date',
            'cibi_final_status' => 'nullable|string',
            'cibi_remarks' => 'nullable|string',
            'cibi_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // BGC Section
            'bgc_endorsed_date' => 'nullable|date',
            'bgc_results_date' => 'nullable|date',
            'bgc_final_status' => 'nullable|string',
            'bgc_remarks' => 'nullable|string',
            'bgc_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // BC Section
            'birth_certificate_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'birth_certificate_submitted_date' => 'nullable|date',
            'birth_certificate_proof_type' => 'nullable|string',
            'birth_certificate_remarks' => 'nullable|string',

            // DBC Section
            'dependent_birth_certificate_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dependent_birth_certificate_submitted_date' => 'nullable|date',
            'dependent_birth_certificate_proof_type' => 'nullable|string',
            'dependent_birth_certificate_remarks' => 'nullable|string',

            // MC Section
            'marriage_certificate_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'marriage_certificate_submitted_date' => 'nullable|date',
            'marriage_certificate_proof_type' => 'nullable|string',
            'marriage_certificate_remarks' => 'nullable|string',

            // SR Section
            'scholastic_record_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'scholastic_record_submitted_date' => 'nullable|date',
            'scholastic_record_proof_type' => 'nullable|string',
            'scholastic_record_remarks' => 'nullable|string',

            // PE Section
            'previous_employment_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'previous_employment_submitted_date' => 'nullable|date',
            'previous_employment_proof_type' => 'nullable|string',
            'previous_employment_remarks' => 'nullable|string',

            // SD Section
            'supporting_documents_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'supporting_documents_submitted_date' => 'nullable|date',
            'supporting_documents_proof_type' => 'nullable|string',
            'supporting_documents_remarks' => 'nullable|string',

        ])->validate();

        // Find the requirements record by employee_tbl_id
        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        $sections = [
            'nbi' => ['final_status', 'validity_date', 'submitted_date', 'printed_date', 'remarks', 'file'],
            'dt' => ['final_status', 'results_date', 'transaction_date', 'endorsed_date', 'remarks', 'file'],
            'peme' => ['remarks', 'endorsed_date', 'results_date', 'transaction_date', 'final_status', 'file'],
            'sss' => ['proof_submitted_type', 'final_status', 'submitted_date', 'remarks', 'number', 'file'],
            'phic' => ['submitted_date', 'final_status', 'proof_submitted_type', 'remarks', 'number', 'file'],
            'pagibig' => ['submitted_date', 'final_status', 'proof_submitted_type', 'remarks', 'number', 'file'],
            'health_certificate' => ['validity_date', 'submitted_date', 'remarks', 'file', 'final_status'],
            'occupational_permit' => ['validity_date', 'submitted_date', 'remarks', 'file', 'final_status'],
            'ofac' => ['checked_date', 'final_status', 'remarks', 'file'],
            'sam' => ['checked_date', 'final_status', 'remarks', 'file'],
            'oig' => ['checked_date', 'final_status', 'remarks', 'file'],
            'cibi' => ['checked_date', 'final_status', 'remarks', 'file'],
            'bgc' => ['endorsed_date', 'results_date', 'final_status', 'remarks', 'file'],
            'birth_certificate' => ['file', 'submitted_date', 'proof_type', 'remarks'],
            'dependent_birth_certificate' => ['file', 'submitted_date', 'proof_type', 'remarks'],
            'marriage_certificate' => ['file', 'submitted_date', 'proof_type', 'remarks'],
            'scholastic_record' => ['file', 'submitted_date', 'proof_type', 'remarks'],
            'previous_employment' => ['file', 'submitted_date', 'proof_type', 'remarks'],
            'supporting_documents' => ['file', 'submitted_date', 'proof_type', 'remarks'],
            // Add any additional sections here...
        ];

        foreach ($sections as $section => $fields) {
            foreach ($fields as $field) {
                $inputName = $section . '_' . $field;

                if ($request->hasFile($inputName)) {
                    $file = $request->file($inputName);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($section . '_files', $fileName, 'public'); // Store in 'public' disk

                    Log::info('File Uploaded', [
                        'section' => $section,
                        'file' => $fileName,
                        'path' => $filePath
                    ]);

                    $requirement->{$section . '_file_name'} = $fileName; // Save the file name in the database
                }
            }
        }

        // Save the requirement model
        if ($requirement->save()) {
            Log::info('Requirement updated successfully', ['id' => $id]);
        } else {
            Log::error('Failed to update requirement', ['id' => $id]);
        }


        // Save the updated requirement
        $requirement->save();

        return response()->json(['success' => 'Requirement updated successfully']);
    } */
    public function updateEmployeeInfo(Request $request, $id)
{
    try {
        // Find the employee record by ID
        $employee = Employee::findOrFail($id);

        // Validate the incoming data
        $validatedData = $request->validate([
            'employee_info_first_name' => 'nullable|string|max:255',
            'employee_info_middle_name' => 'nullable|string|max:255',
            'employee_info_last_name' => 'nullable|string|max:255',
            'employee_info_position' => 'nullable|string|max:255',
            'employee_info_employee_id' => 'required|string|max:255',
            'employee_info_contact_number' => 'nullable|string|max:15',
            'employee_info_email_address' => 'nullable|email|max:255',
            'employee_info_birth_date' => 'nullable|date',
            'employee_info_hired_date' => 'nullable|date',
            'employee_info_employee_status' => 'nullable|string|max:255',
            'employee_info_employement_status' => 'nullable|string|max:255',
            'employee_info_hired_month' => 'nullable|string|max:50', 
            'employee_info_updated_by' => 'nullable|string|max:50', 
        ]);

        // Update the employee information
        $employee->first_name = $validatedData['employee_info_first_name'];
        $employee->middle_name = $validatedData['employee_info_middle_name'] ?? null;
        $employee->last_name = $validatedData['employee_info_last_name'];
        $employee->account_associate = $validatedData['employee_info_position'] ?? null;
        $employee->employee_id = $validatedData['employee_info_employee_id'];
        $employee->contact_number = $validatedData['employee_info_contact_number'] ?? null;
        $employee->email = $validatedData['employee_info_email_address'] ?? null;
        $employee->birthdate = $validatedData['employee_info_birth_date'] ?? null;
        $employee->hired_date = $validatedData['employee_info_hired_date'] ?? null;
        $employee->employee_status = $validatedData['employee_info_employee_status'] ?? null;
        $employee->employment_status = $validatedData['employee_info_employement_status'] ?? null;
        $employee->hired_month = $validatedData['employee_info_hired_month'] ?? null;
        $employee->updated_at = now();
        $employee->updated_by = $validatedData['employee_info_updated_by'] ?? null;

        // Save the changes
        $employee->save();

        return response()->json([
            'message' => 'Employee information updated successfully!',
            'data' => $employee,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to update employee information.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function updateNBI(Request $request, $id)
    {
        Log::info('Update Request Received', ['id' => $id, 'data' => $request->all()]);

        // Validate input data
        $validatedData = Validator::make($request->all(), [
            'nbi_final_status' => 'nullable|string',
            'nbi_validity_date' => 'nullable|date',
            'nbi_submitted_date' => 'nullable|date',
            'nbi_printed_date' => 'nullable|date',
            'nbi_remarks' => 'nullable|string',
            'nbi_updated_by' => 'nullable|integer',
            'nbi_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        // Find the requirements record by employee_tbl_id
        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        // Process the file upload for 'nbi_proof'
        if ($request->hasFile('nbi_proof')) {
            $file = $request->file('nbi_proof');

            // Ensure the file is valid
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('nbi_files', $fileName, 'public'); // Store in 'public' disk

                Log::info('File Uploaded', [
                    'file' => $fileName,
                    'path' => $filePath
                ]);

                // Save the file name (or path) to the database
                $requirement->nbi_file_name = $fileName; // Save the file name to the database
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        // Update other fields
        $requirement->nbi_final_status = $request->input('nbi_final_status');
        $requirement->nbi_validity_date = $request->input('nbi_validity_date');
        $requirement->nbi_submitted_date = $request->input('nbi_submitted_date');
        $requirement->nbi_printed_date = $request->input('nbi_printed_date');
        $requirement->nbi_remarks = $request->input('nbi_remarks');
        $requirement->nbi_updated_by = $request->input('nbi_updated_by');
        $requirement->nbi_last_updated_at = now();

        // Save the requirement model after processing all fields
        if ($requirement->save()) {
            Log::info('Requirement updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Requirement updated successfully']);
        } else {
            Log::error('Failed to update requirement', ['id' => $id]);
            return response()->json(['error' => 'Failed to update requirement'], 500);
        }
    }
    public function updateDT(Request $request, $id)
    {
        Log::info('Update DT Request Received', ['id' => $id, 'data' => $request->all()]);

        // Validate input data
        $validatedData = Validator::make($request->all(), [
            'dt_final_status' => 'nullable|string',
            'dt_results_date' => 'nullable|date',
            'dt_transaction_date' => 'nullable|date',
            'dt_endorsed_date' => 'nullable|date',
            'dt_remarks' => 'nullable|string',
            'dt_updated_by' => 'nullable|integer',
            'dt_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        // Find the requirements record by employee_tbl_id
        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        // Process the file upload for 'dt_proof'
        if ($request->hasFile('dt_proof')) {
            $file = $request->file('dt_proof');

            // Ensure the file is valid
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('dt_files', $fileName, 'public'); // Store in 'public' disk

                Log::info('File Uploaded', [
                    'file' => $fileName,
                    'path' => $filePath
                ]);

                // Save the file name (or path) to the database
                $requirement->dt_file_name = $fileName; // Save the file name to the database
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        // Update other fields
        $requirement->dt_final_status = $request->input('dt_final_status');
        $requirement->dt_results_date = $request->input('dt_results_date');
        $requirement->dt_transaction_date = $request->input('dt_transaction_date');
        $requirement->dt_endorsed_date = $request->input('dt_endorsed_date');
        $requirement->dt_remarks = $request->input('dt_remarks');
        $requirement->dt_updated_by = $request->input('dt_updated_by');
        $requirement->dt_last_updated_at = now();

        // Save the requirement model after processing all fields
        if ($requirement->save()) {
            Log::info('DT Requirement updated successfully', ['id' => $id]);
            return response()->json(['success' => 'DT Requirement updated successfully']);
        } else {
            Log::error('Failed to update DT requirement', ['id' => $id]);
            return response()->json(['error' => 'Failed to update DT requirement'], 500);
        }
    }
    public function updatePEME(Request $request, $id)
    {
        Log::info('Update PEME Request Received', ['id' => $id, 'data' => $request->all()]);

        // Validate input data
        $validatedData = Validator::make($request->all(), [
            'peme_final_status' => 'nullable|string',
            'peme_results_date' => 'nullable|date',
            'peme_transaction_date' => 'nullable|date',
            'peme_endorsed_date' => 'nullable|date',
            'peme_remarks' => 'nullable|string',
            'peme_updated_by' => 'nullable|integer',
            'peme_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        // Find the requirements record by employee_tbl_id
        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        // Process the file upload for 'peme_proof'
        if ($request->hasFile('peme_proof')) {
            $file = $request->file('peme_proof');

            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('peme_files', $fileName, 'public');

                Log::info('File Uploaded', [
                    'file' => $fileName,
                    'path' => $filePath
                ]);

                $requirement->peme_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        // Update other fields
        $requirement->peme_final_status = $request->input('peme_final_status');
        $requirement->peme_results_date = $request->input('peme_results_date');
        $requirement->peme_transaction_date = $request->input('peme_transaction_date');
        $requirement->peme_endorsed_date = $request->input('peme_endorsed_date');
        $requirement->peme_remarks = $request->input('peme_remarks');
        $requirement->peme_updated_by = $request->input('peme_updated_by');
        $requirement->peme_last_updated_at = now();

        // Save the requirement model after processing all fields
        if ($requirement->save()) {
            Log::info('PEME Requirement updated successfully', ['id' => $id]);
            return response()->json(['success' => 'PEME Requirement updated successfully']);
        } else {
            Log::error('Failed to update PEME requirement', ['id' => $id]);
            return response()->json(['error' => 'Failed to update PEME requirement'], 500);
        }
    }
    public function updateSSS(Request $request, $id)
    {
        Log::info('Update SSS Request Received', ['id' => $id, 'data' => $request->all()]);

        // Validate input data
        $validatedData = Validator::make($request->all(), [
            'sss_final_status' => 'nullable|string',
            'sss_submitted_date' => 'nullable|date',
            'sss_remarks' => 'nullable|string',
            'sss_number' => 'nullable|string',
            'sss_proof_submitted_type' => 'nullable|string',
            'sss_updated_by' => 'nullable|integer',
            'sss_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        // Find the requirements record by employee_tbl_id
        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        // Process the file upload for 'sss_proof'
        if ($request->hasFile('sss_proof')) {
            $file = $request->file('sss_proof');

            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('sss_files', $fileName, 'public');

                Log::info('File Uploaded', [
                    'file' => $fileName,
                    'path' => $filePath
                ]);

                $requirement->sss_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        // Update other fields
        $requirement->sss_final_status = $request->input('sss_final_status');
        $requirement->sss_submitted_date = $request->input('sss_submitted_date');
        $requirement->sss_remarks = $request->input('sss_remarks');
        $requirement->sss_number = $request->input('sss_number');
        $requirement->sss_proof_submitted_type = $request->input('sss_proof_submitted_type');
        $requirement->sss_updated_by = $request->input('sss_updated_by');
        $requirement->sss_last_updated_at = now();

        // Save the requirement model after processing all fields
        if ($requirement->save()) {
            Log::info('SSS Requirement updated successfully', ['id' => $id]);
            return response()->json(['success' => 'SSS Requirement updated successfully']);
        } else {
            Log::error('Failed to update SSS requirement', ['id' => $id]);
            return response()->json(['error' => 'Failed to update SSS requirement'], 500);
        }
    }
    public function updatePHIC(Request $request, $id)
    {
        Log::info('Update PHIC Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'phic_submitted_date' => 'nullable|date',
            'phic_final_status' => 'nullable|string',
            'phic_proof_submitted_type' => 'nullable|string',
            'phic_remarks' => 'nullable|string',
            'phic_number' => 'nullable|string',
            'phic_updated_by' => 'nullable|integer',
            'phic_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('phic_proof')) {
            $file = $request->file('phic_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('phic_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->phic_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->phic_submitted_date = $request->input('phic_submitted_date');
        $requirement->phic_final_status = $request->input('phic_final_status');
        $requirement->phic_proof_submitted_type = $request->input('phic_proof_submitted_type');
        $requirement->phic_remarks = $request->input('phic_remarks');
        $requirement->phic_number = $request->input('phic_number');
        $requirement->phic_updated_by = $request->input('phic_updated_by');
        $requirement->phic_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('PHIC Requirement updated successfully', ['id' => $id]);
            return response()->json(['success' => 'PHIC Requirement updated successfully']);
        } else {
            Log::error('Failed to update PHIC requirement', ['id' => $id]);
            return response()->json(['error' => 'Failed to update PHIC requirement'], 500);
        }
    }
    public function updatePagibig(Request $request, $id)
    {
        Log::info('Update Pagibig Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'pagibig_submitted_date' => 'nullable|date',
            'pagibig_final_status' => 'nullable|string',
            'pagibig_proof_submitted_type' => 'nullable|string',
            'pagibig_remarks' => 'nullable|string',
            'pagibig_number' => 'nullable|string',
            'pagibig_updated_by' => 'nullable|integer',
            'pagibig_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('pagibig_proof')) {
            $file = $request->file('pagibig_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('pagibig_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->pagibig_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->pagibig_submitted_date = $request->input('pagibig_submitted_date');
        $requirement->pagibig_final_status = $request->input('pagibig_final_status');
        $requirement->pagibig_proof_submitted_type = $request->input('pagibig_proof_submitted_type');
        $requirement->pagibig_remarks = $request->input('pagibig_remarks');
        $requirement->pagibig_number = $request->input('pagibig_number');
        $requirement->pagibig_updated_by = $request->input('pagibig_updated_by');
        $requirement->pagibig_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('Pagibig Requirement updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Pagibig Requirement updated successfully']);
        } else {
            Log::error('Failed to update Pagibig requirement', ['id' => $id]);
            return response()->json(['error' => 'Failed to update Pagibig requirement'], 500);
        }
    }
    public function updateTin(Request $request, $id)
    {
        Log::info('Update TIN Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'tin_submitted_date' => 'nullable|date',
            'tin_final_status' => 'nullable|string',
            'tin_proof_submitted_type' => 'nullable|string',
            'tin_remarks' => 'nullable|string',
            'tin_number' => 'nullable|string',
            'tin_updated_by' => 'nullable|integer',
            'tin_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('tin_proof')) {
            $file = $request->file('tin_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('tin_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->tin_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->tin_submitted_date = $request->input('tin_submitted_date');
        $requirement->tin_final_status = $request->input('tin_final_status');
        $requirement->tin_proof_submitted_type = $request->input('tin_proof_submitted_type');
        $requirement->tin_remarks = $request->input('tin_remarks');
        $requirement->tin_number = $request->input('tin_number');
        $requirement->tin_updated_by = $request->input('tin_updated_by');
        $requirement->tin_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('TIN Requirement updated successfully', ['id' => $id]);
            return response()->json(['success' => 'TIN Requirement updated successfully']);
        } else {
            Log::error('Failed to update TIN requirement', ['id' => $id]);
            return response()->json(['error' => 'Failed to update TIN requirement'], 500);
        }
    }

    public function updateHealthCertificate(Request $request, $id)
    {
        Log::info('Update Health Certificate Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'health_certificate_validity_date' => 'nullable|date',
            'health_certificate_final_status' => 'nullable|string',
            'health_certificate_submitted_date' => 'nullable|date',
            'health_certificate_remarks' => 'nullable|string',
            'health_certificate_updated_by' => 'nullable|integer',
            'health_certificate_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('health_certificate_proof')) {
            $file = $request->file('health_certificate_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('health_certificate_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->health_certificate_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }
        $requirement->health_certificate_final_status = $request->input('health_certificate_final_status');
        $requirement->health_certificate_validity_date = $request->input('health_certificate_validity_date');
        $requirement->health_certificate_submitted_date = $request->input('health_certificate_submitted_date');
        $requirement->health_certificate_remarks = $request->input('health_certificate_remarks');
        $requirement->health_certificate_updated_by = $request->input('health_certificate_updated_by');
        $requirement->health_certificate_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('Health Certificate updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Health Certificate updated successfully']);
        } else {
            Log::error('Failed to update Health Certificate', ['id' => $id]);
            return response()->json(['error' => 'Failed to update Health Certificate'], 500);
        }
    }
    public function updateOccupationalPermit(Request $request, $id)
    {
        Log::info('Update Occupational Permit Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'occupational_permit_validity_date' => 'nullable|date',
            'occupational_permit_submitted_date' => 'nullable|date',
            'occupational_permit_remarks' => 'nullable|string',
            'occupational_permit_final_status' => 'nullable|string',
            'occupational_permit_updated_by' => 'nullable|integer',
            'occupational_permit_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('occupational_permit_proof')) {
            $file = $request->file('occupational_permit_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('occupational_permit_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->occupational_permit_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->occupational_permit_validity_date = $request->input('occupational_permit_validity_date');
        $requirement->occupational_permit_submitted_date = $request->input('occupational_permit_submitted_date');
        $requirement->occupational_permit_remarks = $request->input('occupational_permit_remarks');
        $requirement->occupational_permit_final_status = $request->input('occupational_permit_final_status');
        $requirement->occupational_permit_updated_by = $request->input('occupational_permit_updated_by');
        $requirement->occupational_permit_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('Occupational Permit updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Occupational Permit updated successfully']);
        } else {
            Log::error('Failed to update Occupational Permit', ['id' => $id]);
            return response()->json(['error' => 'Failed to update Occupational Permit'], 500);
        }
    }
    public function updateOFAC(Request $request, $id)
    {
        Log::info('Update OFAC Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'ofac_checked_date' => 'nullable|date',
            'ofac_final_status' => 'nullable|string',
            'ofac_remarks' => 'nullable|string',
            'ofac_updated_by' => 'nullable|integer',
            'ofac_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('ofac_proof')) {
            $file = $request->file('ofac_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('ofac_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->ofac_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->ofac_checked_date = $request->input('ofac_checked_date');
        $requirement->ofac_final_status = $request->input('ofac_final_status');
        $requirement->ofac_remarks = $request->input('ofac_remarks');
        $requirement->ofac_updated_by = $request->input('ofac_updated_by');
        $requirement->ofac_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('OFAC updated successfully', ['id' => $id]);
            return response()->json(['success' => 'OFAC updated successfully']);
        } else {
            Log::error('Failed to update OFAC', ['id' => $id]);
            return response()->json(['error' => 'Failed to update OFAC'], 500);
        }
    }
    public function updateSAM(Request $request, $id)
    {
        Log::info('Update SAM Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'sam_checked_date' => 'nullable|date',
            'sam_final_status' => 'nullable|string',
            'sam_remarks' => 'nullable|string',
            'sam_updated_by' => 'nullable|integer',
            'sam_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('sam_proof')) {
            $file = $request->file('sam_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('sam_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->sam_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->sam_checked_date = $request->input('sam_checked_date');
        $requirement->sam_final_status = $request->input('sam_final_status');
        $requirement->sam_remarks = $request->input('sam_remarks');
        $requirement->sam_updated_by = $request->input('sam_updated_by');
        $requirement->sam_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('SAM updated successfully', ['id' => $id]);
            return response()->json(['success' => 'SAM updated successfully']);
        } else {
            Log::error('Failed to update SAM', ['id' => $id]);
            return response()->json(['error' => 'Failed to update SAM'], 500);
        }
    }
    public function updateOIG(Request $request, $id)
    {
        Log::info('Update OIG Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'oig_checked_date' => 'nullable|date',
            'oig_final_status' => 'nullable|string',
            'oig_remarks' => 'nullable|string',
            'oig_updated_by' => 'nullable|integer',
            'oig_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('oig_proof')) {
            $file = $request->file('oig_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('oig_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->oig_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->oig_checked_date = $request->input('oig_checked_date');
        $requirement->oig_final_status = $request->input('oig_final_status');
        $requirement->oig_remarks = $request->input('oig_remarks');
        $requirement->oig_updated_by = $request->input('oig_updated_by');
        $requirement->oig_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('OIG updated successfully', ['id' => $id]);
            return response()->json(['success' => 'OIG updated successfully']);
        } else {
            Log::error('Failed to update OIG', ['id' => $id]);
            return response()->json(['error' => 'Failed to update OIG'], 500);
        }
    }
    public function updateCIBI(Request $request, $id)
    {
        Log::info('Update CIBI Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'cibi_checked_date' => 'nullable|date',
            'cibi_final_status' => 'nullable|string',
            'cibi_remarks' => 'nullable|string',
            'cibi_updated_by' => 'nullable|integer',
            'cibi_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('cibi_proof')) {
            $file = $request->file('cibi_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('cibi_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->cibi_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->cibi_checked_date = $request->input('cibi_checked_date');
        $requirement->cibi_final_status = $request->input('cibi_final_status');
        $requirement->cibi_remarks = $request->input('cibi_remarks');
        $requirement->cibi_updated_by = $request->input('cibi_updated_by');
        $requirement->cibi_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('CIBI updated successfully', ['id' => $id]);
            return response()->json(['success' => 'CIBI updated successfully']);
        } else {
            Log::error('Failed to update CIBI', ['id' => $id]);
            return response()->json(['error' => 'Failed to update CIBI'], 500);
        }
    }
    public function updateBGC(Request $request, $id)
    {
        Log::info('Update BGC Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'bgc_endorsed_date' => 'nullable|date',
            'bgc_results_date' => 'nullable|date',
            'bgc_final_status' => 'nullable|string',
            'bgc_remarks' => 'nullable|string',
            'bgc_updated_by' => 'nullable|integer',
            'bgc_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('bgc_proof')) {
            $file = $request->file('bgc_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('bgc_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->bgc_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->bgc_endorsed_date = $request->input('bgc_endorsed_date');
        $requirement->bgc_results_date = $request->input('bgc_results_date');
        $requirement->bgc_final_status = $request->input('bgc_final_status');
        $requirement->bgc_remarks = $request->input('bgc_remarks');
        $requirement->bgc_updated_by = $request->input('bgc_updated_by');
        $requirement->bgc_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('BGC updated successfully', ['id' => $id]);
            return response()->json(['success' => 'BGC updated successfully']);
        } else {
            Log::error('Failed to update BGC', ['id' => $id]);
            return response()->json(['error' => 'Failed to update BGC'], 500);
        }
    }
    public function updateBirthCertificate(Request $request, $id)
    {
        Log::info('Update Birth Certificate Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'birth_certificate_submitted_date' => 'nullable|date',
            'birth_certificate_proof_type' => 'nullable|string',
            'birth_certificate_remarks' => 'nullable|string',
            'birth_certificate_updated_by' => 'nullable|integer',
            'birth_certificate_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('birth_certificate_proof')) {
            $file = $request->file('birth_certificate_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('birth_certificate_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->birth_certificate_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->birth_certificate_submitted_date = $request->input('birth_certificate_submitted_date');
        $requirement->birth_certificate_proof_type = $request->input('birth_certificate_proof_type');
        $requirement->birth_certificate_remarks = $request->input('birth_certificate_remarks');
        $requirement->birth_certificate_updated_by = $request->input('birth_certificate_updated_by');
        $requirement->birth_certificate_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('Birth Certificate updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Birth Certificate updated successfully']);
        } else {
            Log::error('Failed to update Birth Certificate', ['id' => $id]);
            return response()->json(['error' => 'Failed to update Birth Certificate'], 500);
        }
    }
    public function updateDependentBirthCertificate(Request $request, $id)
    {
        Log::info('Update Dependent Birth Certificate Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'dependent_birth_certificate_submitted_date' => 'nullable|date',
            'dependent_birth_certificate_proof_type' => 'nullable|string',
            'dependent_birth_certificate_remarks' => 'nullable|string',
            'dependent_birth_certificate_updated_by' => 'nullable|integer',
            'dependent_birth_certificate_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('dependent_birth_certificate_proof')) {
            $file = $request->file('dependent_birth_certificate_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('dependent_birth_certificate_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->dependent_birth_certificate_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->dependent_birth_certificate_submitted_date = $request->input('dependent_birth_certificate_submitted_date');
        $requirement->dependent_birth_certificate_proof_type = $request->input('dependent_birth_certificate_proof_type');
        $requirement->dependent_birth_certificate_remarks = $request->input('dependent_birth_certificate_remarks');
        $requirement->dependent_birth_certificate_updated_by = $request->input('dependent_birth_certificate_updated_by');
        $requirement->dependent_birth_certificate_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('Dependent Birth Certificate updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Dependent Birth Certificate updated successfully']);
        } else {
            Log::error('Failed to update Dependent Birth Certificate', ['id' => $id]);
            return response()->json(['error' => 'Failed to update Dependent Birth Certificate'], 500);
        }
    }
    public function updateMarriageCertificate(Request $request, $id)
    {
        Log::info('Update Marriage Certificate Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'marriage_certificate_submitted_date' => 'nullable|date',
            'marriage_certificate_proof_type' => 'nullable|string',
            'marriage_certificate_remarks' => 'nullable|string',
            'marriage_certificate_updated_by' => 'nullable|integer',
            'marriage_certificate_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('marriage_certificate_proof')) {
            $file = $request->file('marriage_certificate_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('marriage_certificate_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->marriage_certificate_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->marriage_certificate_submitted_date = $request->input('marriage_certificate_submitted_date');
        $requirement->marriage_certificate_proof_type = $request->input('marriage_certificate_proof_type');
        $requirement->marriage_certificate_remarks = $request->input('marriage_certificate_remarks');
        $requirement->marriage_certificate_updated_by = $request->input('marriage_certificate_updated_by');
        $requirement->marriage_certificate_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('Marriage Certificate updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Marriage Certificate updated successfully']);
        } else {
            Log::error('Failed to update Marriage Certificate', ['id' => $id]);
            return response()->json(['error' => 'Failed to update Marriage Certificate'], 500);
        }
    }
    public function updateScholasticRecord(Request $request, $id)
    {
        Log::info('Update Scholastic Record Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'scholastic_record_submitted_date' => 'nullable|date',
            'scholastic_record_proof_type' => 'nullable|string',
            'scholastic_record_remarks' => 'nullable|string',
            'scholastic_record_updated_by' => 'nullable|integer',
            'scholastic_record_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('scholastic_record_proof')) {
            $file = $request->file('scholastic_record_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('scholastic_record_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->scholastic_record_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->scholastic_record_submitted_date = $request->input('scholastic_record_submitted_date');
        $requirement->scholastic_record_proof_type = $request->input('scholastic_record_proof_type');
        $requirement->scholastic_record_remarks = $request->input('scholastic_record_remarks');
        $requirement->scholastic_record_updated_by = $request->input('scholastic_record_updated_by');
        $requirement->scholastic_record_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('Scholastic Record updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Scholastic Record updated successfully']);
        } else {
            Log::error('Failed to update Scholastic Record', ['id' => $id]);
            return response()->json(['error' => 'Failed to update Scholastic Record'], 500);
        }
    }
    public function updatePreviousEmployment(Request $request, $id)
    {
        Log::info('Update Previous Employment Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'previous_employment_submitted_date' => 'nullable|date',
            'previous_employment_proof_type' => 'nullable|string',
            'previous_employment_remarks' => 'nullable|string',
            'previous_employment_updated_by' => 'nullable|integer',
            'previous_employment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('previous_employment_proof')) {
            $file = $request->file('previous_employment_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('previous_employment_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->previous_employment_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->previous_employment_submitted_date = $request->input('previous_employment_submitted_date');
        $requirement->previous_employment_proof_type = $request->input('previous_employment_proof_type');
        $requirement->previous_employment_remarks = $request->input('previous_employment_remarks');
        $requirement->previous_employment_updated_by = $request->input('previous_employment_updated_by');
        $requirement->previous_employment_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('Previous Employment updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Previous Employment updated successfully']);
        } else {
            Log::error('Failed to update Previous Employment', ['id' => $id]);
            return response()->json(['error' => 'Failed to update Previous Employment'], 500);
        }
    }
    public function updateSupportingDocuments(Request $request, $id)
    {
        Log::info('Update Supporting Documents Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'supporting_documents_submitted_date' => 'nullable|date',
            'supporting_documents_proof_type' => 'nullable|string',
            'supporting_documents_remarks' => 'nullable|string',
            'supporting_documents_updated_by' => 'nullable|integer',
            'supporting_documents_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ])->validate();

        $requirement = Requirements::where('employee_tbl_id', $id)->first();

        if (!$requirement) {
            Log::error('Requirement not found', ['id' => $id]);
            return response()->json(['error' => 'Requirement not found'], 404);
        }

        if ($request->hasFile('supporting_documents_proof')) {
            $file = $request->file('supporting_documents_proof');
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('supporting_documents_files', $fileName, 'public');

                Log::info('File Uploaded', ['file' => $fileName, 'path' => $filePath]);
                $requirement->supporting_documents_file_name = $fileName;
            } else {
                Log::error('File is not valid', ['file' => $file]);
            }
        }

        $requirement->supporting_documents_submitted_date = $request->input('supporting_documents_submitted_date');
        $requirement->supporting_documents_proof_type = $request->input('supporting_documents_proof_type');
        $requirement->supporting_documents_remarks = $request->input('supporting_documents_remarks');
        $requirement->supporting_documents_updated_by = $request->input('supporting_documents_updated_by');
        $requirement->supporting_documents_last_updated_at = now();

        if ($requirement->save()) {
            Log::info('Supporting Documents updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Supporting Documents updated successfully']);
        } else {
            Log::error('Failed to update Supporting Documents', ['id' => $id]);
            return response()->json(['error' => 'Failed to update Supporting Documents'], 500);
        }
    }
    public function updateLob(Request $request, $id)
    {
        Log::info('Update Lob Request Received', ['id' => $id, 'data' => $request->all()]);

        $validatedData = Validator::make($request->all(), [
            'region' => 'nullable|string',
            'site' => 'nullable|string',
            'lob' => 'nullable|string',
            'team_name' => 'nullable|string',
            'project_code' => 'nullable|string',
            'updated_by' => 'required|integer', // Ensure `updated_by` is passed and is an integer
        ])->validate();

        $lob = Lob::where('employee_tbl_id', $id)->first();

        if (!$lob) {
            Log::error('Lob not found', ['id' => $id]);
            return response()->json(['error' => 'Lob not found'], 404);
        }

        try {
            // Update the Lob with new data and metadata
            $lob->update([
                'region' => $validatedData['region'] ?? $lob->region,
                'site' => $validatedData['site'] ?? $lob->site,
                'lob' => $validatedData['lob'] ?? $lob->lob,
                'team_name' => $validatedData['team_name'] ?? $lob->team_name,
                'project_code' => $validatedData['project_code'] ?? $lob->project_code,
                'updated_by' => $validatedData['updated_by'],
                'lob_updated_at' => now(),
            ]);

            Log::info('Lob successfully updated', ['id' => $id]);
            return response()->json(['message' => 'Lob updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error updating Lob', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update Lob'], 500);
        }
    }
}
