<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function sites()
    {
        return $this->hasMany('App\Models\Site');
    }
    public function programs()
    {
        return $this->hasMany('App\Models\Program');
    }
    public function sla_reasons()
    {
        return $this->hasMany('App\Models\Sla_reason');
    }
    public function created_by()
    {
        return $this->hasMany('App\Models\Class', 'created_by');
    }
    public function approved_by()
    {
        return $this->hasMany('App\Models\Class', 'approved_by');
    }
    public function cancelled_by()
    {
        return $this->hasMany('App\Models\Class', 'cancelled_by');
    }
    public function updated_by()
    {
        return $this->hasMany('App\Models\Class', 'created_by');
    }
}
