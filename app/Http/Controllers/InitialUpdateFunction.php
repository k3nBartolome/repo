public function updateEmployee(Request $request, $id)
{
Log::info('Update Request Received', ['id' => $id, 'data' => $request->all()]);

try {
// Attempt to find the Employee record
$employee = Employee::find($id);
if ($employee) {
$validatedData = $request->validate([
'first_name' => 'nullable|string|max:255',
'middle_name' => 'nullable|string|max:255',
'last_name' => 'nullable|string|max:255',
'account_associate' => 'nullable|string|max:255',
'account_type' => 'nullable|string|max:255',
'employee_id' => 'required|string|max:255',
'contact_number' => 'nullable|string|max:15',
'email' => 'nullable|email|max:255',
'birthdate' => 'nullable|date',
'hired_date' => 'nullable|date',
'employee_status' => 'nullable|string|max:255',
'employment_status' => 'nullable|string|max:255',
'hired_month' => 'nullable|string|max:50',
'updated_by' => 'nullable|string|max:50',
]);

$employee->update([
'first_name' => $validatedData['first_name'],
'middle_name' => $validatedData['middle_name'] ?? null,
'last_name' => $validatedData['last_name'],
'account_associate' => $validatedData['account_associate'] ?? null,
'account_type' => $validatedData['account_type'] ?? null,
'employee_id' => $validatedData['employee_id'],
'contact_number' => $validatedData['contact_number'] ?? null,
'email' => $validatedData['email'] ?? null,
'birthdate' => $validatedData['birthdate'] ?? null,
'hired_date' => $validatedData['hired_date'] ?? null,
'employee_status' => $validatedData['employee_status'] ?? null,
'employment_status' => $validatedData['employment_status'] ?? null,
'hired_month' => $validatedData['hired_month'] ?? null,
'updated_by' => $validatedData['updated_by'] ?? null,
'updated_at' => now(),
]);
}
$requirement = Requirements::where('employee_tbl_id', $id)->first();
if ($requirement) {
$validatedData = $request->validate([
'nbi_final_status' => 'nullable|string',
'nbi_validity_date' => 'nullable|date',
'nbi_submitted_date' => 'nullable|date',
'nbi_printed_date' => 'nullable|date',
'nbi_remarks' => 'nullable|string',
'nbi_updated_by' => 'nullable|integer',
'nbi_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max file size: 2MB
'dt_final_status' => 'nullable|string',
'dt_results_date' => 'nullable|date',
'dt_transaction_date' => 'nullable|date',
'dt_endorsed_date' => 'nullable|date',
'dt_remarks' => 'nullable|string',
'dt_updated_by' => 'nullable|integer',
'dt_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'peme_final_status' => 'nullable|string',
'peme_results_date' => 'nullable|date',
'peme_transaction_date' => 'nullable|date',
'peme_endorsed_date' => 'nullable|date',
'peme_remarks' => 'nullable|string',
'peme_updated_by' => 'nullable|integer',
'peme_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'sss_final_status' => 'nullable|string',
'sss_submitted_date' => 'nullable|date',
'sss_remarks' => 'nullable|string',
'sss_number' => 'nullable|string',
'sss_proof_submitted_type' => 'nullable|string',
'sss_updated_by' => 'nullable|integer',
'sss_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'phic_submitted_date' => 'nullable|date',
'phic_final_status' => 'nullable|string',
'phic_proof_submitted_type' => 'nullable|string',
'phic_remarks' => 'nullable|string',
'phic_number' => 'nullable|string',
'phic_updated_by' => 'nullable|integer',
'phic_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'pagibig_submitted_date' => 'nullable|date',
'pagibig_final_status' => 'nullable|string',
'pagibig_proof_submitted_type' => 'nullable|string',
'pagibig_remarks' => 'nullable|string',
'pagibig_number' => 'nullable|string',
'pagibig_updated_by' => 'nullable|integer',
'pagibig_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'tin_submitted_date' => 'nullable|date',
'tin_final_status' => 'nullable|string',
'tin_proof_submitted_type' => 'nullable|string',
'tin_remarks' => 'nullable|string',
'tin_number' => 'nullable|string',
'tin_updated_by' => 'nullable|integer',
'tin_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'health_certificate_validity_date' => 'nullable|date',
'health_certificate_final_status' => 'nullable|string',
'health_certificate_submitted_date' => 'nullable|date',
'health_certificate_remarks' => 'nullable|string',
'health_certificate_updated_by' => 'nullable|integer',
'health_certificate_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'occupational_permit_validity_date' => 'nullable|date',
'occupational_permit_submitted_date' => 'nullable|date',
'occupational_permit_remarks' => 'nullable|string',
'occupational_permit_final_status' => 'nullable|string',
'occupational_permit_updated_by' => 'nullable|integer',
'occupational_permit_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'ofac_checked_date' => 'nullable|date',
'ofac_final_status' => 'nullable|string',
'ofac_remarks' => 'nullable|string',
'ofac_updated_by' => 'nullable|integer',
'ofac_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'sam_checked_date' => 'nullable|date',
'sam_final_status' => 'nullable|string',
'sam_remarks' => 'nullable|string',
'sam_updated_by' => 'nullable|integer',
'sam_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'oig_checked_date' => 'nullable|date',
'oig_final_status' => 'nullable|string',
'oig_remarks' => 'nullable|string',
'oig_updated_by' => 'nullable|integer',
'oig_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'cibi_checked_date' => 'nullable|date',
'cibi_final_status' => 'nullable|string',
'cibi_remarks' => 'nullable|string',
'cibi_updated_by' => 'nullable|integer',
'cibi_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'bgc_endorsed_date' => 'nullable|date',
'bgc_results_date' => 'nullable|date',
'bgc_final_status' => 'nullable|string',
'bgc_remarks' => 'nullable|string',
'bgc_updated_by' => 'nullable|integer',
'bgc_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'birth_certificate_submitted_date' => 'nullable|date',
'birth_certificate_proof_type' => 'nullable|string',
'birth_certificate_remarks' => 'nullable|string',
'birth_certificate_updated_by' => 'nullable|integer',
'birth_certificate_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'dependent_birth_certificate_submitted_date' => 'nullable|date',
'dependent_birth_certificate_proof_type' => 'nullable|string',
'dependent_birth_certificate_remarks' => 'nullable|string',
'dependent_birth_certificate_updated_by' => 'nullable|integer',
'dependent_birth_certificate_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'marriage_certificate_submitted_date' => 'nullable|date',
'marriage_certificate_proof_type' => 'nullable|string',
'marriage_certificate_remarks' => 'nullable|string',
'marriage_certificate_updated_by' => 'nullable|integer',
'marriage_certificate_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'scholastic_record_submitted_date' => 'nullable|date',
'scholastic_record_proof_type' => 'nullable|string',
'scholastic_record_remarks' => 'nullable|string',
'scholastic_record_updated_by' => 'nullable|integer',
'scholastic_record_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'previous_employment_submitted_date' => 'nullable|date',
'previous_employment_proof_type' => 'nullable|string',
'previous_employment_remarks' => 'nullable|string',
'previous_employment_updated_by' => 'nullable|integer',
'previous_employment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
'supporting_documents_submitted_date' => 'nullable|date',
'supporting_documents_proof_type' => 'nullable|string',
'supporting_documents_remarks' => 'nullable|string',
'supporting_documents_updated_by' => 'nullable|integer',
'supporting_documents_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
]);

if ($request->hasFile('nbi_proof')) {
$file = $request->file('nbi_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('nbi_files', $fileName, 'public');
$requirement->nbi_file_name = $fileName;
}
}
if ($request->hasFile('dt_proof')) {
$file = $request->file('dt_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('dt_files', $fileName, 'public');
$requirement->dt_file_name = $fileName;
}
}
if ($request->hasFile('peme_proof')) {
$file = $request->file('peme_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('peme_files', $fileName, 'public');
$requirement->peme_file_name = $fileName;
}
}
if ($request->hasFile('sss_proof')) {
$file = $request->file('sss_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('sss_files', $fileName, 'public');
$requirement->sss_file_name = $fileName;
}
}
if ($request->hasFile('phic_proof')) {
$file = $request->file('phic_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('phic_files', $fileName, 'public');
$requirement->phic_file_name = $fileName;
}
}
if ($request->hasFile('pagibig_proof')) {
$file = $request->file('pagibig_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('pagibig_files', $fileName, 'public');
$requirement->pagibig_file_name = $fileName;
}
}
if ($request->hasFile('tin_proof')) {
$file = $request->file('tin_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('tin_files', $fileName, 'public');
$requirement->tin_file_name = $fileName;
}
}
if ($request->hasFile('health_certificate_proof')) {
$file = $request->file('health_certificate_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('health_certificate_files', $fileName, 'public');
$requirement->health_certificate_file_name = $fileName;
}
}
if ($request->hasFile('occupational_permit_proof')) {
$file = $request->file('occupational_permit_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('occupational_permit_files', $fileName, 'public');
$requirement->occupational_permit_file_name = $fileName;
}
}
if ($request->hasFile('ofac_proof')) {
$file = $request->file('ofac_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('ofac_files', $fileName, 'public');
$requirement->ofac_file_name = $fileName;
}
}
if ($request->hasFile('sam_proof')) {
$file = $request->file('sam_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('sam_files', $fileName, 'public');
$requirement->sam_file_name = $fileName;
}
}
if ($request->hasFile('oig_proof')) {
$file = $request->file('oig_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('oig_files', $fileName, 'public');
$requirement->oig_file_name = $fileName;
}
}
if ($request->hasFile('cibi_proof')) {
$file = $request->file('cibi_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('cibi_files', $fileName, 'public');
$requirement->cibi_file_name = $fileName;
}
}
if ($request->hasFile('bgc_proof')) {
$file = $request->file('bgc_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('bgc_files', $fileName, 'public');
$requirement->bgc_file_name = $fileName;
}
}
if ($request->hasFile('birth_certificate_proof')) {
$file = $request->file('birth_certificate_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('birth_certificate_files', $fileName, 'public');
$requirement->birth_certificate_file_name = $fileName;
}
}
if ($request->hasFile('dependent_birth_certificate_proof')) {
$file = $request->file('dependent_birth_certificate_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('dependent_birth_certificate_files', $fileName, 'public');
$requirement->dependent_birth_certificate_file_name = $fileName;
}
}
if ($request->hasFile('marriage_certificate_proof')) {
$file = $request->file('marriage_certificate_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('marriage_certificate_files', $fileName, 'public');
$requirement->marriage_certificate_file_name = $fileName;
}
}
if ($request->hasFile('scholastic_record_proof')) {
$file = $request->file('scholastic_record_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('scholastic_record_files', $fileName, 'public');
$requirement->scholastic_record_file_name = $fileName;
}
}
if ($request->hasFile('previous_employment_proof')) {
$file = $request->file('previous_employment_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('previous_employment_files', $fileName, 'public');
$requirement->previous_employment_file_name = $fileName;
}
}
if ($request->hasFile('supporting_documents_proof')) {
$file = $request->file('supporting_documents_proof');
if ($file->isValid()) {
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = $file->storeAs('supporting_documents_files', $fileName, 'public');
$requirement->supporting_documents_file_name = $fileName;
}
}

$requirement->update([
'nbi_final_status'['nbi_final_status'] ?? null,
'nbi_validity_date' => $validateData['nbi_validity_date'] ?? null,
'nbi_submitted_date' => $validateData['nbi_submitted_date'] ?? null,
'nbi_printed_date' => $validateData['nbi_printed_date'] ?? null,
'nbi_remarks' => $validateData['nbi_remarks'] ?? null,
'nbi_updated_by' => $validateData['nbi_updated_by'] ?? null,
'dt_final_status' => $validateData['dt_final_status'] ?? null,
'dt_results_date' => $validateData['dt_results_date'] ?? null,
'dt_transaction_date' => $validateData['dt_transaction_date'] ?? null,
'dt_endorsed_date' => $validateData['dt_endorsed_date'] ?? null,
'dt_remarks' => $validateData['dt_remarks'] ?? null,
'dt_updated_by' => $validateData['dt_updated_by'] ?? null,
'peme_final_status' => $validateData['peme_final_status'] ?? null,
'peme_results_date' => $validateData['peme_results_date'] ?? null,
'peme_transaction_date' => $validateData['peme_transaction_date'] ?? null,
'peme_endorsed_date' => $validateData['peme_endorsed_date'] ?? null,
'peme_remarks' => $validateData['peme_remarks'] ?? null,
'peme_updated_by' => $validateData['peme_updated_by'] ?? null,
'sss_final_status' => $validateData['sss_final_status'] ?? null,
'sss_submitted_date' => $validateData['sss_submitted_date'] ?? null,
'sss_remarks' => $validateData['sss_remarks'] ?? null,
'sss_number' => $validateData['sss_number'] ?? null,
'sss_proof_submitted_type' => $validateData['sss_proof_submitted_type'] ?? null,
'sss_updated_by' => $validateData['sss_updated_by'] ?? null,
'phic_submitted_date' => $validateData['phic_submitted_date'] ?? null,
'phic_final_status' => $validateData['phic_final_status'] ?? null,
'phic_proof_submitted_type' => $validateData['phic_proof_submitted_type'] ?? null,
'phic_remarks' => $validateData['phic_remarks'] ?? null,
'phic_number' => $validateData['phic_number'] ?? null,
'phic_updated_by' => $validateData['phic_updated_by'] ?? null,
'pagibig_submitted_date' => $validateData['pagibig_submitted_date'] ?? null,
'pagibig_final_status' => $validateData['pagibig_final_status'] ?? null,
'pagibig_proof_submitted_type' => $validateData['pagibig_proof_submitted_type'] ?? null,
'pagibig_remarks' => $validateData['pagibig_remarks'] ?? null,
'pagibig_number' => $validateData['pagibig_number'] ?? null,
'pagibig_updated_by' => $validateData['pagibig_updated_by'] ?? null,
'tin_submitted_date' => $validateData['tin_submitted_date'] ?? null,
'tin_final_status' => $validateData['tin_final_status'] ?? null,
'tin_proof_submitted_type' => $validateData['tin_proof_submitted_type'] ?? null,
'tin_remarks' => $validateData['tin_remarks'] ?? null,
'tin_number' => $validateData['tin_number'] ?? null,
'tin_updated_by' => $validateData['tin_updated_by'] ?? null,
'health_certificate_validity_date' => $validateData['health_certificate_validity_date'] ?? null,
'health_certificate_final_status' => $validateData['health_certificate_final_status'] ?? null,
'health_certificate_submitted_date' => $validateData['health_certificate_submitted_date'] ?? null,
'health_certificate_remarks' => $validateData['health_certificate_remarks'] ?? null,
'health_certificate_updated_by' => $validateData['health_certificate_updated_by'] ?? null,
'occupational_permit_validity_date' => $validateData['occupational_permit_validity_date'] ?? null,
'occupational_permit_submitted_date' => $validateData['occupational_permit_submitted_date'] ?? null,
'occupational_permit_remarks' => $validateData['occupational_permit_remarks'] ?? null,
'occupational_permit_final_status' => $validateData['occupational_permit_final_status'] ?? null,
'occupational_permit_updated_by' => $validateData['occupational_permit_updated_by'] ?? null,
'ofac_checked_date' => $validateData['ofac_checked_date'] ?? null,
'ofac_final_status' => $validateData['ofac_final_status'] ?? null,
'ofac_remarks' => $validateData['ofac_remarks'] ?? null,
'ofac_updated_by' => $validateData['ofac_updated_by'] ?? null,
'sam_checked_date' => $validateData['sam_checked_date'] ?? null,
'sam_final_status' => $validateData['sam_final_status'] ?? null,
'sam_remarks' => $validateData['sam_remarks'] ?? null,
'sam_updated_by' => $validateData['sam_updated_by'] ?? null,
'oig_checked_date' => $validateData['oig_checked_date'] ?? null,
'oig_final_status' => $validateData['oig_final_status'] ?? null,
'oig_remarks' => $validateData['oig_remarks'] ?? null,
'oig_updated_by' => $validateData['oig_updated_by'] ?? null,
'cibi_checked_date' => $validateData['cibi_checked_date'] ?? null,
'cibi_final_status' => $validateData['cibi_final_status'] ?? null,
'cibi_remarks' => $validateData['cibi_remarks'] ?? null,
'cibi_updated_by' => $validateData['cibi_updated_by'] ?? null,
'bgc_endorsed_date' => $validateData['bgc_endorsed_date'] ?? null,
'bgc_results_date' => $validateData['bgc_results_date'] ?? null,
'bgc_final_status' => $validateData['bgc_final_status'] ?? null,
'bgc_remarks' => $validateData['bgc_remarks'] ?? null,
'bgc_updated_by' => $validateData['bgc_updated_by'] ?? null,
'birth_certificate_submitted_date' => $validateData['birth_certificate_submitted_date'] ?? null,
'birth_certificate_proof_type' => $validateData['birth_certificate_proof_type'] ?? null,
'birth_certificate_remarks' => $validateData['birth_certificate_remarks'] ?? null,
'birth_certificate_updated_by' => $validateData['birth_certificate_updated_by'] ?? null,
'dependent_birth_certificate_submitted_date' => $validateData['dependent_birth_certificate_submitted_date'] ?? null,
'dependent_birth_certificate_proof_type' => $validateData['dependent_birth_certificate_proof_type'] ?? null,
'dependent_birth_certificate_remarks' => $validateData['dependent_birth_certificate_remarks'] ?? null,
'dependent_birth_certificate_updated_by' => $validateData['dependent_birth_certificate_updated_by'] ?? null,
'marriage_certificate_submitted_date' => $validateData['marriage_certificate_submitted_date'] ?? null,
'marriage_certificate_proof_type' => $validateData['marriage_certificate_proof_type'] ?? null,
'marriage_certificate_remarks' => $validateData['marriage_certificate_remarks'] ?? null,
'marriage_certificate_updated_by' => $validateData['marriage_certificate_updated_by'] ?? null,
'scholastic_record_submitted_date' => $validateData['scholastic_record_submitted_date'] ?? null,
'scholastic_record_proof_type' => $validateData['scholastic_record_proof_type'] ?? null,
'scholastic_record_remarks' => $validateData['scholastic_record_remarks'] ?? null,
'scholastic_record_updated_by' => $validateData['scholastic_record_updated_by'] ?? null,
'previous_employment_submitted_date' => $validateData['previous_employment_submitted_date'] ?? null,
'previous_employment_proof_type' => $validateData['previous_employment_proof_type'] ?? null,
'previous_employment_remarks' => $validateData['previous_employment_remarks'] ?? null,
'previous_employment_updated_by' => $validateData['previous_employment_updated_by'] ?? null,
'supporting_documents_submitted_date' => $validateData['supporting_documents_submitted_date'] ?? null,
'supporting_documents_proof_type' => $validateData['supporting_documents_proof_type'] ?? null,
'supporting_documents_remarks' => $validateData['supporting_documents_remarks'] ?? null,
'supporting_documents_updated_by' => $validateData['supporting_documents_updated_by'] ?? null,
'nbi_last_updated_at' => now(),
'dt_last_updated_at' => now(),
'peme_last_updated_at' => now(),
'sss_last_updated_at' => now(),
'phic_last_updated_at' => now(),
'pagibig_last_updated_at' => now(),
'tin_last_updated_at' => now(),
'health_certificate_last_updated_at' => now(),
'occupational_permit_last_updated_at' => now(),
'ofac_last_updated_at' => now(),
'sam_last_updated_at' => now(),
'oig_last_updated_at' => now(),
'cibi_last_updated_at' => now(),
'bgc_last_updated_at' => now(),
'birth_certificate_last_updated_at' => now(),
'dependent_birth_certificate_last_updated_at' => now(),
'marriage_certificate_last_updated_at' => now(),
'scholastic_record_last_updated_at' => now(),
'previous_employment_last_updated_at' => now(),
'supporting_documents_last_updated_at' => now(),
]);
}
$lob = Lob::where('employee_tbl_id', $id)->first();
if ($lob) {
$validatedData = $request->validate([
'region' => 'nullable',
'site' => 'nullable',
'lob' => 'nullable',
'team_name' => 'nullable',
'project_code' => 'nullable',
'compliance_poc' => 'nullable',
'lob_updated_by' => 'required',
]);

$lob->update([
'region' => $validatedData['region'] ?? $lob->region,
'site' => $validatedData['site'] ?? $lob->site,
'lob' => $validatedData['lob'] ?? $lob->lob,
'team_name' => $validatedData['team_name'] ?? $lob->team_name,
'project_code' => $validatedData['project_code'] ?? $lob->project_code,
'compliance_poc' => $validatedData['compliance_poc'] ?? $lob->compliance_poc,
'lob_updated_by' => $validatedData['lob_updated_by'],
'updated_at' => now(),
]);
}
$workday = Workday::where('employee_tbl_id', $id)->first();
if ($workday) {
$validatedData = $request->validate([
'workday_id' => 'nullable',
'ro_feedback' => 'nullable',
'per_findings' => 'nullable',
'completion' => 'nullable',
'contract_findings' => 'nullable',
'contract_remarks' => 'nullable',
'contract_status' => 'nullable',
'updated_by' => 'required|integer',
]);

$workday->update([
'workday_id' => $validatedData['workday_id'] ?? $workday->workday_id,
'ro_feedback' => $validatedData['ro_feedback'] ?? $workday->ro_feedback,
'per_findings' => $validatedData['per_findings'] ?? $workday->per_findings,
'completion' => $validatedData['completion'] ?? $workday->completion,
'contract_status' => $validatedData['contract_status'] ?? $workday->contract_status,
'contract_remarks' => $validatedData['contract_remarks'] ?? $workday->contract_remarks,
'contract_findings' => $validatedData['contract_findings'] ?? $workday->contract_findings,
'updated_at' => now(),
]);
}

return response()->json(['message' => 'Record(s) updated successfully'], 200);
} catch (\Throwable $e) { // Use \Throwable to catch all errors
return response()->json([
'error' => $e->getMessage(), // Returns the exact error message
'trace' => $e->getTrace(), // Full stack trace (optional, for debugging)
], 500);
}
}