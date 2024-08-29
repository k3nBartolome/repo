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
        'transferred_by',
        'transferred_from',
        'transferred_quantity',
        'transferred_to',
        'transferred_date',
        'file_name',
        'path',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
    public function transferredFrom()
    {
        return $this->belongsTo(Site::class, 'transferred_from');
    }
}
