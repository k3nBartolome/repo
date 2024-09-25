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
            'lead_date' => 'required',
            'lead_screener_name' => 'required',
            'lead_source' => 'required',
            'lead_type' => 'required',
            'lead_application_date' => 'required',
            'lead_released_date' => 'required',
            'lead_srid' => 'required',
            'lead_prism_status' => 'required',
            'lead_site_id' => 'required',
            'lead_last_name' => 'required',
            'lead_first_name' => 'required',
            'lead_middle_name' => 'required',
            'lead_contact_number' => 'required',
            'lead_email_address' => 'required',
            'lead_home_address' => 'required',
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
