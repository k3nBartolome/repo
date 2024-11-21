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

class EmployeeController extends Controller
{
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
        // Validate the request to ensure the QR code file is uploaded
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|image|mimes:png,jpg,jpeg,gif',  // Image validation (you can adjust the accepted types)
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid image file.',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Find the employee by ID
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found.',
            ], 404);
        }

        // Store the uploaded QR code image in the 'public/qr_codes' directory
        $path = $request->file('qr_code')->store('qr_codes', 'public');

        // Save the path of the uploaded file in the qr_code_path column
        $employee->qr_code_path = $path;
        $employee->save();

        return response()->json([
            'status' => 'success',
            'message' => 'QR code saved successfully.',
            'employee' => $employee,
        ], 200);
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
            'employment_status' => 'nullable',
            'employee_added_by' => 'nullable|integer',
            'lob_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create the employee
        $employee_info = new Employee();
        $employee_info->fill($request->all());
        $employee_info->save();

        // Get the employee ID
        $employee_id = $employee_info->id;

        // Create the requirement and associate with the employee
        $requirement = new Requirements();
        $requirement->employee_tbl_id = $employee_id;  // Assign employee ID to the requirement
        $requirement->save();

        // Create the lob and associate with the employee
        $lob = new Lob();
        $lob->employee_tbl_id = $employee_id;  // Assign employee ID to the lob
        $lob->save();

        return response()->json([
            'employee' => $employee_info,
            'requirement' => $requirement,
            'lob' => $lob,
        ]);
    }


    public function index(Request $request)
    {
        // Fetch employee information with pagination
        $employee_info = Employee::paginate(10);

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
                'first_page' => $employee_info->url(1),
                'last_page' => $employee_info->url($employee_info->lastPage()),
                'next_page' => $employee_info->nextPageUrl(),
                'prev_page' => $employee_info->previousPageUrl(),
                'per_page' => $employee_info->perPage(),
                'total_pages' => $employee_info->lastPage(),
            ],
        ]);
    }

    public function show($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        // Return employee data as JSON
        return response()->json(['employee' => $employee]);
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
    public function update(Request $request, $id)
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
        $requirement->nbi_updated_by = now();

        // Save the requirement model after processing all fields
        if ($requirement->save()) {
            Log::info('Requirement updated successfully', ['id' => $id]);
            return response()->json(['success' => 'Requirement updated successfully']);
        } else {
            Log::error('Failed to update requirement', ['id' => $id]);
            return response()->json(['error' => 'Failed to update requirement'], 500);
        }
    }
}
