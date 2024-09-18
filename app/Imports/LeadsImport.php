<?php

namespace App\Imports;

use App\Models\ApplicantInfo;
use App\Models\ApplicationInfo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class LeadsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'ai_first_name' => 'required',
            'ai_last_name' => 'required',
            'ai_email_address' => 'required|email',
            'ai_contact_number' => 'required',
            'apn_gen_source' => 'required',
            'apn_specific_source' => 'required',
            'apn_site' => 'required',
            'position_id'=>'required'
        ]);

        if ($validator->fails()) {
            return null;
        }
        $applicantInfo = new ApplicantInfo([
            'ai_first_name' => $row['ai_first_name'],
            'ai_last_name' => $row['ai_last_name'],
            'ai_middle_name' => $row['ai_middle_name'] ?? null,
            'ai_suffix' => $row['ai_suffix'] ?? null,
            'ai_email_address' => $row['ai_email_address'],
            'ai_contact_number' => $row['ai_contact_number'],
        ]);
        $applicantInfo->save();
        $applicationInfo = new ApplicationInfo([
            'apn_gen_source' => $row['apn_gen_source'],
            'apn_specific_source' => $row['apn_specific_source'],
            'apn_site' => $row['apn_site'],
            'position_id'=>$row['position_id'],
            'status_id' => 1,

        ]);
        $applicationInfo->save();

        return $applicantInfo;
    }
}

