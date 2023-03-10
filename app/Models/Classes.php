<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
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
        'updated_by',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function dateRange()
    {
        return $this->belongsTo(DateRange::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function sla_reason()
    {
        return $this->hasMany(Sla_reason::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function cancelledByUser()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
}
