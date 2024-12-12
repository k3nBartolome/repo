<?php

namespace App\Http\Controllers;

use App\Models\ApplicantSite;
use Illuminate\Http\Request;

class ApplicantSiteController extends Controller
{
    /**
     * Display a listing of all applicant sites.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Fetch all applicant sites
        $applicantSites = ApplicantSite::all();

        // Return as JSON
        return response()->json($applicantSites);
    }

    /**
     * Display a single applicant site by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the applicant site by ID
        $applicantSite = ApplicantSite::find($id);

        // Check if the site exists
        if (!$applicantSite) {
            return response()->json(['message' => 'Applicant site not found'], 404);
        }

        // Return the found site as JSON
        return response()->json($applicantSite);
    }
}
