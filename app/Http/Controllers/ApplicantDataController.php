<?php
namespace App\Http\Controllers;
use App\Exports\AttendanceExport;
use App\Models\ApplicantData;
use App\Models\ApplicantSite;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class ApplicantDataController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'site_id' => 'required|exists:applicant_site,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:applicant_data',
            'contact_number' => 'required|string|max:20',
        ]);

        $applicant = ApplicantData::create($validatedData);

        return response()->json([
            'message' => 'Applicant data created successfully!',
            'applicant' => $applicant->load('site'), // Include site details
        ], 201);
    }

    public function index($site_id = null)
{
    $site_id = is_numeric($site_id) ? (int) $site_id : null;

    $applicants = ApplicantData::with('site')
        ->when(!is_null($site_id), function ($query) use ($site_id) {
            return $query->where('site_id', $site_id);
        })
        ->get();
        $formattedApplicants = $applicants->map(function ($applicant) {
            return [
                'site' => $applicant->site->name ?? 'N/A',
                'last_name' => $applicant->last_name,
                'first_name' => $applicant->first_name,
                'middle_name' => $applicant->middle_name,
                'email' => $applicant->email,
                'contact_number' => $applicant->contact_number,
                'created_at' => $applicant->created_at->format('Y-m-d H:i:s'),
            ];
        });
    return response()->json([
        'applicant' => $formattedApplicants,
        'message' => $applicants->isEmpty() ? 'No applicants found.' : 'Applicants retrieved successfully.',
    ], 200);
}
public function ExportAttendance($site_id = null)
{
    $site_id = is_numeric($site_id) ? (int) $site_id : null;
    $applicants = ApplicantData::with('site')
        ->when($site_id, function ($query) use ($site_id) {
            return $query->where('site_id', $site_id);
        })
        ->get();
    $formattedApplicants = $applicants->map(function ($applicant) {
        return [
            'site' => $applicant->site->name ?? 'N/A',
            'last_name' => $applicant->last_name,
            'first_name' => $applicant->first_name,
            'middle_name' => $applicant->middle_name,
            'email' => $applicant->email,
            'contact_number' => $applicant->contact_number,
            'created_at' => $applicant->created_at ? $applicant->created_at->format('Y-m-d H:i:s') : 'N/A', 
        ];
    });
    $formattedApplicantsArray = $formattedApplicants->toArray();
    return Excel::download(new AttendanceExport($formattedApplicantsArray), 'applicantAttendance.xlsx');
}



}

