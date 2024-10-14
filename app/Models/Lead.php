<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'leads';
    protected $fillable = [
        'lead_date',
        'lead_source',
        'lead_type',
        'lead_application_date',
        'lead_released_date',
        'lead_srid',
        'lead_prism_status',
        'lead_site',
        'lead_last_name',
        'lead_first_name',
        'lead_middle_name',
        'lead_contact_number',
        'lead_email_address',
        'lead_home_address',
        'lead_gen_source',
        'lead_spec_source',
        'lead_position',
    ];
}
