<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Requirements;
use App\Models\Lob;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow
{
    protected $employeeAddedBy;

    public function __construct($employeeAddedBy)
    {
        $this->employeeAddedBy = $employeeAddedBy;
    }

    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'employee_id' => 'nullable',
            'last_name' => 'nullable',
            'first_name' => 'nullable',
            'middle_name' => 'nullable',
            'employee_status' => 'nullable',
            'hired_date' => 'nullable|date_format:m/d/Y',
            'hired_month' => 'nullable|integer',
            'birthdate' => 'nullable|date_format:m/d/Y',
            'contact_number' => 'nullable',
            'email' => 'nullable|email|unique:employees,email',
            'account_associate' => 'nullable',
            'account_type' => 'nullable',
            'employment_status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return null;
        }

        try {
            // Parse and reformat dates
            $hiredDate = isset($row['hired_date']) ? Carbon::createFromFormat('m/d/Y', $row['hired_date'])->format('Y-m-d') : null;
            $birthdate = isset($row['birthdate']) ? Carbon::createFromFormat('m/d/Y', $row['birthdate'])->format('Y-m-d') : null;

            // Create employee record
            $employee = Employee::create([
                'employee_id' => $row['employee_id'],
                'last_name' => $row['last_name'],
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'employee_status' => $row['employee_status'],
                'hired_date' => $hiredDate,
                'hired_month' => $row['hired_month'],
                'birthdate' => $birthdate,
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

            // Create related LOB record
            Lob::create([
                'employee_tbl_id' => $employee->id,
            ]);

            return $employee;
        } catch (\Exception $e) {
            return null;
        }
    }
}
