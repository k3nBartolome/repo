<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bci extends Model
{
    use HasFactory;

    protected $table = 'bci'; // Specify the table name

    protected $fillable = [
        'bci_recruiter_name',
        'bci_time_started',
        'bci_time_ended',
        'bci_aht',
        'bci_integrity',
        'bci_orientation',
        'bci_psdm',
        'bci_achievement_orientation',
        'bci_stress_tolerance',
        'bci_sales_focus',
        'bci_score',
        'bci_status',
        'bci_notes',
        'bci_updated_by',
        'apn_id',
        'bci_remarks',
        'bci_added_by',
        'bci_last_update',
        'bci_added_date',
    ];
}
