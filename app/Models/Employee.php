<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $fillable = [
        'employee_id',
        'last_name',
        'first_name',
        'middle_name',
        'employee_status',
        'hired_date',
        'hired_month',
        'birthdate',
        'contact_number',
        'email',
        'account_associate',
        'employment_status',
        'employee_added_by',
        'qr_code_path',
    ];
}
