<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Site;


class Program extends Model
{
    use HasFactory;
      protected $fillable = [
        'name',
        'description',
        'att_tagging',
        'site_id'
        'created_by',
        'updated_by',
        'is_active'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function programs()
    {
        return $this->belongsTo(Site::class);
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
