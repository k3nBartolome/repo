<?php
namespace App\Http\Controllers;
use App\Exports\AttendanceExport;
use App\Models\ApplicantData;
use App\Models\ApplicantSite;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
class ApplicantDataController extends Controller
{

    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'site_id' => 'required|exists:applicant_site,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
        ]);
    
        try {
            // Get the start of today (00:00:00) and end of today (23:59:59)
            $todayStart = Carbon::today()->startOfDay(); // 00:00:00
            $todayEnd = Carbon::today()->endOfDay(); // 23:59:59
    
            // Check if the email already exists today
            $emailExists = ApplicantData::where('email', $request->email)
                ->whereBetween('created_at', [$todayStart, $todayEnd]) // Check if created_at is within today's range
                ->exists();
    
            if ($emailExists) {
                // If email already exists today, return an error message
                return response()->json([
                    'message' => 'The email has already been used today.',
                ], 400);
            }
    
            // If no duplicate email found, proceed with creating the applicant data
            $applicant = ApplicantData::create($validatedData);
    
            return response()->json([
                'message' => 'Applicant data created successfully!',
                'applicant' => $applicant, // Include the created applicant data
            ], 201);
        } catch (QueryException $e) {
            // Log the exception details for debugging purposes
            Log::error('Database error: ' . $e->getMessage());
    
            // Return a generic error message to the user
            return response()->json([
                'message' => 'Failed to submit the applicant details. Please try again.',
            ], 500);
        } catch (\Exception $e) {
            // Catch any other exceptions and log them
            Log::error('Unexpected error: ' . $e->getMessage());
    
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    
    }
    public function index(Request $request)
    {
        // Get the parameters from the query string
        $siteId = $request->query('site_id');  // Match frontend filter site value
        $startDate = $request->query('min_date'); // Start date from frontend
        $endDate = $request->query('max_date');  // End date from frontend
        
        // Log the received parameters for debugging
        \Log::debug('Received Params:', [
            'siteId' => $siteId,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
        
        // Adjust the min and max date ranges by -1 day and +1 day respectively
        if ($startDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->subDay()->format('Y-m-d'); // Subtract 1 day from the start date
        }
    
        if ($endDate) {
            $endDate = \Carbon\Carbon::parse($endDate)->addDay()->format('Y-m-d'); // Add 1 day to the end date
        }
    
        // Build the query
        $query = ApplicantData::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                return $query->where('site_id', $siteId);  // Filter by site
            })
            ->when(!empty($startDate) && !empty($endDate), function ($query) use ($startDate, $endDate) {
                // Filter by date range (between adjusted min and max dates)
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            });
    
        // Log the SQL query (before executing the query)
        \Log::debug('SQL Query:', [$query->toSql()]);
    
        // Execute the query
        $applicants = $query->get();
        
        // Map applicants to the desired response format
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
        
        // Return the response
        return response()->json([
            'applicant' => $formattedApplicants,
            'message' => $applicants->isEmpty() ? 'No applicants found.' : 'Applicants retrieved successfully.',
        ], 200);
    }
    
    
    
    
public function ExportAttendance(Request $request)
{
    // Get the parameters from the query string
    $siteId = $request->query('site_id');  // Match frontend filter site value
    $startDate = $request->query('min_date'); // Start date from frontend
    $endDate = $request->query('max_date');  // End date from frontend
    
    // Log the received parameters for debugging
    \Log::debug('Received Params:', [
        'siteId' => $siteId,
        'startDate' => $startDate,
        'endDate' => $endDate
    ]);
    
    // Adjust the min and max date ranges by -1 day and +1 day respectively
    if ($startDate) {
        $startDate = \Carbon\Carbon::parse($startDate)->subDay()->format('Y-m-d'); // Subtract 1 day from the start date
    }

    if ($endDate) {
        $endDate = \Carbon\Carbon::parse($endDate)->addDay()->format('Y-m-d'); // Add 1 day to the end date
    }

    // Build the query
    $query = ApplicantData::with('site')
        ->when(!empty($siteId), function ($query) use ($siteId) {
            return $query->where('site_id', $siteId);  // Filter by site
        })
        ->when(!empty($startDate) && !empty($endDate), function ($query) use ($startDate, $endDate) {
            // Filter by date range (between adjusted min and max dates)
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        });

    // Log the SQL query (before executing the query)
    \Log::debug('SQL Query:', [$query->toSql()]);

    // Execute the query
    $applicants = $query->get();
    
    // Map applicants to the desired response format
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
    $formattedApplicantsArray = $formattedApplicants->toArray();
    return Excel::download(new AttendanceExport($formattedApplicantsArray), 'applicantAttendance.xlsx');
}

public function getCreatedAtRange()
{
    $minDate = ApplicantData::min('created_at');
    $maxDate = ApplicantData::max('created_at');

    return response()->json([
        'min_date' => $minDate ? \Carbon\Carbon::parse($minDate)->format('Y-m-d') : null,
        'max_date' => $maxDate ? \Carbon\Carbon::parse($maxDate)->format('Y-m-d') : null,
        'message' => ($minDate && $maxDate) ? 'Date range retrieved successfully.' : 'No data available.',
    ], 200);
}


}

