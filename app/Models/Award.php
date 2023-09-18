<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use Notifiable;
    use HasFactory;
    protected $table = 'award';

    protected $fillable = [
        'award_status',
        'remarks',
        'awarded_quantity',
        'awardee_name',
        'awardee_hrid',
        'date_released',
        'file_name',
        'path',
        'inventory_item_id',
        'processed_by',
        'released_by',
        'site_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function items()
    {
        return $this->belongsTo(SiteInventory::class, 'inventory_item_id');
    }

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
