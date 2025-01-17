<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Requirements;
use App\Models\Lob;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class EmployeeImport implements ToModel, WithHeadingRow
{
    protected $employeeAddedBy;

    public function __construct($employeeAddedBy)
    {
        $this->employeeAddedBy = $employeeAddedBy;
    }

    public function model(array $row)
    {
        // Start a database transaction to ensure atomic operations
        DB::beginTransaction();
    
        try {
            // Skip the row if the email already exists in the database
            if (isset($row['email']) && Employee::where('email', $row['email'])->exists()) {
                Log::info('Skipping row due to existing email:', ['email' => $row['email']]);
                return null; // Skip this row
            }
    
            // Convert Excel serial date values to Y-m-d format if they exist
            $row['hired_date'] = $this->excelDateToDateString($row['hired_date']);
            $row['birthdate'] = $this->excelDateToDateString($row['birthdate']);
    
            // Validate row data after conversion
            $validator = Validator::make($row, [
                'employee_id' => 'nullable',
                'wd_id' => 'nullable',
                'last_name' => 'nullable',
                'first_name' => 'nullable',
                'middle_name' => 'nullable',
                'employee_status' => 'nullable',
                'hired_date' => 'nullable|date_format:Y-m-d', // Adjusted to match Y-m-d format
                'hired_month' => 'nullable|string',
                'birthdate' => 'nullable|date_format:Y-m-d', // Adjusted to match Y-m-d format
                'contact_number' => 'nullable',
                'email' => 'nullable|email', // Removed unique rule as we handle it manually
                'account_associate' => 'nullable',
                'account_type' => 'nullable',
                'employment_status' => 'nullable',
                'site' => 'nullable',
            ]);
    
            if ($validator->fails()) {
                Log::error('Validation failed for row:', $row);
                Log::error('Validation errors:', $validator->errors()->all());
                return null; // Skip the invalid row
            }
    
            // Create employee record
            $employee = Employee::create([
                'employee_id' => $row['employee_id'],
                'wd_id' => $row['wd_id'],
                'last_name' => $row['last_name'],
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'employee_status' => $row['employee_status'],
                'hired_date' => $row['hired_date'],
                'hired_month' => $row['hired_month'],
                'birthdate' => $row['birthdate'],
                'contact_number' => $row['contact_number'],
                'email' => $row['email'],
                'account_associate' => $row['account_associate'],
                'account_type' => $row['account_type'],
                'employment_status' => $row['employment_status'],
                'employee_added_by' => $this->employeeAddedBy,
            ]);
    
            // Create related Requirements record
            Requirements::create([
                'employee_tbl_id' => $employee->id,
            ]);
    
            // Safely handle missing 'site' field by checking if it's set
            $site = isset($row['site']) ? $row['site'] : null;
    
            // Create related LOB record with the site value
            Lob::create([
                'employee_tbl_id' => $employee->id,
                'site' => $site,
            ]);
    
            // Commit the transaction after successful inserts
            DB::commit();
            return $employee; // Return employee model for further processing if needed
        } catch (\Exception $e) {
            // Rollback transaction on error and log the exception
            DB::rollBack();
            Log::error('Error occurred while saving employee:', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    // Helper function to convert Excel date serial number to a string date in Y-m-d format
    private function excelDateToDateString($excelDate)
    {
        if (is_numeric($excelDate)) {
            // Convert Excel serial date number to a date string (Y-m-d format)
            $excelDate = Date::excelToDateTimeObject($excelDate);
            return $excelDate->format('Y-m-d'); // Return date in the desired format (Y-m-d)
        }
    
        // If it's already in a string format (like Y-m-d or m/d/Y), just return it
        if (Carbon::hasFormat($excelDate, 'Y-m-d') || Carbon::hasFormat($excelDate, 'm/d/Y')) {
            return Carbon::createFromFormat('m/d/Y', $excelDate)->format('Y-m-d');
        }
    
        // Return the input date in the same format as received if it's already a string
        return $excelDate;
    }
    
}
