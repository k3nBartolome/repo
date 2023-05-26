<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $casts = [
        'requested_by' => 'array',
        'cancelled_by' => 'array',
        'condition' => 'array',
    ];

    protected $fillable = [
        'pushedback_id',
        'within_sla',
        'original_start_date',
        'pushback_start_date_ta',
        'pushback_start_date_wf',
        'requested_start_date_by_wf',
        'start_date_committed_by_ta',
        'supposed_start_date',
        'approved_date',
        'cancelled_date',
        'wfm_date_requested',
        'notice_weeks',
        'external_target',
        'internal_target',
        'notice_days',
        'pipeline_utilized',
        'total_target',
        'reason_for_counter_proposal',
        'remarks',
        'status',
        'type_of_hiring',
        'backfill',
        'growth',
        'category',
        'date_range_id',
        'update_status',
        'approved_status',
        'with_erf',
        'approved_by',
        'cancelled_by',
        'created_by',
        'program_id',
        'site_id',
        'erf_number',
        'condition',
        'agreed_start_date',
        'requested_by',
        'changes',
        'two_dimensional_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function dateRange()
    {
        return $this->belongsTo(DateRange::class, 'date_range_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function classes_staffing()
    {
        return $this->hasMany(ClassStaffing::ClassStaffing, 'classes_id');
    }
}
