<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workday extends Model
{
    use HasFactory;
    protected $table = 'workday_table';
    protected $fillable = [
        'employee_tbl_id',
        'workday_id',
        'ro_feedback',
        'per_findings',
        'completion',
        'contract_findings',
        'contract_remarks',
        'contract_status',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_tbl_id','id');
    }
}
