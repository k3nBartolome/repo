<?php

namespace App\Http\Controllers;

use App\Imports\LeadsImport;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{
    public function storeLeads(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_site' => 'nullable|string',
            'lead_last_name' => 'nullable|string',
            'lead_first_name' => 'nullable|string',
            'lead_middle_name' => 'nullable|string',
            'lead_contact_number' => 'nullable|string',
            'lead_email_address' => 'nullable|email',
            'lead_gen_source' => 'nullable|string',
            'lead_spec_source' => 'nullable|string',
            'lead_position' => 'nullable|string',
            'lead_added_by' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $leadinfo = new Lead();
        $leadinfo->fill($request->all());
        $leadinfo->lead_date = Carbon::now()->format('Y-m-d H:i');
        $leadinfo->save();

        return response()->json([
            'leadinfo' => $leadinfo,
        ]);
    }

    public function storeBulkLeads(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
            'lead_added_by' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            Excel::import(new LeadsImport($request->lead_added_by), $request->file('file'));

            return response()->json(['success' => 'Leads imported successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error importing leads: '.$e->getMessage()], 500);
        }
    }

    public function retrieveLeads(){

    }
}
