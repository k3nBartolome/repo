<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lob extends Model
{
    use HasFactory;
    protected $table = 'lob';
    protected $fillable = [
        'employee_tbl_id',
        'region',
        'site',
        'lob',
        'team_name',
        'project_code',
    ];
}
