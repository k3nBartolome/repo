<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Site;
use App\Models\Program;
use App\Models\User;
class Classes extends Model
 {
    use HasFactory;
    protected $fillable = [
        'notice_weeks',
        'notice_days',
        'external_target',
        'internal_target',
        'target',
        'pipeline_utilized',
        'type_of_hiring',
        'within_sla',
        'with_erf',
        'reason_for_counter_proposal',
        'remarks',
        'status',
        'update_status',
        'approved_status',
        'approved_date',
        'cancelled_date',
        'date_requested',
        'delivery_date',
        'entry_date',
        'original_start_date',
        'pushback_start_date_ta',
        'pushback_start_date_wf',
        'requested_start_date_by_wf',
        'start_date_committed_by_ta',
        'supposed_start_date',
        'wfm_date_requested',
        'program_id',
        'site_id',
        'sla_reason_id',
        'cancelled_by',
        'created_by',
        'requested_by',
        'updated_by',
        'approved_by',
        'is_active',

    ];
    public function sites(){
        return $this->hasMany('App\Models\Site');
    }
    public function programs(){
        return $this->hasMany('App\Models\Program');
    }
    public function sla_reason(){
        return $this->hasMany('App\Models\Sla_reason');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function boot()
    {
    parent::boot();

    static::creating(function ($model) {
        if (auth()->check()) {
            $model->created_by = auth()->user()->id;
            $model->updated_by = auth()->user()->id;
        }
    });

    static::updating(function ($model) {
        if (auth()->check()) {
            $model->updated_by = auth()->user()->id;
        }
    });
    }

}
