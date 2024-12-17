<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'name',
        'description',
        'site_director',
        'region',
        'created_by',
        'updated_by',
        'is_active',
    ];
    public function applicants()
    {
        return $this->hasMany(ApplicantData::class);
    }
    public function lob()
    {
        return $this->hasMany(Lob::class );
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function items()
    {
        return $this->hasMany(Items::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function programs()
    {
        return $this->hasMany(Program::class, 'site_id');
    }
}
