<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgSpecTest extends Model
{
    use HasFactory;

    protected $table = 'prog_spec_test'; // Specify the table name

    protected $fillable = [
        'pst_date',
        'pst_score',
        'pst_type',
        'pst_status',
        'pst_updated_by',
        'apn_id',
        'pst_remarks',
        'pst_added_by',
        'pst_last_update',
        'pst_added_date',
    ];
}
