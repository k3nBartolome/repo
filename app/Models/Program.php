<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Site;

class Program extends Model
{
    protected $fillable = [
        'name', 'description', 'program_group', 'user_id', 'site_id', 'created_by', 'updated_by', 'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

}
