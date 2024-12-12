<?php

namespace App\Models;
use App\Models\ApplicantSite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantData extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id', 
        'first_name', 
        'middle_name', 
        'last_name', 
        'email', 
        'contact_number',
    ];

    public function site()
    {
        return $this->belongsTo(ApplicantSite::class, 'site_id');
    }
}
