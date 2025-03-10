<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'site_id',
        'position'

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

    public function sitesCreated()
    {
        return $this->hasMany(Site::class, 'created_by');
    }

    public function sitesUpdated()
    {
        return $this->hasMany(Site::class, 'updated_by');
    }

    public function programs()
    {
        return $this->hasMany('App\Models\Program');
    }
   // app/Models/User.php
   public function sites()
   {
       return $this->belongsToMany(Site::class, 'user_site', 'user_id', 'site_id');
   }
   

    

}
