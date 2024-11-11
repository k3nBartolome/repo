<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeImport;
use App\Models\Employee;
use App\Models\Requirements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
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
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $employee_info = new Employee();
        $employee_info->fill($request->all());
        $employee_info->save();

        return response()->json([
            'employee' => $employee_info,
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

    public function update(Request $request, $id)
    {
        $requirement = Requirements::find($id);
        if (!$requirement) {
            return response()->json(['error' => 'Requirement not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'employee_tbl_id' => 'required|exists:employees,employee_id',
            'nbi' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'nbi_remarks' => 'nullable|string',
            'nbi_validity_date' => 'nullable|date',
            'nbi_printed_date' => 'nullable|date',
            'dt_results' => 'nullable|string',
            'dt_transaction_date' => 'nullable|date',
            'dt_result_date' => 'nullable|date',
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
                if ($requirement->{$field . '_path'}) {
                    Storage::disk('public')->delete($requirement->{$field . '_path'});
                }
                $imagePath = $request->file($field)->store('uploads/requirements', 'public');
                $validatedData[$field . '_path'] = $imagePath;
                $validatedData[$field . '_file_name'] = $request->file($field)->getClientOriginalName();
            }
        }
        $requirement->update($validatedData);

        return response()->json([
            'requirements' => $requirement,
        ]);
    }

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
            'dt_result_date' => 'nullable|date',
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
}
