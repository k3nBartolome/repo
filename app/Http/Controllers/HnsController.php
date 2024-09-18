<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ApplicantInfo;
use App\Models\ApplicationInfo;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LeadsImport;
use Illuminate\Support\Facades\Validator;


class HnsController extends Controller
{
    public function index()
    {
        return response('');
    }

    public function create()
    {
        return response('');
    }

    public function storeLeads(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ai_first_name' => 'required',
            'ai_last_name' => 'required',
            'ai_middle_name' => 'nullable',
            'ai_suffix' => 'nullable',
            'ai_email_address' => 'required',
            'ai_contact_number' => 'required',
            'apn_gen_source' => 'required',
            'apn_specific_source' => 'required',
            'apn_site' => 'required',
            'position_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $applicantInfo = new ApplicantInfo();
        $applicantInfo->fill($request->all());
        $applicationInfo = new ApplicationInfo();
        $applicationInfo->fill($request->all());
        $applicationInfo->status_id=1;
        $applicationInfo->save();

        return response()->json([
            'applicantInfo' => $applicantInfo,
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
            Excel::import(new LeadsImport, $request->file('file'));

            return response()->json(['success' => 'Leads imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error importing leads: ' . $e->getMessage()], 500);
        }
    }

    public function show(request $request)
    {
        return response('');
    }

    public function edit(request $request)
    {
        return response('');
    }

    public function update(Request $request)
    {
        return response('');
    }

    public function destroy(request $request)
    {
        return response('');
    }
}
