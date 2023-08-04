<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteInventory extends Model
{
    use HasFactory;
    protected $table = 'site_inventory';

    protected $fillable = [
        'item_less_id',
        'item_name',
        'quantity',
        'original_quantity',
        'budget_code',
        'type',
        'category',
        'date_expiry',
        'site_id',
        'is_active',
        'created_by',
        'cost',
        'total_cost',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
