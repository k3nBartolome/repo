<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
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
            'hired_date' => 'nullable',
            'hired_month' => 'nullable',
            'birthdate' => 'nullable',
            'contact_number' => 'nullable',
            'email' => 'nullable|email|unique:employees,email',
            'account_associate' => 'nullable',
    ]);

        if ($validator->fails()) {
            return null;
        }

        try {
            return new Employee([
            'employee_id' => $row['employee_id'],
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
            'employee_added_by' => $this->employeeAddedBy,
        ]);
        } catch (\Exception $e) {
            return null;
        }
    }
}
