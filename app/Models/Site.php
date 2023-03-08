<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'site_director',
        'region',
        'created_by',
        'updated_by',
        'is_active',
    ];

    public function belongsToUser()
    {
        return $this->belongsTo(User::class);
    }

    public function programs()
    {
        return $this->hasMany('App\Models\Program');
    }
}
