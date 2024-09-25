<?php

namespace App\Imports;

use App\Models\Lead;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'lead_date' => 'nullable|date',
            'lead_screener_name' => 'nullable|string',
            'lead_source' => 'nullable|string',
            'lead_type' => 'nullable|string',
            'lead_application_date' => 'nullable|date',
            'lead_released_date' => 'nullable|date',
            'lead_srid' => 'nullable|string',
            'lead_prism_status' => 'nullable|string',
            'lead_site_id' => 'nullable|integer',
            'lead_last_name' => 'nullable|string',
            'lead_first_name' => 'nullable|string',
            'lead_middle_name' => 'nullable|string',
            'lead_contact_number' => 'nullable|string',
            'lead_email_address' => 'nullable|email',
            'lead_home_address' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return null;
        }

        return new Lead([
            'lead_date' => $row['lead_date'],
            'lead_screener_name' => $row['lead_screener_name'],
            'lead_source' => $row['lead_source'],
            'lead_type' => $row['lead_type'],
            'lead_application_date' => $row['lead_application_date'],
            'lead_released_date' => $row['lead_released_date'],
            'lead_srid' => $row['lead_srid'],
            'lead_prism_status' => $row['lead_prism_status'],
            'lead_site_id' => $row['lead_site_id'],
            'lead_last_name' => $row['lead_last_name'],
            'lead_first_name' => $row['lead_first_name'],
            'lead_middle_name' => $row['lead_middle_name'],
            'lead_contact_number' => $row['lead_contact_number'],
            'lead_email_address' => $row['lead_email_address'],
            'lead_home_address' => $row['lead_home_address'],
        ]);
    }
}
