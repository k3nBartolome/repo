<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'region',
        'created_by',
        'updated_by',
        'is_active'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function program()
        return $this->hasMany('App\Models\Program');
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
            $model->created_by = auth()->user()->id;
            $model->updated_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }
}

