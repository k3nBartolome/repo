<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardPremium extends Model
{
    use HasFactory;
    protected $table = 'award_premium';
    protected $fillable = [
        'award_status',
        'remarks',
        'awarded_quantity',
        'awardee_name',
        'awardee_hrid',
        'date_released',
        'file_name',
        'path',
        'item_id',
        'processed_by',
        'released_by',
        'site_id',
    ];

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
