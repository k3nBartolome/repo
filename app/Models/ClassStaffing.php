<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStaffing extends Model
{
    protected $table = 'class_staffing';
    protected $fillable = [
        'day_1',
        'day_2',
        'day_3',
        'day_4',
        'day_5',
        'day_6',
        'day_7',
        'day_8',
        'day_1_start_rate',
        'day_2_start_rate',
        'day_3_start_rate',
        'day_4_start_rate',
        'day_5_start_rate',
        'day_6_start_rate',
        'day_7_start_rate',
        'day_8_start_rate',
        'total_endorsed',
        'endorsed_rate',
        'internals_hires',
        'externals_hires',
        'internals_hires_all',
        'externals_hires_all',
        'add_extender_jo',
        'with_jo',
        'pending_jo',
        'pending_berlitz',
        'pending_ov',
        'pending_pre_emps',
        'additional_remarks',
        'pipeline',
        'pipeline_target',
        'pipeline_total',
        'percentage',
        'show_ups_internal',
        'show_ups_external',
        'show_ups_total',
        'deficit',
        'deficit_total',
        'fill_Rate',
        'hiring_status',
        'status',
        'over_hires',
        'fallout_reason',
        'catch_up_start',
        'catch_up_percentage',
        'classes_id',
        'updated_by',
        'created_by',
    ];

    public function classes()
    {
        return $this->belongsTo(Classes::classes, 'classes_id');
    }
}
