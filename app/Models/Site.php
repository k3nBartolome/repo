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

    public function belongsToUser()
    {
        return $this->belongsTo(User::class);
    }

    public function programs(){
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
