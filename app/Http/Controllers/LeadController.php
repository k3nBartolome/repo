<?php

namespace App\Http\Controllers;

use App\Imports\LeadsImport;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{
    public function storeLeads(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_date' => 'nullable|date',
            'lead_source' => 'nullable|string',
            'lead_type' => 'nullable|string',
            'lead_application_date' => 'nullable|date',
            'lead_released_date' => 'nullable|date',
            'lead_srid' => 'nullable|string',
            'lead_prism_status' => 'nullable|string',
            'lead_site' => 'nullable|integer',
            'lead_last_name' => 'nullable|string',
            'lead_first_name' => 'nullable|string',
            'lead_middle_name' => 'nullable|string',
            'lead_contact_number' => 'nullable|string',
            'lead_email_address' => 'nullable|email',
            'lead_home_address' => 'nullable|string',
            'lead_gen_source' => 'nullable|integer',
            'lead_spec_source' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $leadinfo = new Lead();
        $leadinfo->fill($request->all());

        return response()->json([
            'leadinfo' => $leadinfo,
        ]);
    }

    public function storeBulkLeads(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'file' => 'required|mimes:xlsx,xls',
    ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            Excel::import(new LeadsImport(), $request->file('file'));

            return response()->json(['success' => 'Leads imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error importing leads: '.$e->getMessage()], 500);
        }
    }
}
