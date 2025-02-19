public function updateEmployeeInfo(Request $request, $id)
{
try {


$validatedData = $request->validate([
'first_name' => 'nullable|string|max:255',
'middle_name' => 'nullable|string|max:255',
'last_name' => 'nullable|string|max:255',
'position' => 'nullable|string|max:255',
'account_type' => 'nullable|string|max:255',
'employee_id' => 'required|string|max:255',
'contact_number' => 'nullable|string|max:15',
'email_address' => 'nullable|email|max:255',
'birth_date' => 'nullable|date',
'hired_date' => 'nullable|date',

'employee_status' => 'nullable|string|max:255',
'employment_status' => 'nullable|string|max:255',
'hired_month' => 'nullable|string|max:50',
'updated_by' => 'nullable|string|max:50',
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
'region' => 'nullable|string',
'site' => 'nullable|integer',
'lob' => 'nullable|string',
'team_name' => 'nullable|string',
'project_code' => 'nullable|string',
'compliance_poc' => 'nullable|string',
'updated_by' => 'required|integer',
'employee_tbl_id' => 'nullable|integer',
'workday_id' => 'nullable|string',
'ro_feedback' => 'nullable|string',
'per_findings' => 'nullable|string',
'completion' => 'nullable|string',
'contract_findings' => 'nullable|string',
'contract_remarks' => 'nullable|string',
'contract_status' => 'nullable|string',
'updated_by' => 'required|integer',
]);

$employee = Employee::findOrFail($id);
$requirement = Requirements::where('employee_tbl_id', $id)->first();

$employee->first_name = $validatedData['first_name'];
$employee->middle_name = $validatedData['middle_name'] ?? null;
$employee->last_name = $validatedData['last_name'];
$employee->account_associate = $validatedData['position'] ?? null;
$employee->account_type = $validatedData['account_type'] ?? null;
$employee->employee_id = $validatedData['employee_id'];
$employee->contact_number = $validatedData['contact_number'] ?? null;
$employee->email = $validatedData['email_address'] ?? null;
$employee->birthdate = $validatedData['birth_date'] ?? null;
$employee->hired_date = $validatedData['hired_date'] ?? null;
$employee->employee_status = $validatedData['employee_status'] ?? null;
$employee->employment_status = $validatedData['employment_status'] ?? null;
$employee->hired_month = $validatedData['hired_month'] ?? null;
$employee->updated_at = now();
$employee->updated_by = $validatedData['updated_by'] ?? null;

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


// Find the requirements record by employee_tbl_id


if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

// Process the file upload for 'nbi_proof'
if ($request->hasFile('nbi_proof')) {
$file = $request->file('nbi_proof');

if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
$filePath = $file->storeAs('nbi_files', $fileName, 'public'); // Store in 'public' disk

Log::info('File Uploaded', [
'file' => $fileName,
'path' => $filePath,
]);

$requirement->nbi_file_name = $fileName; // Save the file name to the database
} else {
Log::error('Invalid file uploaded', ['file' => $file]);

return response()->json(['error' => 'Invalid file uploaded'], 422);
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

// Save and return response
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
$fileName = time().'_'.$file->getClientOriginalName();
$filePath = $file->storeAs('dt_files', $fileName, 'public'); // Store in 'public' disk

Log::info('File Uploaded', [
'file' => $fileName,
'path' => $filePath,
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
$fileName = time().'_'.$file->getClientOriginalName();
$filePath = $file->storeAs('peme_files', $fileName, 'public');

Log::info('File Uploaded', [
'file' => $fileName,
'path' => $filePath,
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
$fileName = time().'_'.$file->getClientOriginalName();
$filePath = $file->storeAs('sss_files', $fileName, 'public');

Log::info('File Uploaded', [
'file' => $fileName,
'path' => $filePath,
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



$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('phic_proof')) {
$file = $request->file('phic_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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



$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('pagibig_proof')) {
$file = $request->file('pagibig_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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



$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('tin_proof')) {
$file = $request->file('tin_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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



$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('health_certificate_proof')) {
$file = $request->file('health_certificate_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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



$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('occupational_permit_proof')) {
$file = $request->file('occupational_permit_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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




$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('ofac_proof')) {
$file = $request->file('ofac_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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


$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('sam_proof')) {
$file = $request->file('sam_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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



$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('oig_proof')) {
$file = $request->file('oig_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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


$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('cibi_proof')) {
$file = $request->file('cibi_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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



$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('bgc_proof')) {
$file = $request->file('bgc_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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


$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('birth_certificate_proof')) {
$file = $request->file('birth_certificate_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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



$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('dependent_birth_certificate_proof')) {
$file = $request->file('dependent_birth_certificate_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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


$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('marriage_certificate_proof')) {
$file = $request->file('marriage_certificate_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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



$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('scholastic_record_proof')) {
$file = $request->file('scholastic_record_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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


$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('previous_employment_proof')) {
$file = $request->file('previous_employment_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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


$requirement = Requirements::where('employee_tbl_id', $id)->first();

if (!$requirement) {
Log::error('Requirement not found', ['id' => $id]);

return response()->json(['error' => 'Requirement not found'], 404);
}

if ($request->hasFile('supporting_documents_proof')) {
$file = $request->file('supporting_documents_proof');
if ($file->isValid()) {
$fileName = time().'_'.$file->getClientOriginalName();
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
'compliance_poc' => $validatedData['project_code'] ?? $lob->compliance_poc,
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

public function updateWorkday(Request $request, $id)
{
Log::info('Update Workday Request Received', ['id' => $id, 'data' => $request->all()]);


$workday = Workday::where('employee_tbl_id', $id)->first();

if (!$workday) {
Log::error('Workday not found', ['id' => $id]);

return response()->json(['error' => 'Workday not found'], 404);
}

try {
// Update the Workday with new data and metadata
$workday->update([
'workday_id' => $validatedData['workday_id'] ?? $workday->workday_id,
'ro_feedback' => $validatedData['ro_feedback'] ?? $workday->ro_feedback,
'per_findings' => $validatedData['per_findings'] ?? $workday->per_findings,
'completion' => $validatedData['completion'] ?? $workday->completion,
'contract_findings' => $validatedData['contract_findings'] ?? $workday->contract_findings,
'contract_remarks' => $validatedData['contract_remarks'] ?? $workday->contract_remarks,
'contract_status' => $validatedData['contract_status'] ?? $workday->contract_status,
'updated_by' => $validatedData['updated_by'],
'workday_updated_at' => now(),
]);

Log::info('Workday successfully updated', ['id' => $id]);

return response()->json(['message' => 'Workday updated successfully'], 200);
} catch (\Exception $e) {
Log::error('Error updating Workday', ['id' => $id, 'error' => $e->getMessage()]);

return response()->json(['error' => 'Failed to update Workday'], 500);
}
