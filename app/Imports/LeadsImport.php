<?php

namespace App\Imports;

use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToModel, WithHeadingRow
{
    protected $leadAddedBy;

    public function __construct($leadAddedBy)
    {
        $this->leadAddedBy = $leadAddedBy;
    }

    public function model(array $row)
    {
        $validator = Validator::make($row, [
        'lead_site' => 'nullable|string',
        'lead_last_name' => 'nullable|string',
        'lead_first_name' => 'nullable|string',
        'lead_middle_name' => 'nullable|string',
        'lead_contact_number' => 'nullable|string',
        'lead_email_address' => 'nullable|email|unique:leads,lead_email_address',
        'lead_gen_source' => 'nullable|string',
        'lead_spec_source' => 'nullable|string',
        'lead_position' => 'nullable|string',
    ]);

        if ($validator->fails()) {
            return null;
        }

        try {
            return new Lead([
            'lead_site' => $row['lead_site'],
            'lead_last_name' => $row['lead_last_name'],
            'lead_first_name' => $row['lead_first_name'],
            'lead_middle_name' => $row['lead_middle_name'],
            'lead_contact_number' => $row['lead_contact_number'],
            'lead_email_address' => $row['lead_email_address'],
            'lead_gen_source' => $row['lead_gen_source'],
            'lead_spec_source' => $row['lead_spec_source'],
            'lead_position' => $row['lead_position'],
            'lead_date' => Carbon::now()->format('Y-m-d H:i'),
            'lead_added_by' => $this->leadAddedBy,
        ]);
        } catch (\Exception $e) {
            return null;
        }
    }
}
