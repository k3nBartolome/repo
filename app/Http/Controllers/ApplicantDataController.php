<?php
namespace App\Http\Controllers;

use App\Models\ApplicantData;
use App\Models\ApplicantSite;
use Illuminate\Http\Request;

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

    public function index()
    {
        $applicants = ApplicantData::with('site')->get();

        return response()->json($applicants);
    }
}

