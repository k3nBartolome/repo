public function updateRecords(Request $request, $id)
{
    Log::info('Update Request Received', ['id' => $id, 'data' => $request->all()]);

    try {
        // Attempt to find the Employee record
        $employee = Employee::find($id);
        if ($employee) {
            $validatedData = $request->validate([
                '_first_name' => 'nullable|string|max:255',
                '_middle_name' => 'nullable|string|max:255',
                '_last_name' => 'nullable|string|max:255',
                '_position' => 'nullable|string|max:255',
                '_account_type' => 'nullable|string|max:255',
                '_employee_id' => 'required|string|max:255',
                '_contact_number' => 'nullable|string|max:15',
                '_email_address' => 'nullable|email|max:255',
                '_birth_date' => 'nullable|date',
                '_hired_date' => 'nullable|date',
                '_employee_status' => 'nullable|string|max:255',
                '_employment_status' => 'nullable|string|max:255',
                '_hired_month' => 'nullable|string|max:50',
                '_updated_by' => 'nullable|string|max:50',
            ]);

            $employee->update([
                'first_name' => $validatedData['_first_name'],
                'middle_name' => $validatedData['_middle_name'] ?? null,
                'last_name' => $validatedData['_last_name'],
                'account_associate' => $validatedData['_position'] ?? null,
                'account_type' => $validatedData['_account_type'] ?? null,
                'employee_id' => $validatedData['_employee_id'],
                'contact_number' => $validatedData['_contact_number'] ?? null,
                'email' => $validatedData['_email_address'] ?? null,
                'birthdate' => $validatedData['_birth_date'] ?? null,
                'hired_date' => $validatedData['_hired_date'] ?? null,
                'employee_status' => $validatedData['_employee_status'] ?? null,
                'employment_status' => $validatedData['_employment_status'] ?? null,
                'hired_month' => $validatedData['_hired_month'] ?? null,
                'updated_by' => $validatedData['_updated_by'] ?? null,
                'updated_at' => now(),
            ]);
        }

        // Attempt to find Requirements record
        $requirement = Requirements::where('employee_tbl_id', $id)->first();
        if ($requirement) {
            $validatedData = $request->validate([
                'nbi_final_status' => 'nullable|string',
                'nbi_validity_date' => 'nullable|date',
                'nbi_submitted_date' => 'nullable|date',
                'nbi_printed_date' => 'nullable|date',
                'nbi_remarks' => 'nullable|string',
                'nbi_updated_by' => 'nullable|integer',
                'dt_final_status' => 'nullable|string',
                'dt_results_date' => 'nullable|date',
                'dt_transaction_date' => 'nullable|date',
                'dt_endorsed_date' => 'nullable|date',
                'dt_remarks' => 'nullable|string',
                'dt_updated_by' => 'nullable|integer',
                'nbi_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'dt_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            if ($request->hasFile('nbi_proof')) {
                $file = $request->file('nbi_proof');
                if ($file->isValid()) {
                    $fileName = time().'_'.$file->getClientOriginalName();
                    $filePath = $file->storeAs('nbi_files', $fileName, 'public');
                    $requirement->nbi_file_name = $fileName;
                }
            }

            if ($request->hasFile('dt_proof')) {
                $file = $request->file('dt_proof');
                if ($file->isValid()) {
                    $fileName = time().'_'.$file->getClientOriginalName();
                    $filePath = $file->storeAs('dt_files', $fileName, 'public');
                    $requirement->dt_file_name = $fileName;
                }
            }

            $requirement->update([
                'nbi_final_status' => $validatedData['nbi_final_status'] ?? null,
                'nbi_validity_date' => $validatedData['nbi_validity_date'] ?? null,
                'nbi_submitted_date' => $validatedData['nbi_submitted_date'] ?? null,
                'nbi_printed_date' => $validatedData['nbi_printed_date'] ?? null,
                'nbi_remarks' => $validatedData['nbi_remarks'] ?? null,
                'nbi_updated_by' => $validatedData['nbi_updated_by'] ?? null,
                'dt_final_status' => $validatedData['dt_final_status'] ?? null,
                'dt_results_date' => $validatedData['dt_results_date'] ?? null,
                'dt_transaction_date' => $validatedData['dt_transaction_date'] ?? null,
                'dt_endorsed_date' => $validatedData['dt_endorsed_date'] ?? null,
                'dt_remarks' => $validatedData['dt_remarks'] ?? null,
                'dt_updated_by' => $validatedData['dt_updated_by'] ?? null,
                'nbi_last_updated_at' => now(),
                'dt_last_updated_at' => now(),
            ]);
        }

        // Attempt to find Lob record
        $lob = Lob::where('employee_tbl_id', $id)->first();
        if ($lob) {
            $validatedData = $request->validate([
                'region' => 'nullable|string',
                'site' => 'nullable|integer',
                'lob' => 'nullable|string',
                'team_name' => 'nullable|string',
                'project_code' => 'nullable|string',
                'compliance_poc' => 'nullable|string',
                'updated_by' => 'required|integer',
            ]);

            $lob->update([
                'region' => $validatedData['region'] ?? $lob->region,
                'site' => $validatedData['site'] ?? $lob->site,
                'lob' => $validatedData['lob'] ?? $lob->lob,
                'team_name' => $validatedData['team_name'] ?? $lob->team_name,
                'project_code' => $validatedData['project_code'] ?? $lob->project_code,
                'compliance_poc' => $validatedData['compliance_poc'] ?? $lob->compliance_poc,
                'updated_by' => $validatedData['updated_by'],
                'lob_updated_at' => now(),
            ]);
        }

        // Attempt to find Workday record
        $workday = Workday::where('employee_tbl_id', $id)->first();
        if ($workday) {
            $validatedData = $request->validate([
                'workday_id' => 'nullable|string',
                'ro_feedback' => 'nullable|string',
                'per_findings' => 'nullable|string',
                'completion' => 'nullable|string',
                'contract_findings' => 'nullable|string',
                'contract_remarks' => 'nullable|string',
                'contract_status' => 'nullable|string',
                'updated_by' => 'required|integer',
            ]);

            $workday->update([
                'workday_id' => $validatedData['workday_id'] ?? $workday->workday_id,
                'ro_feedback' => $validatedData['ro_feedback'] ?? $workday->ro_feedback,
                'per_findings' => $validatedData['per_findings'] ?? $workday->per_findings,
                'completion' => $validatedData['completion'] ?? $workday->completion,
                'contract_status' => $validatedData['contract_status'] ?? $workday->contract_status,
                'updated_by' => $validatedData['updated_by'],
                'workday_updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Record(s) updated successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to update records', 'details' => $e->getMessage()], 500);
    }
}
