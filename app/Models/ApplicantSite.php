<?php

namespace App\Models;
use App\Models\ApplicantData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantSite extends Model
{
    use HasFactory;

    // Specify the table name (optional if it follows Laravel's naming conventions)
    protected $table = 'applicant_site';

    // Define fillable fields
    protected $fillable = ['name', 'description'];
   
}
